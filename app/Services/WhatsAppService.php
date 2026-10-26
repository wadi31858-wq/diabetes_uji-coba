<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WhatsAppService
{
    /**
     * Masa berlaku kode OTP dalam menit
     */
    const OTP_EXPIRY_MINUTES = 5;

    /**
     * Batas maksimal percobaan salah
     */
    const MAX_ATTEMPTS = 3;

    /**
     * Jeda minimum antara permintaan OTP (detik)
     */
    const COOLDOWN_SECONDS = 60;

    /**
     * Generate dan kirim kode OTP ke nomor WhatsApp pengguna
     */
    public function sendOtp(User $user): array
    {
        // 1. Cek nomor telepon
        $phone = $this->normalizePhone($user->phone ?? '');
        if (empty($phone)) {
            return [
                'success' => false,
                'message' => 'Nomor WhatsApp belum terdaftar pada akun ini. Silakan hubungi administrator.'
            ];
        }

        // 2. Cek Cooldown (jangan spam kirim)
        if ($user->otp_expires_at) {
            $createdApprox = Carbon::parse($user->otp_expires_at)->subMinutes(self::OTP_EXPIRY_MINUTES);
            $secondsSinceLast = Carbon::now()->diffInSeconds($createdApprox, false);
            // Jika baru dikirim kurang dari COOLDOWN_SECONDS lalu
            if (abs($secondsSinceLast) < self::COOLDOWN_SECONDS && Carbon::now()->lt($user->otp_expires_at)) {
                $wait = self::COOLDOWN_SECONDS - abs($secondsSinceLast);
                return [
                    'success' => false,
                    'message' => "Mohon tunggu {$wait} detik sebelum meminta kode verifikasi baru."
                ];
            }
        }

        // 3. Generate 6 digit angka acak yang aman
        $otp = (string) random_int(100000, 999999);

        // 4. Simpan ke database dengan masa berlaku 5 menit
        $user->update([
            'otp_code' => bcrypt($otp),
            'otp_expires_at' => Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES),
            'otp_attempts' => 0,
        ]);

        // Simpan plaintext OTP hanya di cache/session lokal sementara untuk development/testing jika belum ada API key
        session(['last_otp_sent_to' => substr($phone, 0, 4) . '****' . substr($phone, -3)]);

        // 5. Cek Konfigurasi Gateway Eksternal
        $apiToken = env('WHATSAPP_API_TOKEN');
        $provider = env('WHATSAPP_PROVIDER', 'fonnte'); // fonnte / wablas / official

        $messageText = "*KODE VERIFIKASI LOGIN ADMIN*\n\n"
                     . "Kode verifikasi Anda adalah: *{$otp}*\n\n"
                     . "Kode ini berlaku selama " . self::OTP_EXPIRY_MINUTES . " menit.\n"
                     . "JANGAN berikan kode ini kepada siapa pun demi keamanan akun Diabetes Uji Coba Anda.";

        if (empty($apiToken)) {
            // Mode ketika API Token belum diatur di .env
            Log::info("WhatsApp OTP Generated for user {$user->id} ({$phone}): [SECURE_DISPATCH]. WhatsApp API Token is not configured in .env.");

            // Beri notifikasi ramah bahwa gateway belum dihubungkan dengan API key
            return [
                'success' => true,
                'simulated' => true,
                'phone_masked' => substr($phone, 0, 4) . '****' . substr($phone, -3),
                'message' => 'Kode verifikasi telah dibuat. Catatan: Kredensial gateway WhatsApp belum dikonfigurasi di .env (WHATSAPP_API_TOKEN). Untuk pengujian lokal, kode OTP dapat diperiksa di server log atau hubungi pengelola sistem.',
                'debug_code' => config('app.debug') ? $otp : null
            ];
        }

        // 6. Eksekusi Pengiriman Melalui Provider
        try {
            if ($provider === 'fonnte') {
                $response = Http::withHeaders([
                    'Authorization' => $apiToken
                ])->timeout(10)->post('https://api.fonnte.com/send', [
                    'target' => $phone,
                    'message' => $messageText,
                    'countryCode' => '62',
                ]);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'phone_masked' => substr($phone, 0, 4) . '****' . substr($phone, -3),
                        'message' => 'Kode verifikasi berhasil dikirim ke nomor WhatsApp Anda.'
                    ];
                }

                Log::error('Fonnte WhatsApp API failed: ' . $response->body());
            } elseif ($provider === 'official') {
                $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');
                $response = Http::withToken($apiToken)->timeout(10)->post("https://graph.facebook.com/v19.0/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone,
                    'type' => 'text',
                    'text' => ['body' => $messageText]
                ]);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'phone_masked' => substr($phone, 0, 4) . '****' . substr($phone, -3),
                        'message' => 'Kode verifikasi resmi WhatsApp berhasil dikirim.'
                    ];
                }

                Log::error('Meta Cloud WhatsApp API failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Exception sending WhatsApp OTP: ' . $e->getMessage());
        }

        return [
            'success' => false,
            'message' => 'Gagal mengirim kode via WhatsApp Gateway. Pastikan kredensial API dan koneksi internet server aktif.'
        ];
    }

    /**
     * Verifikasi kode OTP yang dimasukkan admin
     */
    public function verifyOtp(User $user, string $inputCode): array
    {
        // 1. Cek apakah ada kode yang aktif
        if (empty($user->otp_code) || empty($user->otp_expires_at)) {
            return [
                'success' => false,
                'message' => 'Tidak ada kode verifikasi aktif. Silakan minta kode baru.'
            ];
        }

        // 2. Cek masa berlaku
        if (Carbon::now()->gt(Carbon::parse($user->otp_expires_at))) {
            $user->update(['otp_code' => null, 'otp_expires_at' => null]);
            return [
                'success' => false,
                'message' => 'Kode verifikasi telah kedaluwarsa. Silakan minta kode verifikasi baru.'
            ];
        }

        // 3. Cek batas percobaan
        if ($user->otp_attempts >= self::MAX_ATTEMPTS) {
            $user->update(['otp_code' => null, 'otp_expires_at' => null, 'otp_attempts' => 0]);
            return [
                'success' => false,
                'message' => 'Batas percobaan salah telah terlampaui. Kode dibatalkan demi keamanan. Silakan minta kode baru.'
            ];
        }

        // 4. Verifikasi hash kode OTP
        if (!password_verify($inputCode, $user->otp_code)) {
            $user->increment('otp_attempts');
            $sisa = self::MAX_ATTEMPTS - $user->otp_attempts;
            return [
                'success' => false,
                'message' => "Kode verifikasi salah. Sisa kesempatan mencoba: {$sisa} kali."
            ];
        }

        // 5. Berhasil: Bersihkan OTP agar tidak bisa dipakai ulang
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0
        ]);

        return [
            'success' => true,
            'message' => 'Verifikasi WhatsApp berhasil.'
        ];
    }

    /**
     * Normalisasi nomor telepon ke format internasional (e.g. 0812 -> 62812)
     */
    public function normalizePhone(string $rawPhone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62' . substr($cleaned, 1);
        } elseif (str_starts_with($cleaned, '8')) {
            $cleaned = '62' . $cleaned;
        }
        return $cleaned;
    }
}

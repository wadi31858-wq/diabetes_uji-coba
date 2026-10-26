<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Assessment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComprehensiveIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Pastikan akun admin tersedia
        User::updateOrCreate(
            ['email' => 'admin@klikdokter.com'],
            [
                'name' => 'Administrator Diabetes Uji Coba',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890'
            ]
        );
    }

    /**
     * 1. Pengunjung dapat mengakses halaman publik
     */
    public function test_guest_can_access_public_pages(): void
    {
        $responseHome = $this->get('/');
        $responseHome->assertStatus(200);
        $responseHome->assertSee('Diabetes Uji Coba');

        $responseArticles = $this->get('/berita-artikel');
        $responseArticles->assertStatus(200);

        $responseQuiz = $this->get('/cek-risiko');
        $responseQuiz->assertStatus(200);
        $responseQuiz->assertSee('Diabetes');

        $responseLogin = $this->get('/login');
        $responseLogin->assertStatus(200);
        $responseLogin->assertSee('Masuk Admin');
    }

    /**
     * 2. Pengunjung dilarang mengakses riwayat (Redirect ke /login)
     */
    public function test_guest_is_redirected_when_accessing_history(): void
    {
        $response = $this->get('/riwayat');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * 3. Admin dapat login menggunakan email dan password yang valid
     */
    public function test_admin_can_login_with_valid_credentials(): void
    {
        $this->flushSession();

        $response = $this->post('/login', [
            'email' => 'admin@klikdokter.com',
            'password' => 'admin123'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    /**
     * 4. Login ditolak jika kredensial salah
     */
    public function test_admin_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@klikdokter.com',
            'password' => 'passwordsalah'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * 5. Admin yang sudah login dapat mengakses riwayat audit
     */
    public function test_authenticated_admin_can_access_history(): void
    {
        $admin = User::where('email', 'admin@klikdokter.com')->first();
        
        $response = $this->actingAs($admin)->get('/riwayat');
        $response->assertStatus(200);
        $response->assertSee('Panel Khusus Administrator');
    }

    /**
     * 6. Alur verifikasi WhatsApp OTP
     */
    public function test_whatsapp_otp_request_and_verification_flow(): void
    {
        $user = User::where('email', 'admin@klikdokter.com')->first();

        // Request OTP
        $responseReq = $this->postJson('/login/whatsapp/request', [
            'identifier' => '081234567890'
        ]);

        $responseReq->assertStatus(200);
        $responseReq->assertJson(['success' => true]);

        $user->refresh();
        $this->assertNotNull($user->otp_code);
        $this->assertNotNull($user->otp_expires_at);

        // Verifikasi dengan OTP salah
        $responseWrong = $this->postJson('/login/whatsapp/verify', [
            'user_id' => $user->id,
            'code' => '999999'
        ]);
        $responseWrong->assertJson(['success' => false]);

        // Verifikasi dengan OTP yang valid
        $user->otp_code = Hash::make('123456');
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        $responseCorrect = $this->postJson('/login/whatsapp/verify', [
            'user_id' => $user->id,
            'code' => '123456'
        ]);

        $responseCorrect->assertStatus(200);
        $responseCorrect->assertJson(['success' => true]);
        $this->assertAuthenticated();
    }

    /**
     * 7. Form Cek Risiko memproses pertanyaan lama & baru dengan kompatibilitas penuh
     */
    public function test_assessment_prediction_with_compatible_features(): void
    {
        $payload = [
            'gender' => 'Laki-laki',
            'age' => 45,
            'height' => 170,
            'weight' => 75,
            'high_bp' => 1,
            'high_chol' => 1,
            'phys_activity' => 0,
            'family_history' => 1,
            'smoker' => 1,
            'fruits_veggies' => 0,
            'chol_check' => 1,
            'heart_disease' => 0,
            'gestational_history' => 0,
        ];

        $response = $this->post('/predict', $payload);
        $response->assertStatus(302);
        $response->assertRedirect('/hasil');

        // Pastikan tersimpan di database
        $this->assertDatabaseHas('assessments', [
            'gender' => 'Laki-laki',
            'high_bp' => 1,
            'smoker' => 1,
        ]);
    }

    /**
     * 8. Endpoint Agentic AI merespons pertanyaan kesehatan
     */
    public function test_agentic_ai_chat_endpoint_responds_reliably(): void
    {
        $response = $this->postJson('/agent/chat', [
            'message' => 'Apa itu diabetes?',
            'history' => [],
            'assessment' => null
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'reply',
            'reasoning_steps'
        ]);

        $data = $response->json();
        $this->assertEquals('success', $data['status']);
        $this->assertNotEmpty($data['reply']);
    }
}

<?php

namespace App\Services\Agent\Tools;

class ExerciseRecommendationTool
{
    /**
     * Menghasilkan panduan olahraga adaptif untuk meningkatkan translokasi GLUT4 tanpa lonjakan tensi mendadak.
     */
    public function execute(array $params): array
    {
        $age = (int)($params['age'] ?? 30);
        $bmi = (float)($params['bmi'] ?? 24.5);
        $highBp = !empty($params['high_bp']);
        $currentlyActive = !empty($params['phys_activity']);

        // Intensitas dan durasi target
        if ($currentlyActive) {
            $aerobicTarget = '150 - 200 menit per minggu (30-40 menit, 5x seminggu)';
            $intensity = 'Sedang hingga Menengah (Moderate-to-Vigorous)';
        } else {
            $aerobicTarget = 'Mulai bertahap dari 75 - 150 menit per minggu (15-20 menit, 4-5x seminggu)';
            $intensity = 'Ringan hingga Sedang (Low-to-Moderate, jalan cepat bertahap)';
        }

        // Pilihan olahraga aerobik
        $aerobicOptions = [
            'Jalan Cepat (Brisk Walking): 4-5 km/jam di jalur datar, sangat efektif menurunkan gula darah postprandial.',
            'Bersepeda Statis / Santai: Beban benturan sendi lutut rendah, ideal untuk profil BMI > 25.',
            'Berenang: Melatih kapasitas kardiorespirasi tanpa membebani persendian.'
        ];

        // Latihan resistensi / beban
        $resistanceOptions = [
            'Squat Kursi (Chair Squat): 8-12 repetisi x 3 set (mengaktifkan otot quadriceps, penyimpan glukosa terbesar).',
            'Push-up Dinding / Lutut: 10 repetisi x 3 set.',
            'Plank Statis di Lantai: 15-30 detik x 3 repetisi.'
        ];

        // Kontraindikasi dan peringatan khusus
        $safetyPrecautions = [];
        if ($highBp) {
            $safetyPrecautions[] = 'Hindari teknik manuver Valsalva (menahan napas kuat-kuat saat mengangkat beban berat) karena dapat memicu lonjakan tekanan darah mendadak.';
            $safetyPrecautions[] = 'Prioritaskan olahraga ritmis aerobik (jalan kaki, sepeda) yang terbukti membantu menurunkan tekanan darah perifer hingga 5-7 mmHg.';
        }
        if ($bmi >= 30.0) {
            $safetyPrecautions[] = 'Hindari olahraga dengan benturan tinggi (lompat tali keras / lari sprint di aspal) untuk melindungi sendi lutut dan tumit.';
        }
        $safetyPrecautions[] = 'Lakukan aktivitas fisik 30–60 menit setelah makan besar untuk menekan lonjakan glukosa darah puncak.';
        $safetyPrecautions[] = 'Segera istirahat dan periksa kadar gula jika mengalami sensasi limbung, pusing berputar, gemetar dingin, atau nyeri dada.';

        return [
            'success' => true,
            'tool' => 'ExerciseRecommendationTool',
            'data' => [
                'target_duration' => $aerobicTarget,
                'target_intensity' => $intensity,
                'aerobic_options' => $aerobicOptions,
                'resistance_options' => $resistanceOptions,
                'safety_precautions' => $safetyPrecautions
            ]
        ];
    }
}

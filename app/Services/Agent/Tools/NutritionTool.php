<?php

namespace App\Services\Agent\Tools;

class NutritionTool
{
    /**
     * Menghasilkan rencana nutrisi & menu harian rendah indeks glikemik.
     */
    public function execute(array $params): array
    {
        $category = $params['category'] ?? 'Sedang';
        $targetCalories = (int)($params['target_calories'] ?? 2000);
        $sugarLimit = (int)($params['sugar_limit_grams'] ?? 20);
        $preference = strtolower($params['preference'] ?? 'indonesia');

        // Alokasi Kalori per Waktu Makan
        $breakfastCal = round($targetCalories * 0.25);
        $lunchCal = round($targetCalories * 0.35);
        $dinnerCal = round($targetCalories * 0.25);
        $snackCal = round($targetCalories * 0.15);

        $meals = [
            'breakfast' => [
                'time' => '06.30 - 08.00 WIB',
                'target_kcal' => $breakfastCal,
                'menu_utama' => 'Bubur oatmeal / havermut tawar (40g) diseduh air hangat dengan taburan 1 sdm biji chia, ditemani 1 butir telur rebus matang dan 1 potong pepaya segar (100g).',
                'menu_alternatif' => '1 mangkuk kecil nasi beras merah (100g), pepes tahu jamur kukus tanpa minyak, dan sayur bening bayam jagung manis porsi kecil.',
                'minuman' => 'Air putih hangat / Teh hijau tawar hangat (tanpa gula pasir/madu).'
            ],
            'lunch' => [
                'time' => '12.00 - 13.00 WIB',
                'target_kcal' => $lunchCal,
                'konsep' => 'Prinsip Piring Model T Kemenkes RI (1/2 Sayur, 1/4 Karbohidrat Kompleks, 1/4 Lauk Protein)',
                'karbohidrat' => '100-150g nasi merah / nasi shirataki / ubi jalar kukus (GI Rendah: <55).',
                'protein' => '1 ekor ikan kembung bakar bumbu kunyit / 1 potong dada ayam panggang tanpa kulit + 1 potong tempe bacem kukus.',
                'sayuran' => 'Sayur capcay kuah bening wortel buncis sawi / lalapan timun, selada air, dan tomat segar.',
                'minuman' => 'Air putih 1-2 gelas.'
            ],
            'dinner' => [
                'time' => '18.30 - 19.30 WIB',
                'target_kcal' => $dinnerCal,
                'menu' => 'Sup ayam bening dengan kembang kol, wortel, dan tahu sutra (rendah karbohidrat olahan di malam hari).',
                'aturan' => 'Selesaikan makan malam sebelum pukul 20.00 untuk menjaga sensitivitas reseptor insulin saat istirahat malam.'
            ],
            'snacks' => [
                'time' => 'Di antara makan pagi-siang (10.00) atau siang-malam (16.00)',
                'target_kcal' => $snackCal,
                'pilihan' => [
                    '1 buah apel fuji / apel malang dengan kulitnya (kaya pektin pembendung gula).',
                    '1 genggam kecil (25g) kacang almond panggang / edamame rebus tanpa garam.',
                    '1 potong buah alpukat segar tanpa kental manis/sirup.'
                ]
            ]
        ];

        return [
            'success' => true,
            'tool' => 'NutritionTool',
            'data' => [
                'target_calories' => $targetCalories,
                'sugar_limit_grams' => $sugarLimit,
                'meals' => $meals,
                'rules' => [
                    'Hindari karbohidrat sederhana: nasi putih pulen panas berlebih, tepung terigu, mie instan, lontong.',
                    "Batasi asupan gula bebas (sukrosa, fruktosa cair, kental manis) maksimal {$sugarLimit} gram/hari.",
                    'Utamakan teknik memasak kukus, rebus, panggang, atau pepes dibandingkan deep-fried (goreng rendam minyak).'
                ]
            ]
        ];
    }
}

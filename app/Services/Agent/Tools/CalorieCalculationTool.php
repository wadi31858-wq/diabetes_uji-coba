<?php

namespace App\Services\Agent\Tools;

class CalorieCalculationTool
{
    /**
     * Menghitung estimasi energi, target kalori, dan batas makronutrien (Mifflin-St Jeor).
     */
    public function execute(array $params): array
    {
        $gender = strtolower($params['gender'] ?? 'laki-laki');
        $age = max(10, min(120, (int)($params['age'] ?? 30)));
        $height = max(50, min(250, (float)($params['height'] ?? 170)));
        $weight = max(20, min(300, (float)($params['weight'] ?? 65)));
        $bmi = (float)($params['bmi'] ?? round($weight / pow($height / 100, 2), 1));
        $active = !empty($params['phys_activity']);
        $category = $params['category'] ?? 'Sedang';

        // Rumus BMR Mifflin-St Jeor
        if ($gender === 'laki-laki' || $gender === 'pria' || $gender === 'male') {
            $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
        } else {
            $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
        }
        $bmr = round($bmr);

        // Faktor Aktivitas Fisik (TDEE)
        $activityMultiplier = $active ? 1.4 : 1.2;
        $tdee = round($bmr * $activityMultiplier);

        // Target Kalori Adaptif
        if ($bmi >= 25.0) {
            $targetCalories = max(1400, $tdee - 450);
            $calorieStatus = 'Defisit Terkontrol (-450 kcal untuk penurunan BB bertahap)';
        } elseif ($bmi < 18.5) {
            $targetCalories = $tdee + 300;
            $calorieStatus = 'Surplus Sehat (+300 kcal)';
        } else {
            $targetCalories = $tdee;
            $calorieStatus = 'Maintenance Kalori Optimal (Mempertahankan Berat Badan Ideal)';
        }

        // Distribusi Makronutrien Ramah Diabetes (Karbohidrat Kompleks 45%, Protein 25%, Lemak Sehat 30%)
        $carbsGrams = round(($targetCalories * 0.45) / 4);
        $proteinGrams = round(($targetCalories * 0.25) / 4);
        $fatGrams = round(($targetCalories * 0.30) / 9);

        // Batas Konsumsi Gula Tambahan (WHO / Kemenkes RI)
        $sugarLimit = $category === 'Tinggi' ? 15 : ($category === 'Sedang' ? 20 : 25);
        $waterLiters = round($weight * 0.033, 1);
        $fiberGrams = 25 + ($gender === 'laki-laki' ? 5 : 0);

        return [
            'success' => true,
            'tool' => 'CalorieCalculationTool',
            'data' => [
                'bmr' => $bmr,
                'tdee' => $tdee,
                'target_calories' => $targetCalories,
                'calorie_status' => $calorieStatus,
                'carbs_grams' => $carbsGrams,
                'protein_grams' => $proteinGrams,
                'fat_grams' => $fatGrams,
                'sugar_limit_grams' => $sugarLimit,
                'fiber_grams' => $fiberGrams,
                'water_liters' => $waterLiters
            ]
        ];
    }
}

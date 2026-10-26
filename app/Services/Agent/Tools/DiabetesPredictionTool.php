<?php

namespace App\Services\Agent\Tools;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class DiabetesPredictionTool
{
    /**
     * Menjalankan evaluasi prediksi risiko diabetes menggunakan model Machine Learning XGBoost
     * dengan fallback algoritma skoring CDC BRFSS / KlikDokter.
     */
    public function execute(array $params): array
    {
        $gender = strtolower(trim($params['gender'] ?? 'laki-laki'));
        $genderLabel = in_array($gender, ['wanita', 'perempuan', 'female']) ? 'Perempuan' : 'Laki-laki';
        
        $age = isset($params['age']) ? (int)$params['age'] : 30;
        $height = isset($params['height']) ? (float)$params['height'] : 170.0;
        $weight = isset($params['weight']) ? (float)$params['weight'] : 65.0;

        // Validasi input batas wajar
        if ($age < 1 || $age > 120 || $height < 50 || $height > 250 || $weight < 20 || $weight > 300) {
            return [
                'success' => false,
                'tool' => 'DiabetesPredictionTool',
                'error' => 'Parameter usia, tinggi, atau berat badan di luar rentang klinis wajar.',
            ];
        }

        $highBp = !empty($params['high_bp']) ? 1 : 0;
        $highChol = !empty($params['high_chol']) ? 1 : 0;
        $physActivity = isset($params['phys_activity']) ? (int)!empty($params['phys_activity']) : 1;
        $familyHistory = !empty($params['family_history']) ? 1 : 0;
        $smoker = !empty($params['smoker']) ? 1 : 0;
        $heartDisease = !empty($params['heart_disease']) ? 1 : 0;

        // Hitung BMI
        $hM = $height / 100.0;
        $bmi = round($weight / ($hM * $hM), 1);

        // Kategori BMI Asia-Pasifik
        if ($bmi < 18.5) {
            $bmiCat = 'Berat Badan Kurang (Underweight)';
        } elseif ($bmi < 23.0) {
            $bmiCat = 'Normal / Ideal';
        } elseif ($bmi < 25.0) {
            $bmiCat = 'Kelebihan Berat Badan (Overweight)';
        } elseif ($bmi < 30.0) {
            $bmiCat = 'Obesitas Tingkat 1';
        } else {
            $bmiCat = 'Obesitas Tingkat 2';
        }

        // Payload untuk predict_cli.py
        $payload = [
            'gender' => $genderLabel,
            'age' => $age,
            'height' => $height,
            'weight' => $weight,
            'bmi' => $bmi,
            'high_bp' => $highBp,
            'high_chol' => $highChol,
            'phys_activity' => $physActivity,
            'family_history' => $familyHistory,
            'Smoker' => $smoker,
            'Fruits' => 1,
            'Veggies' => 1,
            'CholCheck' => 1,
            'HeartDiseaseorAttack' => $heartDisease,
            'heart_disease' => $heartDisease,
        ];

        $mlResult = null;
        try {
            $scriptPath = base_path('predict_cli.py');
            $pythonBin = $this->getPythonBinary();
            $process = new Process([$pythonBin, $scriptPath]);
            $process->setInput(json_encode($payload));
            $process->setTimeout(15);
            $process->run();

            if ($process->isSuccessful()) {
                $output = trim($process->getOutput());
                $parsed = json_decode($output, true);
                if (is_array($parsed) && isset($parsed['category'])) {
                    $mlResult = $parsed;
                }
            }
        } catch (\Exception $e) {
            Log::info('DiabetesPredictionTool Python execution fallback: ' . $e->getMessage());
        }

        if ($mlResult) {
            $category = $mlResult['category'];
            $score = $mlResult['score'] ?? 10;
            $recommendations = $mlResult['recommendations'] ?? [];
            $algorithm = 'XGBoost Machine Learning Model (CDC BRFSS Dataset)';
        } else {
            // Fallback Algoritma Klinis CDC / Kemenkes
            $score = 0;
            if ($genderLabel === 'Laki-laki') $score += 1;
            if ($age < 35) $score += 0;
            elseif ($age < 50) $score += 3;
            elseif ($age < 65) $score += 5;
            else $score += 7;

            if ($bmi >= 25.0) $score += 4;
            if ($bmi >= 30.0) $score += 2;

            if ($highBp === 1) $score += 5;
            if ($familyHistory === 1) $score += 5;
            if ($physActivity === 0) $score += 3;
            if ($highChol === 1) $score += 2;

            $score = min($score, 25);

            if ($score <= 6) {
                $category = 'Rendah';
                $recommendations = [
                    'Pertahankan pola makan bergizi seimbang dan batasi konsumsi gula berlebih.',
                    'Lakukan aktivitas fisik atau olahraga teratur minimal 30 menit setiap hari.',
                    'Jaga berat badan ideal dan pantau tekanan darah secara berkala.',
                    'Lakukan pemeriksaan gula darah rutin setidaknya 1 tahun sekali.'
                ];
            } elseif ($score <= 14) {
                $category = 'Sedang';
                $recommendations = [
                    'Lakukan pemeriksaan gula darah (GDP/HbA1c) dan tekanan darah secara berkala.',
                    'Batasi konsumsi makanan manis, minuman kemasan, dan karbohidrat sederhana.',
                    'Tingkatkan aktivitas fisik minimal 150 menit per minggu.',
                    'Konsultasikan hasil dengan tenaga kesehatan atau dokter umum.'
                ];
            } else {
                $category = 'Tinggi';
                $recommendations = [
                    'Sangat disarankan segera berkonsultasi dengan dokter spesialis penyakit dalam (Sp.PD).',
                    'Lakukan pemeriksaan darah lengkap di laboratorium (HbA1c, GDP, dan profil lipid).',
                    'Batasi secara ketat konsumsi gula tambahan dan makanan olahan tinggi kalori.',
                    'Rutin pantau kadar glukosa darah mandiri.'
                ];
            }
            $algorithm = 'Algoritma Skoring Klinis Validasi CDC BRFSS';
        }

        return [
            'success' => true,
            'tool' => 'DiabetesPredictionTool',
            'data' => [
                'gender' => $genderLabel,
                'age' => $age,
                'height' => $height,
                'weight' => $weight,
                'bmi' => $bmi,
                'bmi_category' => $bmiCat,
                'score' => $score,
                'max_score' => 25,
                'risk_category' => $category,
                'algorithm' => $algorithm,
                'recommendations' => $recommendations,
                'disclaimer' => 'Hasil evaluasi ini adalah taksiran probabilitas skrining awal berbasis Machine Learning dan BUKAN vonis diagnosis medis dokter. Diagnosis pasti hanya dapat ditegakkan melalui pemeriksaan laboratorium darah (HbA1c / GDP).'
            ]
        ];
    }

    private function getPythonBinary(): string
    {
        if ($envPath = env('PYTHON_PATH')) {
            if (file_exists($envPath)) {
                return $envPath;
            }
        }

        $candidates = [
            base_path('../.venv/Scripts/python.exe'),
            base_path('.venv/Scripts/python.exe'),
            'C:\\laragon\\bin\\python\\python-3.10\\python.exe',
            'python3',
            'python',
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        return 'python';
    }
}

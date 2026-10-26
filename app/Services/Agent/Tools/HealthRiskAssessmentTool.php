<?php

namespace App\Services\Agent\Tools;

class HealthRiskAssessmentTool
{
    /**
     * Mengevaluasi faktor risiko kesehatan secara komprehensif,
     * memisahkan status BMI dari komorbiditas kardiovaskular dan genetik.
     */
    public function execute(array $params): array
    {
        $age = isset($params['age']) ? (int)$params['age'] : null;
        $height = isset($params['height']) ? (float)$params['height'] : null;
        $weight = isset($params['weight']) ? (float)$params['weight'] : null;
        $bmi = isset($params['bmi']) ? (float)$params['bmi'] : null;
        $gender = $params['gender'] ?? 'Laki-laki';
        $highBp = !empty($params['high_bp']);
        $highChol = !empty($params['high_chol']);
        $physActivity = !empty($params['phys_activity']);
        $familyHistory = !empty($params['family_history']);
        $score = isset($params['score']) ? (int)$params['score'] : null;
        $category = $params['category'] ?? null;

        // Validasi kelengkapan data
        if ($age === null || ($bmi === null && ($height === null || $weight === null))) {
            return [
                'success' => false,
                'tool' => 'HealthRiskAssessmentTool',
                'error' => 'Data profil biometrik belum lengkap untuk melakukan penilaian risiko yang valid.',
                'status_assessment' => 'Belum Dapat Ditentukan (Data Tidak Lengkap)'
            ];
        }

        if ($bmi === null && $height > 0 && $weight > 0) {
            $bmi = round($weight / pow($height / 100, 2), 1);
        }

        // Klasifikasi BMI Standar Asia-Pasifik (WHO WPRO)
        if ($bmi < 18.5) {
            $bmiCategory = 'Berat Badan Kurang (Underweight)';
            $bmiRiskNote = 'Indeks massa tubuh rendah. Risiko resistensi insulin akibat lemak tubuh minimal, namun pastikan status nutrisi seimbang.';
        } elseif ($bmi < 23.0) {
            $bmiCategory = 'Berat Badan Normal / Ideal';
            $bmiRiskNote = 'Indeks massa tubuh berada dalam rentang ideal.';
        } elseif ($bmi < 25.0) {
            $bmiCategory = 'Kelebihan Berat Badan Ringan (Overweight)';
            $bmiRiskNote = 'Indeks massa tubuh mendekati batas ambang. Peningkatan adipositas mulai membebani sel beta pankreas.';
        } elseif ($bmi < 30.0) {
            $bmiCategory = 'Obesitas Tingkat 1';
            $bmiRiskNote = 'Kelebihan jaringan adiposa viseral yang memicu pelepasan asam lemak bebas dan resistensi insulin sistemik.';
        } else {
            $bmiCategory = 'Obesitas Tingkat 2';
            $bmiRiskNote = 'Kategori obesitas berat dengan beban metabolik tinggi pada organ hati dan pankreas.';
        }

        // Dekonstruksi Rinci Kontributor Risiko (Metode CDC BRFSS / Kemenkes Screening)
        $contributors = [];
        $totalCalculatedScore = 0;

        // 1. Faktor Jenis Kelamin
        if (strtolower($gender) === 'laki-laki' || strtolower($gender) === 'pria') {
            $contributors[] = [
                'factor' => 'Jenis Kelamin (Pria)',
                'points' => 1,
                'clinical_context' => 'Secara statistik epidemiologis memiliki kerentanan lemak viseral abdominal lebih dini dibandingkan wanita premenopause.'
            ];
            $totalCalculatedScore += 1;
        }

        // 2. Faktor Usia
        if ($age >= 65) {
            $agePoints = 7;
        } elseif ($age >= 50) {
            $agePoints = 5;
        } elseif ($age >= 35) {
            $agePoints = 3;
        } else {
            $agePoints = 0;
        }
        $contributors[] = [
            'factor' => "Kelompok Usia ({$age} Tahun)",
            'points' => $agePoints,
            'clinical_context' => $agePoints > 0 ? 'Penuaan seluler berkontribusi terhadap penurunan fungsi sekresi insulin secara bertahap.' : 'Usia muda (<35 thn) memberikan keunggulan elastisitas vaskular dan cadangan sel beta pankreas.'
        ];
        $totalCalculatedScore += $agePoints;

        // 3. Faktor BMI
        $bmiPoints = 0;
        if ($bmi >= 30.0) {
            $bmiPoints = 6;
        } elseif ($bmi >= 25.0) {
            $bmiPoints = 4;
        }
        if ($bmiPoints > 0) {
            $contributors[] = [
                'factor' => "Indeks Massa Tubuh (BMI: {$bmi})",
                'points' => $bmiPoints,
                'clinical_context' => 'Kelebihan berat badan memicu lipotoksisitas dan inflamasi kronis derajat rendah.'
            ];
            $totalCalculatedScore += $bmiPoints;
        } else {
            $contributors[] = [
                'factor' => "Indeks Massa Tubuh (BMI: {$bmi})",
                'points' => 0,
                'clinical_context' => 'BMI normal (tidak ada penambahan poin risiko dari faktor berat badan).'
            ];
        }

        // 4. Komorbiditas Hipertensi
        if ($highBp) {
            $contributors[] = [
                'factor' => 'Riwayat Tekanan Darah Tinggi (Hipertensi)',
                'points' => 5,
                'clinical_context' => 'Hipertensi adalah bagian utama dari sindrom metabolik, mempercepat disfungsi endotel pembuluh darah dan resistensi perifer.'
            ];
            $totalCalculatedScore += 5;
        }

        // 5. Riwayat Keluarga
        if ($familyHistory) {
            $contributors[] = [
                'factor' => 'Riwayat Diabetes dalam Keluarga (Genetik)',
                'points' => 5,
                'clinical_context' => 'Faktor hereditas derajat pertama meningkatkan kerentanan poligenik terhadap disfungsi sel beta pankreas hingga 2-4 kali lipat.'
            ];
            $totalCalculatedScore += 5;
        }

        // 6. Gaya Hidup Sedenter
        if (!$physActivity) {
            $contributors[] = [
                'factor' => 'Kurangnya Aktivitas Fisik Rutin',
                'points' => 3,
                'clinical_context' => 'Otot rangka yang pasif menurunkan translokasi transporter GLUT4, menghambat penyerapan glukosa non-insulin.'
            ];
            $totalCalculatedScore += 3;
        }

        // 7. Kolesterol
        if ($highChol) {
            $contributors[] = [
                'factor' => 'Riwayat Kolesterol Tinggi (Dislipidemia)',
                'points' => 2,
                'clinical_context' => 'Partikel LDL teroksidasi dan trigliserida tinggi mempercepat aterosklerosis pada pembuluh darah kapiler.'
            ];
            $totalCalculatedScore += 2;
        }

        $totalCalculatedScore = min(25, $totalCalculatedScore);

        // Rekonsiliasi dengan Kategori
        $finalCategory = $category ?? ($totalCalculatedScore <= 6 ? 'Rendah' : ($totalCalculatedScore <= 14 ? 'Sedang' : 'Tinggi'));

        // Sintesis Klarifikasi Klinis (Memisahkan BMI dari Risiko Keseluruhan)
        $bmiClarification = "Status fisik Anda adalah {$bmiCategory} (BMI: {$bmi} kg/m²). ";
        if ($finalCategory === 'Tinggi' && $bmi < 25.0) {
            $bmiClarification .= "Penting dicatat: Tingkat Risiko TINGGI pada hasil skrining Anda BUKAN disebabkan oleh kelebihan berat badan, melainkan akumulasi faktor komorbiditas lain (seperti hipertensi, riwayat genetik keluarga, atau kolesterol). Fenomena ini dikenal dalam istilah klinis sebagai Normal-Weight Metabolically Obese (NWMO).";
        } elseif ($finalCategory === 'Tinggi') {
            $bmiClarification .= "Tingkat risiko tinggi dipicu oleh kombinasi indeks massa tubuh di atas normal disertai faktor vaskular/genetik.";
        } else {
            $bmiClarification .= "Faktor risiko saat ini terkendali pada kategori {$finalCategory}.";
        }

        return [
            'success' => true,
            'tool' => 'HealthRiskAssessmentTool',
            'data' => [
                'bmi' => $bmi,
                'bmi_category' => $bmiCategory,
                'bmi_risk_note' => $bmiRiskNote,
                'screening_score' => $totalCalculatedScore,
                'risk_category' => $finalCategory,
                'is_diagnostic_claim' => false,
                'diagnostic_disclaimer' => 'Skrining ini merupakan alat penaksir risiko dini (risk assessment tool), BUKAN diagnosis medis diabetes. Kepastian klinis hanya dapat ditegakkan melalui pemeriksaan laboratorium darah (HbA1c/GDP).',
                'bmi_clarification' => $bmiClarification,
                'contributors' => $contributors
            ]
        ];
    }
}

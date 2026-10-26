<?php

namespace App\Services\Agent;

use App\Services\GeminiService;
use App\Services\Agent\Tools\CalorieCalculationTool;
use App\Services\Agent\Tools\HealthRiskAssessmentTool;
use App\Services\Agent\Tools\NutritionTool;
use App\Services\Agent\Tools\LaboratoryInformationTool;
use App\Services\Agent\Tools\ExerciseRecommendationTool;
use App\Services\Agent\Tools\MedicalKnowledgeRetrievalTool;
use App\Services\Agent\Tools\DiabetesPredictionTool;
use Illuminate\Support\Facades\Log;

class HealthAgentOrchestrator
{
    protected GeminiService $geminiService;
    protected CalorieCalculationTool $calorieTool;
    protected HealthRiskAssessmentTool $riskTool;
    protected NutritionTool $nutritionTool;
    protected LaboratoryInformationTool $labTool;
    protected ExerciseRecommendationTool $exerciseTool;
    protected MedicalKnowledgeRetrievalTool $medicalKnowledgeTool;
    protected DiabetesPredictionTool $predictionTool;

    public function __construct(
        GeminiService $geminiService,
        CalorieCalculationTool $calorieTool,
        HealthRiskAssessmentTool $riskTool,
        NutritionTool $nutritionTool,
        LaboratoryInformationTool $labTool,
        ExerciseRecommendationTool $exerciseTool,
        MedicalKnowledgeRetrievalTool $medicalKnowledgeTool,
        DiabetesPredictionTool $predictionTool
    ) {
        $this->geminiService = $geminiService;
        $this->calorieTool = $calorieTool;
        $this->riskTool = $riskTool;
        $this->nutritionTool = $nutritionTool;
        $this->labTool = $labTool;
        $this->exerciseTool = $exerciseTool;
        $this->medicalKnowledgeTool = $medicalKnowledgeTool;
        $this->predictionTool = $predictionTool;
    }

    /**
     * Siklus Eksekusi Terkendali Agent Orchestrator:
     * Pengguna mengirim pertanyaan
     * -> Sanitasi input & proteksi keamanan
     * -> Integrasi Gemini API (dengan Function Calling Tool Loop & Multi-Turn Context)
     * -> Fallback Autonomous Local Orchestrator jika API Key belum tersedia atau kuota habis
     */
    public function run(string $userMessage, array $assessment, array $history = []): array
    {
        $reasoningSteps = [];

        // 1. Sanitasi Input & Proteksi Prompt Injection
        $sanitizedMessage = $this->sanitizeInput($userMessage);
        $isInjectionAttempt = $this->detectPromptInjection($userMessage);
        if ($isInjectionAttempt) {
            $reasoningSteps[] = "⚠️ Peringatan Keamanan: Pola instruksi tidak tepercaya dinetralisir.";
        }

        // 2. Ekstraksi dan Validasi Profil Pasien
        $validatedProfile = $this->validatePatientProfile($assessment);

        // 3. Eksekusi Tool Dasar Kebutuhan Energi
        $calorieResult = $this->calorieTool->execute($validatedProfile);
        $biometrics = $calorieResult['data'];

        // 4. INTEGRASI GEMINI API (Jika API Key Dikonfigurasi)
        if ($this->geminiService->isConfigured()) {
            $reasoningSteps[] = "3. Menghubungi Google Gemini API (Model: " . $this->geminiService->getModel() . ") dengan Function Calling & Multi-turn Memory";

            $systemInstruction = $this->buildSystemInstruction($validatedProfile, $biometrics);

            // Callback dispatcher untuk Tool Calling Gemini
            $toolDispatcher = function (string $toolName, array $args) use ($validatedProfile, $biometrics) {
                switch ($toolName) {
                    case 'predict_diabetes_risk':
                        $merged = array_merge($validatedProfile, $args);
                        return $this->predictionTool->execute($merged);

                    case 'calculate_calories_and_sugar':
                        $merged = array_merge($validatedProfile, $args);
                        return $this->calorieTool->execute($merged);

                    case 'get_medical_knowledge':
                        return $this->medicalKnowledgeTool->execute($args);

                    case 'get_lab_guidelines':
                        return $this->labTool->execute($args);

                    case 'get_exercise_recommendation':
                        $merged = array_merge($validatedProfile, $args);
                        return $this->exerciseTool->execute($merged);

                    default:
                        return ['error' => "Unknown tool: {$toolName}"];
                }
            };

            $geminiResult = $this->geminiService->chatWithTools(
                $systemInstruction,
                $history,
                $sanitizedMessage,
                $toolDispatcher,
                $reasoningSteps
            );

            if (!empty($geminiResult['success'])) {
                $reasoningSteps[] = "4. Jawaban berhasil disintesis oleh " . $geminiResult['provider'];
                return [
                    'status' => 'success',
                    'reply' => $geminiResult['reply'],
                    'reasoning_steps' => $reasoningSteps,
                    'biometrics' => $biometrics,
                    'provider' => $geminiResult['provider']
                ];
            } else {
                $err = $geminiResult['error_message'] ?? 'Kendala komunikasi API';
                $reasoningSteps[] = "⚠️ Gemini API ({$err}). Menggunakan Autonomous Local Orchestrator.";
            }
        } else {
            $reasoningSteps[] = "ℹ️ GEMINI_API_KEY belum dikonfigurasi di .env. Mengaktifkan Autonomous Local Agent.";
        }

        // 5. AUTONOMOUS LOCAL ORCHESTRATOR (Fallback Cerdas Terfokus)
        $reasoningSteps[] = "3. Memproses maksud percakapan dan konteks riwayat";
        
        $localReply = $this->processLocalAutonomousResponse(
            $sanitizedMessage,
            $history,
            $validatedProfile,
            $biometrics,
            $reasoningSteps
        );

        return [
            'status' => 'success',
            'reply' => $localReply,
            'reasoning_steps' => $reasoningSteps,
            'biometrics' => $biometrics,
            'provider' => 'autonomous-local-orchestrator'
        ];
    }

    /**
     * Membangun System Instruction untuk Gemini API dengan aturan komunikasi yang terfokus
     */
    protected function buildSystemInstruction(array $profile, array $biometrics): string
    {
        return "Anda adalah 'AI Assistant' pada website 'Diabetes Uji Coba', asisten kesehatan berbasis evidence-based (PERKENI, ADA, WHO) dan Machine Learning XGBoost.
Pedoman Menjawab & Etika Komunikasi:
1. Koherensi & Relevansi Percakapan ('Nyambung'):
   - Selalu perhatikan percakapan sebelumnya. Jika pengguna bertanya hal lanjutan (seperti 'apa obatnya?', 'bagaimana cara minumnya?', 'apa efek sampingnya?', 'bagaimana cara mencegahnya?', 'apa kelebihannya?'), jawablah secara LANGSUNG menyambung konteks topik yang sedang dibicarakan tanpa mereset atau mengulang sapaan dari awal.
2. Sapaan Singkat & Ramah:
   - Jika pengguna hanya menyapa (seperti 'Halo', 'Hallo', 'Hai', 'Selamat Pagi'), jawablah singkat, ramah, dan terfokus: 'Halo juga! Ada yang bisa saya bantu hari ini?'
   - DILARANG KERAS langsung menampilkan data biometrik, target kalori, atau ringkasan profil pasien jika pengguna hanya menyapa atau mengobrol santai!
3. Identitas Asisten:
   - Jika ditanya siapa kamu, jawab: 'Saya AI Assistant pada website Diabetes Uji Coba. Saya dapat membantu menjawab pertanyaan dan menjelaskan informasi seputar kesehatan, teknologi, serta penggunaan website ini.'
4. Edukasi Obat & Pengobatan Diabetes:
   - Jika pengguna menanyakan obat, jelaskan golongan obat diabetes utama secara jelas dan terstruktur:
     • Metformin (Lini pertama oral: menurunkan glukosa hati & meningkatkan sensitivitas insulin; diminum bersama/segera setelah makan).
     • Sulfonilurea / Glimepiride (Pemacu sekresi insulin; diminum sebelum sarapan; waspadai risiko hipoglikemia).
     • SGLT-2 Inhibitor / Empagliflozin / Jardiance (Membuang gula via urine; proteksi kardiorenal).
     • DPP-4 Inhibitor / Sitagliptin / Januvia (Mempertahankan hormon inkretin; ramah lansia).
     • Terapi Insulin (Injeksi basal/prandial untuk defisiensi insulin berat atau kegagalan obat oral).
   - Selalu sertakan penegasan bahwa semua obat diabetes adalah obat keras dengan resep dan pengawasan dokter.
5. Definisi & Pencegahan Diabetes:
   - Jelaskan definisi diabetes, faktor risiko, gejala 3P (Poliuria, Polidipsia, Polifagia), dan 5 pilar pencegahan (diet seimbang, aktivitas 150 menit/minggu, berat badan ideal, hindari rokok/alkohol, pemeriksaan berkala).
6. Teknologi Machine Learning XGBoost:
   - Jelaskan bahwa website ini menggunakan model XGBoost berbasis dataset CDC BRFSS dengan 21 parameter kesehatan untuk evaluasi risiko dini yang cepat, objektif, dan akurat.
7. Format Jawaban:
   - Jawab secara jelas, padat, terstruktur, gunakan poin-poin agar mudah dibaca oleh masyarakat awam, dan gunakan Bahasa Indonesia yang santun.
8. Batasan Medis:
   - Tegaskan bahwa skrining AI pada website ini adalah estimasi risiko dini dan bukan diagnosis pasti dokter.";
    }

    /**
     * Pemrosesan Percakapan Cerdas pada Mode Lokal (Terfokus & Selalu Nyambung)
     */
    protected function processLocalAutonomousResponse(
        string $query,
        array $history,
        array $profile,
        array $biometrics,
        array &$reasoningSteps
    ): string {
        $q = strtolower(trim($query));
        $cleanQ = preg_replace('/[^\p{L}\p{N}\s]/u', '', $q);
        $words = preg_split('/\s+/', trim($cleanQ));
        $firstWord = $words[0] ?? '';
        
        // Cek riwayat untuk konteks multi-turn percakapan sebelumnya
        $lastModelTurn = '';
        $lastUserTurn = '';
        if (!empty($history)) {
            $reversed = array_reverse($history);
            foreach ($reversed as $turn) {
                $sender = strtolower(trim($turn['sender'] ?? ''));
                if (!$lastModelTurn && in_array($sender, ['model', 'assistant', 'bot'])) {
                    $lastModelTurn = strtolower($turn['text'] ?? '');
                }
                if (!$lastUserTurn && $sender === 'user') {
                    $lastUserTurn = strtolower($turn['text'] ?? '');
                }
                if ($lastModelTurn && $lastUserTurn) break;
            }
        }

        // 1. Sapaan Ramah (Halo, Hallo, Hai, Hello, Pagi, Malam, dsb)
        $greetingPatterns = [
            'halo', 'hallo', 'hello', 'hai', 'haii', 'hi', 'hey', 'helo',
            'assalamualaikum', 'assalamu alaikum', 'selamat pagi', 'selamat siang',
            'selamat sore', 'selamat malam', 'pagi', 'siang', 'sore', 'malam', 'p', 'ping', 'tes'
        ];

        $isGreeting = in_array($cleanQ, $greetingPatterns) 
            || (count($words) <= 3 && in_array($firstWord, ['halo', 'hallo', 'hello', 'hai', 'hi', 'hey']));

        if ($isGreeting) {
            $reasoningSteps[] = "-> Sapaan ramah terdeteksi.";
            return "Halo juga! Ada yang bisa saya bantu hari ini?";
        }

        // 2. Pertanyaan Identitas (Siapa kamu?)
        if (str_contains($cleanQ, 'siapa kamu') 
            || str_contains($cleanQ, 'siapa anda') 
            || str_contains($cleanQ, 'kamu siapa') 
            || str_contains($cleanQ, 'anda siapa')
            || str_contains($cleanQ, 'siapakah kamu')
            || str_contains($cleanQ, 'siapakah anda')
            || str_contains($cleanQ, 'tentang kamu')) {
            $reasoningSteps[] = "-> Pertanyaan identitas asisten terdeteksi.";
            return "Saya AI Assistant pada website Diabetes Uji Coba. Saya dapat membantu menjawab pertanyaan dan menjelaskan informasi seputar kesehatan, teknologi, serta penggunaan website ini.";
        }

        // 3. Pertanyaan Lanjutan: Aturan Minum & Efek Samping Obat (Nyambung dengan topik obat)
        $isDosageOrSideEffect = str_contains($cleanQ, 'cara minum') 
            || str_contains($cleanQ, 'aturan minum') 
            || str_contains($cleanQ, 'kapan minum') 
            || str_contains($cleanQ, 'efek samping') 
            || str_contains($cleanQ, 'bahaya obat')
            || ((str_contains($cleanQ, 'bagaimana') || str_contains($cleanQ, 'kapan') || str_contains($cleanQ, 'efeknya')) 
                && (str_contains($lastUserTurn, 'obat') || str_contains($lastModelTurn, 'obat') || str_contains($lastUserTurn, 'metformin') || str_contains($lastModelTurn, 'metformin')));

        if ($isDosageOrSideEffect) {
            $reasoningSteps[] = "-> Pertanyaan lanjutan aturan pakai & efek samping obat terdeteksi.";
            return "Melanjutkan pembahasan mengenai obat diabetes, berikut aturan waktu minum dan penanganan efek samping yang umum:\n\n"
                 . "1. **Aturan Waktu Minum:**\n"
                 . "   • **Metformin:** Wajib diminum **bersama suapan makan** atau **segera sesudah makan** (mengurangi rasa begah, mual, atau diare adaptasi).\n"
                 . "   • **Sulfonilurea (Glimepiride):** Diminum **15–30 menit sebelum sarapan** dengan air putih.\n"
                 . "   • **Acarbose (Glucobay):** Diminum **bersama suapan pertama** makanan utama.\n"
                 . "   • **SGLT-2 Inhibitor (Empagliflozin/Jardiance):** Diminum **1x sehari di pagi hari**, disertai asupan air putih minimal 2 liter per hari.\n\n"
                 . "2. **Efek Samping Umum & Kewaspadaan:**\n"
                 . "   • **Mual & Begah (Metformin):** Biasanya bersifat sementara selama 1–2 minggu awal.\n"
                 . "   • **Hipoglikemia (Gemetar/Keringat Dingin):** Terjadi jika dosis berlebih atau terlambat makan pada terapi sulfonilurea/insulin. Segera konsumsi 1 sendok makan gula atau teh manis bila gula darah turun di bawah 70 mg/dL.\n\n"
                 . "> ⚠️ **Peringatan Medis:** Jangan pernah mengubah dosis atau menghentikan obat tanpa persetujuan dokter yang merawat Anda.";
        }

        // 4. Pertanyaan Obat & Terapi Farmakologi Diabetes
        $isMedicineQuery = str_contains($cleanQ, 'obat') 
            || str_contains($cleanQ, 'pengobatan') 
            || str_contains($cleanQ, 'resep') 
            || str_contains($cleanQ, 'farmasi') 
            || str_contains($cleanQ, 'medikasi')
            || str_contains($cleanQ, 'metformin')
            || str_contains($cleanQ, 'insulin')
            || str_contains($cleanQ, 'glimepirid');

        if ($isMedicineQuery) {
            $reasoningSteps[] = "-> Pertanyaan obat diabetes terdeteksi.";
            return "Pengobatan diabetes melitus tipe 2 disesuaikan dengan kondisi klinis dan kadar gula darah pasien. Berdasarkan standar konsensus PERKENI dan ADA, golongan obat diabetes utama meliputi:\n\n"
                 . "1. **Metformin (Lini Pertama Oral):**\n"
                 . "   • **Fungsi:** Menurunkan produksi glukosa oleh hati dan meningkatkan sensitivitas reseptor insulin di sel tubuh.\n"
                 . "   • **Aturan Minum:** Diminum bersama atau segera sesudah makan. Aman terhadap berat badan dan tidak menyebabkan hipoglikemia.\n\n"
                 . "2. **Sulfonilurea (Glimepiride, Gliclazide, Glibenclamide):**\n"
                 . "   • **Fungsi:** Merangsang sel beta pankreas memproduksi lebih banyak insulin.\n"
                 . "   • **Aturan Minum:** Diminum 15–30 menit sebelum sarapan. Wajib makan tepat waktu untuk mencegah hipoglikemia.\n\n"
                 . "3. **SGLT-2 Inhibitor (Empagliflozin / Jardiance, Dapagliflozin / Forxiga):**\n"
                 . "   • **Fungsi:** Membuang kelebihan gula darah melalui urine serta melindungi fungsi jantung dan ginjal.\n\n"
                 . "4. **DPP-4 Inhibitor (Sitagliptin / Januvia, Linagliptin / Trajenta):**\n"
                 . "   • **Fungsi:** Mempertahankan hormon alami inkretin agar pelepasan insulin optimal saat gula naik sehabis makan.\n\n"
                 . "5. **Terapi Insulin (Injeksi):**\n"
                 . "   • Diberikan jika obat oral belum mencapai target HbA1c, kadar gula darah sangat tinggi (> 300 mg/dL atau HbA1c > 9-10%), atau pada kondisi darurat.\n\n"
                 . "> ⚠️ **Penting:** Semua obat diabetes adalah **obat keras dengan resep dokter**. Pemilihan jenis obat dan dosis wajib disesuaikan dengan kondisi medis, fungsi ginjal, dan hasil tes laboratorium oleh dokter yang merawat.";
        }

        // 5. Pertanyaan Gejala Diabetes (Trias 3P dan Gejala Klasik)
        if (str_contains($cleanQ, 'gejala') || str_contains($cleanQ, 'ciri') || str_contains($cleanQ, 'tanda') || str_contains($cleanQ, '3p')) {
            $reasoningSteps[] = "-> Pertanyaan gejala diabetes terdeteksi.";
            return "Gejala diabetes melitus umumnya berkembang secara bertahap dan terbagi menjadi gejala klasik serta gejala lanjutan:\n\n"
                 . "1. **Gejala Klasik (Trias 3P):**\n"
                 . "   • **Poliuria (Sering Kencing):** Terutama terbangun di malam hari karena ginjal membuang kelebihan glukosa.\n"
                 . "   • **Polidipsia (Sering Haus):** Rasa haus terus-menerus karena cairan tubuh terkuras lewat urine.\n"
                 . "   • **Polifagia (Cepat Lapar):** Sel tubuh kekurangan energi karena glukosa tidak bisa masuk tanpa insulin yang cukup.\n\n"
                 . "2. **Gejala Penyerta Lainnya:**\n"
                 . "   • Penurunan berat badan secara drastis tanpa sebab yang jelas.\n"
                 . "   • Tubuh cepat lelah, lesu, dan mudah mengantuk.\n"
                 . "   • Penglihatan kabur atau buram.\n"
                 . "   • Luka lecet yang lambat sembuh atau sering terinfeksi.\n"
                 . "   • Rasa kesemutan atau kebas pada ujung jari kaki dan tangan.\n\n"
                 . "Bila Anda mengalami gejala-gejala tersebut, lakukan skrining mandiri pada menu website ini atau periksakan kadar gula darah di fasilitas kesehatan.";
        }

        // 6. Pertanyaan Komplikasi & Bahaya Diabetes
        if (str_contains($cleanQ, 'komplikasi') || str_contains($cleanQ, 'bahaya') || str_contains($cleanQ, 'akibat diabetes')) {
            $reasoningSteps[] = "-> Pertanyaan komplikasi diabetes terdeteksi.";
            return "Kadar gula darah yang tinggi dan tidak terkontrol dalam jangka panjang dapat merusak pembuluh darah dan organ vital:\n\n"
                 . "1. **Komplikasi Jantung & Pembuluh Darah:** Meningkatkan risiko penyakit jantung koroner, serangan jantung, dan stroke 2–4 kali lipat.\n"
                 . "2. **Nefropati Diabetik (Ginjal):** Kerusakan saringan ginjal yang dapat memicu gagal ginjal kronis (cuci darah).\n"
                 . "3. **Retinopati Diabetik (Mata):** Kerusakan pembuluh darah retina yang dapat menyebabkan penurunan penglihatan hingga kebutaan.\n"
                 . "4. **Neuropati Diabetik (Saraf):** Kerusakan saraf tepi yang menyebabkan rasa mati rasa, baal, atau kesemutan di kaki.\n"
                 . "5. **Kaki Diabetik & Luka Ulkus:** Hilangnya sensasi rasa sakit membuat luka kecil di kaki tidak disadari, mudah membusuk, dan berisiko amputasi jika terlambat dirawat.\n\n"
                 . "Seluruh komplikasi ini dapat dicegah dengan menjaga HbA1c di bawah 7%, diet sehat, dan pemeriksaan berkala.";
        }

        // 7. Pertanyaan Nilai Gula Darah Normal & Hasil Tes Lab
        if (str_contains($cleanQ, 'normal') || str_contains($cleanQ, 'hba1c') || str_contains($cleanQ, 'gdp') || str_contains($cleanQ, 'gds') || str_contains($cleanQ, 'kadar gula') || str_contains($cleanQ, 'tes lab')) {
            $reasoningSteps[] = "-> Pertanyaan nilai rujukan gula darah terdeteksi.";
            return "Berdasarkan standar rujukan konsensus **PERKENI 2021** dan **ADA**, berikut rentang nilai normal gula darah:\n\n"
                 . "| Parameter Pemeriksaan | Normal | Prediabetes | Diabetes Melitus |\n"
                 . "| :--- | :--- | :--- | :--- |\n"
                 . "| **HbA1c** (Rata-rata 3 Bulan) | **< 5.7%** | **5.7% – 6.4%** | **≥ 6.5%** |\n"
                 . "| **Gula Darah Puasa (GDP)** | **< 100 mg/dL** | **100 – 125 mg/dL** | **≥ 126 mg/dL** |\n"
                 . "| **Gula Darah Sewaktu (GDS)** | **< 140 mg/dL** | **140 – 199 mg/dL** | **≥ 200 mg/dL** |\n\n"
                 . "• **Syarat Tes GDP:** Wajib berpuasa makan dan minuman manis selama 8–10 jam sebelum pengambilan darah (air putih diperbolehkan).\n"
                 . "• **Target Diabetisi:** Bagi pasien diabetes yang sudah menjalani terapi, target HbA1c umumnya dijaga **< 7.0%**.";
        }

        // 8. Pertanyaan Makanan, Diet, dan Pantangan
        if (str_contains($cleanQ, 'pantangan') || str_contains($cleanQ, 'dilarang') || str_contains($cleanQ, 'makanan') || str_contains($cleanQ, 'minuman') || str_contains($cleanQ, 'diet') || str_contains($cleanQ, 'pola makan')) {
            $reasoningSteps[] = "-> Pertanyaan pola makan dan pantangan terdeteksi.";
            return "Pola makan memegang peran kunci dalam mengontrol diabetes. Berikut panduan makanan dan pantangannya:\n\n"
                 . "1. **Pantangan yang Wajib Dibatasi / Dihindari:**\n"
                 . "   • Minuman manis kemasan, boba, sirup, teh manis pekat, dan minuman bersoda.\n"
                 . "   • Karbohidrat sederhana berindeks glikemik tinggi (nasi putih porsi besar, roti tawar putih, mie instan, gorengan tepung).\n"
                 . "   • Makanan tinggi lemak jenuh (jeroan, santan kental berlebih, daging olahan).\n"
                 . "   • Batasi asupan gula pasir maksimal 4 sendok makan (50 gram) per hari.\n\n"
                 . "2. **Makanan yang Dianjurkan (Konsep Piring T):**\n"
                 . "   • **1/2 Piring:** Sayuran kaya serat (bayam, brokoli, buncis, kangkung, sawi).\n"
                 . "   • **1/4 Piring:** Karbohidrat kompleks (beras merah, oatmeal, ubi rebus, jagung).\n"
                 . "   • **1/4 Piring:** Protein rendah lemak (ikan kembung/salmon, dada ayam, telur, tempe, tahu).\n\n"
                 . "3. **Buah Segar Pilihan:** Apel, pir, pepaya, dan alpukat (hindari buah kaleng berpengawet atau jus buah yang diberi gula).";
        }

        // 9. Pertanyaan Apakah Diabetes Bisa Sembuh Total (Remisi)
        if (str_contains($cleanQ, 'sembuh') || str_contains($cleanQ, 'remisi') || str_contains($cleanQ, 'hilang total')) {
            $reasoningSteps[] = "-> Pertanyaan kesembuhan dan remisi diabetes terdeteksi.";
            return "Secara medis, **diabetes melitus tipe 2 belum dapat disembuhkan secara permanen**, karena faktor genetik dan kecenderungan resistensi insulin tetap ada di dalam tubuh.\n\n"
                 . "Namun, penderita diabetes dapat mencapai kondisi **REMISI DIABETES**:\n\n"
                 . "• **Arti Remisi:** Kondisi di mana kadar gula darah dan HbA1c kembali normal stabil (HbA1c < 6.5%) selama minimal 3 bulan **tanpa menggunakan obat penurun gula darah**.\n"
                 . "• **Langkah Mencapai Remisi:**\n"
                 . "  1. Menurunkan kelebihan berat badan (turun 5–10% dari berat badan awal sangat efektif menurunkan resistensi insulin).\n"
                 . "  2. Disiplin diet rendah kalori dan membatasi makanan tinggi gula.\n"
                 . "  3. Rutin berolahraga aerobik minimal 150 menit per minggu.\n\n"
                 . "Dengan disiplin gaya hidup sehat, diabetisi dapat hidup normal, bugar, dan terhindar dari komplikasi.";
        }

        // 10. Pertanyaan Olahraga & Latihan Fisik
        if (str_contains($cleanQ, 'olahraga') || str_contains($cleanQ, 'senam') || str_contains($cleanQ, 'latihan fisik')) {
            $reasoningSteps[] = "-> Pertanyaan rekomendasi olahraga terdeteksi.";
            return "Aktivitas fisik sangat efektif merangsang otot menyerap glukosa darah. Rekomendasi olahraga yang dianjurkan:\n\n"
                 . "1. **Olahraga Kardio (Aerobik):**\n"
                 . "   • Pilihan: Jalan cepat, bersepeda santai, berenang, atau senam diabetes.\n"
                 . "   • Durasi: Minimal **30 menit sehari**, **3–5 hari per minggu** (total minimal 150 menit per minggu).\n\n"
                 . "2. **Latihan Kekuatan Otot:** Latihan beban ringan atau push-up dinding 2x seminggu untuk meningkatkan massa otot penyerap gula.\n\n"
                 . "3. **Pedoman Keamanan:**\n"
                 . "   • Gunakan sepatu yang pas dan kaos kaki bersih untuk mencegah lecet di kaki.\n"
                 . "   • Jangan berolahraga jika gula darah < 100 mg/dL (siapkan permen/air gula untuk antisipasi) atau jika > 250 mg/dL.\n"
                 . "   • Cukupi asupan air putih sebelum, saat, dan sesudah latihan.";
        }

        // 11. Pertanyaan Lanjutan: "Bagaimana cara mencegahnya?"
        $isPreventionQuery = str_contains($cleanQ, 'mencegah') 
            || str_contains($cleanQ, 'pencegahan') 
            || str_contains($cleanQ, 'cara cegah')
            || (str_contains($cleanQ, 'bagaimana') && (str_contains($cleanQ, 'cara') || str_contains($cleanQ, 'caranya')));

        if ($isPreventionQuery && (str_contains($cleanQ, 'diabetes') || str_contains($lastUserTurn, 'diabetes') || str_contains($lastModelTurn, 'diabetes') || $isPreventionQuery)) {
            $reasoningSteps[] = "-> Pertanyaan lanjutan pencegahan diabetes terdeteksi.";
            return "Melanjutkan pembahasan sebelumnya mengenai diabetes, langkah-langkah pencegahan diabetes yang dapat diterapkan secara edukatif meliputi:\n\n"
                 . "1. **Pola Makan Sehat:** Batasi konsumsi gula berlebih, kurangi makanan dan minuman manis, serta perbanyak konsumsi serat dari sayur dan biji-bijian.\n"
                 . "2. **Aktivitas Fisik Teratur:** Lakukan olahraga intensitas sedang minimal 30 menit sehari (150 menit per minggu), seperti berjalan cepat atau bersepeda.\n"
                 . "3. **Menjaga Berat Badan Ideal:** Menurunkan kelebihan berat badan terbukti secara klinis efektif menurunkan resistensi insulin.\n"
                 . "4. **Hindari Merokok dan Alkohol:** Menjaga kesehatan pembuluh darah dan sensitivitas insulin.\n"
                 . "5. **Pemeriksaan Berkala:** Lakukan skrining risiko mandiri atau tes gula darah puasa (GDP) dan HbA1c secara rutin di fasilitas kesehatan.";
        }

        // 12. Pertanyaan Lanjutan: "Apa kelebihannya?" (setelah topik XGBoost atau teknologi)
        if (str_contains($cleanQ, 'kelebihan') || str_contains($cleanQ, 'keunggulan')) {
            $reasoningSteps[] = "-> Pertanyaan keunggulan teknologi XGBoost terdeteksi.";
            return "Melanjutkan pembahasan tentang XGBoost sebelumnya, keunggulan utama algoritma XGBoost meliputi:\n\n"
                 . "1. **Kecepatan Inferensi Tinggi:** Evaluasi data pengguna selesai dalam hitungan detik di server.\n"
                 . "2. **Akurasi & Generalisasi Optimal:** Membangun pohon keputusan bertingkat (*boosting*) yang terus memperbaiki kesalahan iterasi sebelumnya.\n"
                 . "3. **Pencegahan Overfitting:** Dilengkapi regularisasi internal (L1 dan L2) sehingga handal terhadap data pengguna baru.\n"
                 . "4. **Tervalidasi secara Klinis:** Teruji pada dataset besar CDC BRFSS untuk klasifikasi risiko diabetes.";
        }

        // 13. Pertanyaan Teknologi: "Apa itu XGBoost?"
        if (str_contains($cleanQ, 'xgboost') || (str_contains($cleanQ, 'machine learning') && (str_contains($cleanQ, 'apa') || str_contains($cleanQ, 'model')))) {
            $reasoningSteps[] = "-> Pertanyaan teknologi model Machine Learning XGBoost terdeteksi.";
            return "XGBoost (Extreme Gradient Boosting) adalah algoritma machine learning mutakhir yang digunakan pada website Diabetes Uji Coba untuk mengevaluasi tingkat risiko diabetes berdasarkan data perilaku dan klinis pengguna. Model ini dilatih menggunakan dataset survei kesehatan CDC BRFSS dengan 21 parameter kesehatan.";
        }

        // 14. Pertanyaan Definisi Medis: "Apa itu diabetes?"
        if (str_contains($cleanQ, 'apa itu diabetes') 
            || str_contains($cleanQ, 'definisi diabetes') 
            || str_contains($cleanQ, 'arti diabetes') 
            || str_contains($cleanQ, 'apakah diabetes itu') 
            || $cleanQ === 'diabetes') {
            $reasoningSteps[] = "-> Pertanyaan definisi diabetes terdeteksi.";
            return "Diabetes adalah kondisi ketika kadar gula darah terlalu tinggi akibat tubuh tidak memproduksi cukup insulin atau tidak dapat menggunakan insulin secara efektif. Saya dapat membantu menjelaskan faktor risiko, gejala umum, dan cara pencegahannya.";
        }

        // 15. Permintaan Prediksi Risiko / Penilaian Biometrik
        if (str_contains($cleanQ, 'prediksi') || str_contains($cleanQ, 'cek risiko') || str_contains($cleanQ, 'hitung risiko') || str_contains($cleanQ, 'apakah saya berisiko')) {
            $reasoningSteps[] = "-> Mengeksekusi DiabetesPredictionTool untuk penilaian risiko.";
            $predResult = $this->predictionTool->execute($profile);
            if (!empty($predResult['success'])) {
                $d = $predResult['data'];
                $reply = "### 📊 Hasil Evaluasi Prediksi Risiko Diabetes\n\n"
                       . "Berdasarkan data profil:\n"
                       . "* **Profil:** {$d['gender']}, Usia {$d['age']} tahun\n"
                       . "* **BMI:** **{$d['bmi']} kg/m²** ({$d['bmi_category']})\n"
                       . "* **Skor Skrining:** **{$d['score']} / {$d['max_score']} poin**\n"
                       . "* **Tingkat Risiko:** <strong class=\"text-emerald-700 uppercase\">{$d['risk_category']}</strong>\n"
                       . "* **Algoritma:** {$d['algorithm']}\n\n"
                       . "**Rekomendasi Tindakan:**\n";
                foreach ($d['recommendations'] as $rec) {
                    $reply .= "* {$rec}\n";
                }
                $reply .= "\n> ⚠️ *{$d['disclaimer']}*";
                return $reply;
            }
        }

        // 16. Permintaan Kalori & Gula
        if (str_contains($cleanQ, 'kalori') || str_contains($cleanQ, 'target kalori') || str_contains($cleanQ, 'batas gula')) {
            $reasoningSteps[] = "-> Menjalankan CalorieCalculationTool.";
            return "Berdasarkan profil Anda ({$profile['gender']}, {$profile['age']} tahun, TB {$profile['height']} cm, BB {$profile['weight']} kg):\n\n"
                 . "* **Kebutuhan Energi Harian (BMR/Target):** Sekitar **{$biometrics['target_calories']} kcal/hari**.\n"
                 . "* **Batas Maksimal Konsumsi Gula:** Maksimal **{$biometrics['sugar_limit_grams']} gram/hari** (sekitar 4 sendok makan).\n\n"
                 . "Menjaga asupan kalori dan gula dalam batas ini sangat efektif untuk menjaga kestabilan glukosa darah Anda.";
        }

        // 17. Permintaan Penggunaan Website / Cara Cek Risiko
        if (str_contains($cleanQ, 'cara cek') || str_contains($cleanQ, 'cara kuesioner') || str_contains($cleanQ, 'cara pakai website') || str_contains($cleanQ, 'fitur website')) {
            return "Untuk melakukan cek risiko diabetes mandiri pada website ini:\n"
                 . "1. Buka menu **Cek Risiko (AI)** pada bilah navigasi atau tombol di beranda.\n"
                 . "2. Jawab pertanyaan kuesioner mengenai usia, tinggi & berat badan, serta riwayat kesehatan.\n"
                 . "3. Sistem akan memproses jawaban Anda menggunakan model Machine Learning XGBoost dan menampilkan tingkat risiko (Rendah, Sedang, atau Tinggi) beserta saran tindakan.";
        }

        // 18. Kueri Klinis Tertarget Lainnya (Lab, Obat, Olahraga, Menu Diet)
        $toolsToExecute = $this->planTools($query);
        $executedToolOutputs = [];

        foreach ($toolsToExecute as $toolName) {
            $toolOutput = $this->dispatchTool($toolName, $query, $profile, $biometrics);
            if (!empty($toolOutput['success'])) {
                $executedToolOutputs[$toolName] = $toolOutput['data'];
            }
        }

        if (!empty($executedToolOutputs)) {
            return $this->synthesizeResponse($query, $executedToolOutputs, $profile, $biometrics);
        }

        // 19. Jawaban Edukasi Terstruktur & Relevan
        return "Saya adalah AI Assistant pada website **Diabetes Uji Coba**.\n\n"
             . "Saya dapat membantu menjawab berbagai pertanyaan seputar:\n"
             . "1. **Pengobatan & Obat Diabetes:** Golongan Metformin, Sulfonilurea, SGLT-2i, dan Terapi Insulin.\n"
             . "2. **Edukasi Diabetes:** Gejala 3P, faktor pemicu, komplikasi, dan pencegahan.\n"
             . "3. **Nilai Rujukan Laboratorium:** Standar normal HbA1c, Gula Darah Puasa (GDP), dan GDS.\n"
             . "4. **Model Machine Learning XGBoost:** Cara kerja prediksi risiko berbasis dataset CDC BRFSS.\n"
             . "5. **Gaya Hidup Sehat:** Menu makan Piring T, kebutuhan kalori harian, batas gula, dan olahraga.\n\n"
             . "Silakan tanyakan topik kesehatan apa pun yang ingin Anda ketahui!";
    }

    /**
     * Sanitasi input pengguna untuk keamanan.
     */
    protected function sanitizeInput(string $input): string
    {
        $cleaned = strip_tags($input);
        return trim(substr($cleaned, 0, 1000));
    }

    /**
     * Deteksi pola Prompt Injection
     */
    protected function detectPromptInjection(string $input): bool
    {
        $patterns = [
            '/ignore\s+(all\s+)?(previous|prior)\s+instructions/i',
            '/system\s+prompt/i',
            '/you\s+are\s+now\s+/i',
            '/bypass\s+filter/i',
            '/dan\s+mode/i',
            '/disregard\s+all/i',
            '/developer\s+mode/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validasi data profil pasien
     */
    protected function validatePatientProfile(array $raw): array
    {
        $gender = strtolower(trim($raw['gender'] ?? 'laki-laki'));
        $gender = in_array($gender, ['wanita', 'perempuan', 'female']) ? 'Perempuan' : 'Laki-laki';
        $age = max(18, min(100, (int)($raw['age'] ?? 30)));
        $height = max(100, min(220, (float)($raw['height'] ?? 170)));
        $weight = max(30, min(200, (float)($raw['weight'] ?? 65)));
        
        $bmi = (float)($raw['bmi'] ?? round($weight / pow($height / 100, 2), 1));
        $category = in_array($raw['category'] ?? '', ['Rendah', 'Sedang', 'Tinggi']) ? $raw['category'] : 'Sedang';

        return [
            'gender' => $gender,
            'age' => $age,
            'height' => $height,
            'weight' => $weight,
            'bmi' => $bmi,
            'category' => $category,
            'score' => (int)($raw['score'] ?? 10),
            'high_bp' => !empty($raw['high_bp']),
            'high_chol' => !empty($raw['high_chol']),
            'phys_activity' => !empty($raw['phys_activity']),
            'family_history' => !empty($raw['family_history']),
        ];
    }

    /**
     * Menentukan Tools mana yang diperlukan berdasarkan kata kunci kueri
     */
    protected function planTools(string $query): array
    {
        $q = strtolower($query);
        $tools = [];

        // Kebutuhan Analisis Risiko & BMI
        if (str_contains($q, 'skor') || str_contains($q, 'kenapa risiko') || str_contains($q, 'mengapa risiko')) {
            $tools[] = 'HealthRiskAssessmentTool';
        }

        // Kebutuhan Menu Makanan & Diet
        if (str_contains($q, 'menu') || str_contains($q, 'diet') || str_contains($q, 'sarapan') || str_contains($q, 'makan siang') || str_contains($q, 'makan malam') || str_contains($q, 'camilan')) {
            $tools[] = 'NutritionTool';
        }

        // Kebutuhan Pemeriksaan Lab & Hasil Tes
        if (str_contains($q, 'hba1c') || str_contains($q, 'gdp') || str_contains($q, 'gds') || str_contains($q, 'tes darah') || str_contains($q, 'angka lab')) {
            $tools[] = 'LaboratoryInformationTool';
        }

        // Kebutuhan Olahraga & Latihan Fisik
        if (str_contains($q, 'olahraga') || str_contains($q, 'latihan fisik') || str_contains($q, 'jalan santai') || str_contains($q, 'senam')) {
            $tools[] = 'ExerciseRecommendationTool';
        }

        // Kebutuhan Pengetahuan Klinis Spesifik
        $medicalKeywords = [
            'obat', 'pengobatan', 'resep', 'medikasi', 'terapi', 'aturan minum', 'efek samping',
            'metformin', 'insulin', 'glimepirid', 'glimepiride', 'amaryl', 'glibenklamid', 'diamicron',
            'jardiance', 'empagliflozin', 'forxiga', 'dapagliflozin', 'januvia', 'sitagliptin', 'galvus', 'vildagliptin',
            'trajenta', 'linagliptin', 'ozempic', 'semaglutide', 'victoza', 'liraglutide', 'rybelsus', 'acarbose', 'glucobay',
            'pioglitazone', 'actos', 'statin', 'atorvastatin', 'simvastatin', 'candesartan', 'captopril', 'herbal', 'jamu',
            'pare', 'kayu manis', 'madu', 'stevia', 'pemanis', 'durian',
            'kesemutan', 'kebas', '3p', 'poliuria', 'polidipsia', 'polifagia', 'gejala', 'ciri', 'tanda',
            'hipoglikemia', 'keringat dingin', 'aturan 15', 'sembuh', 'remisi', 'komplikasi', 'bahaya',
            'ulkus', 'luka', 'amputasi', 'neuropati', 'retinopati', 'nefropati',
            'ramadan', 'sahur', 'berbuka', 'tipe 1', 'tipe 2', 'gestasional', 'pantangan'
        ];

        foreach ($medicalKeywords as $kw) {
            if (str_contains($q, $kw)) {
                $tools[] = 'MedicalKnowledgeRetrievalTool';
                break;
            }
        }

        return array_unique($tools);
    }

    /**
     * Mengeksekusi Tool spesifik dengan parameter tervalidasi
     */
    protected function dispatchTool(string $toolName, string $query, array $profile, array $biometrics): array
    {
        switch ($toolName) {
            case 'HealthRiskAssessmentTool':
                return $this->riskTool->execute($profile);
            case 'NutritionTool':
                return $this->nutritionTool->execute([
                    'category' => $profile['category'],
                    'target_calories' => $biometrics['target_calories'],
                    'sugar_limit_grams' => $biometrics['sugar_limit_grams'],
                ]);
            case 'LaboratoryInformationTool':
                return $this->labTool->execute([
                    'lab_values' => $this->extractLabMarkersFromQuery($query)
                ]);
            case 'ExerciseRecommendationTool':
                return $this->exerciseTool->execute([
                    'age' => $profile['age'],
                    'bmi' => $profile['bmi'],
                    'high_bp' => $profile['high_bp'],
                    'phys_activity' => $profile['phys_activity'],
                ]);
            case 'MedicalKnowledgeRetrievalTool':
                return $this->medicalKnowledgeTool->execute(['query' => $query]);
            case 'DiabetesPredictionTool':
                return $this->predictionTool->execute($profile);
            default:
                return ['success' => false, 'error' => "Unknown tool: {$toolName}"];
        }
    }

    /**
     * Ekstraksi angka lab dari pertanyaan pengguna (jika disebutkan secara eksplisit)
     */
    protected function extractLabMarkersFromQuery(string $query): array
    {
        $values = [];
        if (preg_match('/hba1c\s*[:=]?\s*([0-9]+[.,]?[0-9]*)/i', $query, $m)) {
            $values['hba1c'] = (float)str_replace(',', '.', $m[1]);
        }
        if (preg_match('/(gdp|puasa)\s*[:=]?\s*([0-9]+)/i', $query, $m)) {
            $values['gdp'] = (int)$m[2];
        }
        if (preg_match('/(gds|sewaktu)\s*[:=]?\s*([0-9]+)/i', $query, $m)) {
            $values['gds'] = (int)$m[2];
        }
        if (preg_match('/kolesterol\s*[:=]?\s*([0-9]+)/i', $query, $m)) {
            $values['kolesterol'] = (int)$m[1];
        }
        return $values;
    }

    /**
     * Menyintesis hasil tools menjadi respons terstruktur
     */
    protected function synthesizeResponse(string $query, array $toolOutputs, array $profile, array $biometrics): string
    {
        $sections = [];

        // 1. Luaran HealthRiskAssessmentTool
        if (isset($toolOutputs['HealthRiskAssessmentTool'])) {
            $risk = $toolOutputs['HealthRiskAssessmentTool'];
            $riskSec = "### 🔍 Analisis Faktor Risiko & Status Tubuh\n";
            $riskSec .= "{$risk['bmi_clarification']}\n\n";
            $riskSec .= "**Rincian Faktor Penilai Risiko:**\n";
            foreach ($risk['contributors'] as $c) {
                $pointsLabel = $c['points'] > 0 ? "+{$c['points']} poin" : "0 poin";
                $riskSec .= "* **{$c['factor']} ({$pointsLabel}):** {$c['clinical_context']}\n";
            }
            $riskSec .= "\n> **Penting:** {$risk['diagnostic_disclaimer']}";
            $sections[] = $riskSec;
        }

        // 2. Luaran NutritionTool
        if (isset($toolOutputs['NutritionTool'])) {
            $nut = $toolOutputs['NutritionTool'];
            $meals = $nut['meals'];
            $nutSec = "### 🥗 Rekomendasi Menu Sehat Rendah Indeks Glikemik\n";
            $nutSec .= "Target Energi: **{$nut['target_calories']} kcal/hari** | Batas Gula Maksimal: **{$nut['sugar_limit_grams']} gram/hari**.\n\n";
            $nutSec .= "* **🌅 Sarapan ({$meals['breakfast']['time']}):** {$meals['breakfast']['menu_utama']}\n";
            $nutSec .= "* **☀️ Makan Siang ({$meals['lunch']['time']}):** {$meals['lunch']['konsep']} — Karbohidrat: {$meals['lunch']['karbohidrat']} Lauk: {$meals['lunch']['protein']} Sayur: {$meals['lunch']['sayuran']}\n";
            $nutSec .= "* **🌙 Makan Malam ({$meals['dinner']['time']}):** {$meals['dinner']['menu']}\n";
            $nutSec .= "* **🍎 Pilihan Camilan:** " . implode(', ', $meals['snacks']['pilihan']) . "\n";
            $sections[] = $nutSec;
        }

        // 3. Luaran MedicalKnowledgeRetrievalTool
        if (isset($toolOutputs['MedicalKnowledgeRetrievalTool'])) {
            $med = $toolOutputs['MedicalKnowledgeRetrievalTool'];
            foreach ($med['entries'] as $entry) {
                $medSec = "### 🩺 Informasi Medis: {$entry['topic']}\n";
                $medSec .= "{$entry['content']}\n\n";
                $medSec .= "*Sumber Referensi: {$entry['reference']}*";
                $sections[] = $medSec;
            }
        }

        // 4. Luaran LaboratoryInformationTool
        if (isset($toolOutputs['LaboratoryInformationTool'])) {
            $lab = $toolOutputs['LaboratoryInformationTool'];
            $labSec = "### 📊 Informasi Pemeriksaan Laboratorium Medis\n";
            if (!empty($lab['evaluated_markers'])) {
                $labSec .= "**Hasil Evaluasi Parameter yang Disebutkan:**\n";
                foreach ($lab['evaluated_markers'] as $m) {
                    $labSec .= "* **{$m['marker']}:** {$m['value']} {$m['unit']} — **{$m['status']}** ({$m['clinical_note']})\n";
                }
            } else {
                $labSec .= "Berikut nilai rujukan standar resmi (*PERKENI & ADA*):\n\n";
                $labSec .= "| Parameter | Normal | Prediabetes | Diabetes Melitus |\n";
                $labSec .= "| :--- | :--- | :--- | :--- |\n";
                $labSec .= "| **HbA1c** | < 5.7% | 5.7% - 6.4% | ≥ 6.5% |\n";
                $labSec .= "| **Gula Darah Puasa (GDP)** | < 100 mg/dL | 100 - 125 mg/dL | ≥ 126 mg/dL |\n";
                $labSec .= "| **Gula Darah Sewaktu (GDS)** | < 140 mg/dL | 140 - 199 mg/dL | ≥ 200 mg/dL |\n";
            }
            $sections[] = $labSec;
        }

        // 5. Luaran ExerciseRecommendationTool
        if (isset($toolOutputs['ExerciseRecommendationTool'])) {
            $ex = $toolOutputs['ExerciseRecommendationTool'];
            $exSec = "### 🏃 Panduan Aktivitas Fisik Mandiri\n";
            $exSec .= "Target Latihan: **{$ex['target_duration']}** (Intensitas: {$ex['target_intensity']})\n\n";
            $exSec .= "**Pilihan Olahraga Kardio:**\n* " . implode("\n* ", $ex['aerobic_options']) . "\n\n";
            $exSec .= "**Latihan Penguatan Otot:**\n* " . implode("\n* ", $ex['resistance_options']) . "\n\n";
            $exSec .= "**Pedoman Keamanan Medis:**\n* " . implode("\n* ", $ex['safety_precautions']);
            $sections[] = $exSec;
        }

        if (!empty($sections)) {
            return implode("\n\n---\n\n", $sections);
        }

        return "Saya siap membantu Anda seputar edukasi diabetes, penjelasan model Machine Learning XGBoost, atau panduan skrining risiko mandiri. Ada hal spesifik yang ingin Anda ketahui?";
    }
}

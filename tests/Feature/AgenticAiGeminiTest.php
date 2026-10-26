<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use App\Services\GeminiService;
use App\Services\Agent\Tools\DiabetesPredictionTool;

class AgenticAiGeminiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.gemini.api_key' => '']);
    }
    /**
     * 1. Pesan "Halo" mendapat respons ramah dan edukatif
     */
    public function test_greeting_receives_welcoming_response(): void
    {
        $response = $this->postJson('/agent/chat', [
            'message' => 'Halo',
            'history' => []
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'reply', 'reasoning_steps', 'provider']);
        $this->assertEquals('success', $response->json('status'));
        $reply = $response->json('reply');
        $this->assertStringContainsString('Halo juga! Ada yang bisa saya bantu hari ini?', $reply);
    }

    /**
     * 2. Pertanyaan identitas "Siapa kamu?" dijawab dengan tepat
     */
    public function test_identity_question_explains_assistant_role(): void
    {
        $response = $this->postJson('/agent/chat', [
            'message' => 'Siapa kamu?',
            'history' => []
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('AI Assistant pada website Diabetes Uji Coba', $reply);
    }

    /**
     * 3. Pertanyaan umum tentang diabetes dijawab secara edukatif dan evidence-based
     */
    public function test_diabetes_question_answered_accurately(): void
    {
        $response = $this->postJson('/agent/chat', [
            'message' => 'Apa itu diabetes?',
            'history' => []
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('kadar gula darah', $reply);
        $this->assertStringContainsString('faktor risiko, gejala umum', $reply);
    }

    /**
     * 4. Pertanyaan tentang teknologi Machine Learning XGBoost dijawab secara relevan
     */
    public function test_xgboost_question_explains_model_and_cdc_dataset(): void
    {
        $response = $this->postJson('/agent/chat', [
            'message' => 'Apa itu XGBoost?',
            'history' => []
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('XGBoost', $reply);
        $this->assertStringContainsString('CDC', $reply);
        $this->assertStringContainsString('BRFSS', $reply);
    }

    /**
     * 5. Pertanyaan lanjutan menggunakan konteks riwayat sebelumnya (Multi-Turn Continuity)
     */
    public function test_contextual_follow_up_prevention_continues_conversation(): void
    {
        $history = [
            ['sender' => 'user', 'text' => 'Apa itu diabetes?'],
            ['sender' => 'model', 'text' => 'Diabetes adalah kondisi ketika kadar gula darah terlalu tinggi. Saya dapat membantu menjelaskan faktor risiko, gejala umum, dan cara pencegahannya.']
        ];

        $response = $this->postJson('/agent/chat', [
            'message' => 'Bagaimana cara mencegahnya?',
            'history' => $history
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('pencegahan diabetes', $reply);
        $this->assertStringContainsString('Pola Makan Sehat', $reply);
        $this->assertStringContainsString('Aktivitas Fisik', $reply);
    }

    /**
     * 6. Pertanyaan lanjutan keunggulan XGBoost berbasis riwayat
     */
    public function test_contextual_follow_up_xgboost_advantages(): void
    {
        $history = [
            ['sender' => 'user', 'text' => 'Apa itu model XGBoost?'],
            ['sender' => 'model', 'text' => 'XGBoost adalah algoritma machine learning berbasis decision tree ensemble yang digunakan pada website Diabetes Uji Coba.']
        ];

        $response = $this->postJson('/agent/chat', [
            'message' => 'Apa kelebihannya?',
            'history' => $history
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('XGBoost', $reply);
        $this->assertStringContainsString('Akurasi', $reply);
        $this->assertStringContainsString('Cepat', $reply);
    }

    /**
     * 7. Pesan kosong ditolak oleh validasi backend
     */
    public function test_empty_message_is_rejected(): void
    {
        $response = $this->postJson('/agent/chat', [
            'message' => '',
            'history' => []
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
    }

    /**
     * 8. Pertanyaan obat diabetes dijawab secara lengkap dengan golongan obat dan disclaimer dokter
     */
    public function test_medication_question_explains_drug_classes_and_doctor_disclaimer(): void
    {
        $response = $this->postJson('/agent/chat', [
            'message' => 'apa obat diabetes?',
            'history' => []
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('Metformin', $reply);
        $this->assertStringContainsString('Sulfonilurea', $reply);
        $this->assertStringContainsString('resep dokter', $reply);
    }

    /**
     * 9. Pertanyaan lanjutan aturan minum/efek samping menyambung topik obat sebelumnya
     */
    public function test_follow_up_medication_dosage_is_coherent(): void
    {
        $history = [
            ['sender' => 'user', 'text' => 'apa obat diabetes?'],
            ['sender' => 'model', 'text' => 'Golongan obat diabetes utama antara lain Metformin lini pertama oral dan Sulfonilurea.']
        ];

        $response = $this->postJson('/agent/chat', [
            'message' => 'bagaimana cara minumnya?',
            'history' => $history
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('Metformin', $reply);
        $this->assertStringContainsString('sesudah makan', $reply);
    }

    /**
     * 10. Pertanyaan gejala diabetes menjelaskan trias 3P secara edukatif
     */
    public function test_symptoms_question_explains_trias_3p(): void
    {
        $response = $this->postJson('/agent/chat', [
            'message' => 'apa saja gejala diabetes?',
            'history' => []
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('Poliuria', $reply);
        $this->assertStringContainsString('Polidipsia', $reply);
        $this->assertStringContainsString('Polifagia', $reply);
    }

    /**
     * 8. Evaluasi DiabetesPredictionTool menjalankan model XGBoost dan menghasilkan kategori risiko
     */
    public function test_diabetes_prediction_tool_evaluation(): void
    {
        $tool = new DiabetesPredictionTool();
        $result = $tool->execute([
            'gender' => 'Laki-laki',
            'age' => 48,
            'height' => 170,
            'weight' => 85,
            'high_bp' => 1,
            'high_chol' => 1,
            'phys_activity' => 0,
            'family_history' => 1,
        ]);

        $this->assertTrue($result['success']);
        $this->assertEquals('DiabetesPredictionTool', $result['tool']);
        $this->assertGreaterThanOrEqual(25.0, $result['data']['bmi']);
        $this->assertContains($result['data']['risk_category'], ['Sedang', 'Tinggi']);
        $this->assertNotEmpty($result['data']['recommendations']);
        $this->assertStringContainsString('bukan vonis diagnosis', strtolower($result['data']['disclaimer']));
    }

    /**
     * 9. API Key yang belum ada ditangani secara aman (tanpa crash website)
     */
    public function test_unconfigured_api_key_falls_back_gracefully(): void
    {
        config(['services.gemini.api_key' => '']);
        
        $response = $this->postJson('/agent/chat', [
            'message' => 'Berapa batas gula harian normal?',
            'history' => []
        ]);

        $response->assertStatus(200);
        $this->assertEquals('success', $response->json('status'));
        $steps = implode(' ', $response->json('reasoning_steps'));
        $this->assertStringContainsString('Autonomous Local', $steps);
    }

    /**
     * 10. Pengujian integrasi Gemini API dengan simulasi Function Calling (Mocking HTTP)
     */
    public function test_gemini_service_handles_function_calling_and_two_turn_synthesis(): void
    {
        config(['services.gemini.api_key' => 'AIzaSyFakeKeyTestingPurposes123456789']);

        // Mock respons putaran pertama: Gemini memilih memanggil tool 'predict_diabetes_risk'
        // Mock respons putaran kedua: Gemini menyintesis jawaban akhir
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::sequence()
                ->push([
                    'candidates' => [
                        [
                            'content' => [
                                'parts' => [
                                    [
                                        'functionCall' => [
                                            'name' => 'predict_diabetes_risk',
                                            'args' => [
                                                'gender' => 'Laki-laki',
                                                'age' => 45,
                                                'height' => 170,
                                                'weight' => 75,
                                                'high_bp' => 1
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ], 200)
                ->push([
                    'candidates' => [
                        [
                            'content' => [
                                'parts' => [
                                    [
                                        'text' => 'Berdasarkan model Machine Learning XGBoost kami, tingkat risiko Anda berada pada kategori Sedang. Disarankan untuk membatasi asupan gula dan rutin beraktivitas fisik.'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ], 200)
        ]);

        $geminiService = app(GeminiService::class);
        $logs = [];
        $toolDispatcher = function ($name, $args) {
            $tool = new DiabetesPredictionTool();
            return $tool->execute($args);
        };

        $result = $geminiService->chatWithTools(
            'System Instruction',
            [],
            'Tolong prediksi risiko diabetes saya, umur 45, tinggi 170, berat 75, darah tinggi',
            $toolDispatcher,
            $logs
        );

        $this->assertTrue($result['success']);
        $this->assertEquals('predict_diabetes_risk', $result['tool_used']);
        $this->assertStringContainsString('XGBoost', $result['reply']);
        $this->assertStringContainsString('gemini-api:', $result['provider']);
    }

    /**
     * 11. Pengujian penanganan batas kuota (HTTP 429) secara aman
     */
    public function test_gemini_rate_limit_429_handled_without_crash(): void
    {
        config(['services.gemini.api_key' => 'AIzaSyFakeKeyTestingPurposes123456789']);

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'error' => [
                    'code' => 429,
                    'message' => 'Resource exhausted (quota exceeded).'
                ]
            ], 429)
        ]);

        $response = $this->postJson('/agent/chat', [
            'message' => 'Apa itu diabetes?',
            'history' => []
        ]);

        $response->assertStatus(200);
        $this->assertEquals('success', $response->json('status'));
        $steps = implode(' ', $response->json('reasoning_steps'));
        $this->assertStringContainsString('429', $steps);
        $this->assertStringContainsString('Autonomous Local Orchestrator', $steps);
    }
}

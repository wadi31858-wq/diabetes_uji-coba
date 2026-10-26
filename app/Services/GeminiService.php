<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');
        $this->model = config('services.gemini.model') ?: env('GEMINI_MODEL', 'gemini-3.5-flash');
        $this->baseUrl = config('services.gemini.base_url') ?: 'https://generativelanguage.googleapis.com/v1beta';
        $this->timeout = (int)(config('services.gemini.timeout') ?: env('GEMINI_TIMEOUT', 20));
    }

    /**
     * Memeriksa apakah API Key Gemini telah dikonfigurasi secara valid di .env
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && strlen(trim($this->apiKey)) > 10;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Deklarasi Tools (Function Calling) yang didaftarkan ke Gemini API.
     */
    public function getToolDeclarations(): array
    {
        return [
            [
                'name' => 'predict_diabetes_risk',
                'description' => 'Menjalankan evaluasi prediksi risiko diabetes melitus menggunakan model Machine Learning XGBoost (dataset CDC BRFSS) berdasarkan data klinis pengguna.',
                'parameters' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'gender' => [
                            'type' => 'STRING',
                            'enum' => ['Laki-laki', 'Perempuan'],
                            'description' => 'Jenis kelamin pengguna'
                        ],
                        'age' => [
                            'type' => 'INTEGER',
                            'description' => 'Usia pengguna dalam tahun (contoh: 45)'
                        ],
                        'height' => [
                            'type' => 'NUMBER',
                            'description' => 'Tinggi badan pengguna dalam cm (contoh: 168)'
                        ],
                        'weight' => [
                            'type' => 'NUMBER',
                            'description' => 'Berat badan pengguna dalam kg (contoh: 75)'
                        ],
                        'high_bp' => [
                            'type' => 'INTEGER',
                            'description' => '1 jika memiliki riwayat tekanan darah tinggi (hipertensi), 0 jika tidak'
                        ],
                        'high_chol' => [
                            'type' => 'INTEGER',
                            'description' => '1 jika memiliki riwayat kolesterol tinggi, 0 jika tidak'
                        ],
                        'phys_activity' => [
                            'type' => 'INTEGER',
                            'description' => '1 jika rutin berolahraga / aktif fisik minimal 30 menit sehari, 0 jika jarang/kurang aktif'
                        ],
                        'family_history' => [
                            'type' => 'INTEGER',
                            'description' => '1 jika memiliki orang tua/saudara kandung penderita diabetes, 0 jika tidak'
                        ]
                    ],
                    'required' => ['age', 'height', 'weight']
                ]
            ],
            [
                'name' => 'calculate_calories_and_sugar',
                'description' => 'Menghitung kebutuhan kalori harian (BMR & TDEE) serta batas konsumsi gula harian maksimal (gram) menurut standar PERKENI & WHO.',
                'parameters' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'gender' => [
                            'type' => 'STRING',
                            'enum' => ['Laki-laki', 'Perempuan'],
                            'description' => 'Jenis kelamin pengguna'
                        ],
                        'age' => [
                            'type' => 'INTEGER',
                            'description' => 'Usia pengguna dalam tahun'
                        ],
                        'height' => [
                            'type' => 'NUMBER',
                            'description' => 'Tinggi badan dalam cm'
                        ],
                        'weight' => [
                            'type' => 'NUMBER',
                            'description' => 'Berat badan dalam kg'
                        ]
                    ],
                    'required' => ['age', 'height', 'weight']
                ]
            ],
            [
                'name' => 'get_medical_knowledge',
                'description' => 'Mencari referensi edukasi klinis diabetes evidence-based (obat seperti Metformin/Insulin, gejala 3P, pantangan makanan, pencegahan, komplikasi neuropati/kaki diabetik, puasa).',
                'parameters' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'topic' => [
                            'type' => 'STRING',
                            'description' => 'Topik atau kata kunci yang dicari (contoh: metformin, gejala 3p, komplikasi kaki, aturan minum obat, puasa)'
                        ]
                    ],
                    'required' => ['topic']
                ]
            ],
            [
                'name' => 'get_lab_guidelines',
                'description' => 'Mengambil tabel nilai rujukan baku tes laboratorium diabetes resmi dari standar PERKENI dan ADA (HbA1c, Gula Darah Puasa GDP, Gula Darah Sewaktu GDS).',
                'parameters' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'marker' => [
                            'type' => 'STRING',
                            'description' => 'Parameter spesifik yang ditanyakan (hba1c, gdp, gds, atau semua)'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'get_exercise_recommendation',
                'description' => 'Mengambil rekomendasi aktivitas fisik dan olahraga yang aman untuk pengendalian gula darah dan pencegahan komplikasi diabetes.',
                'parameters' => [
                    'type' => 'OBJECT',
                    'properties' => [
                        'age' => ['type' => 'INTEGER', 'description' => 'Usia pasien'],
                        'bmi' => ['type' => 'NUMBER', 'description' => 'Indeks massa tubuh pasien'],
                        'high_bp' => ['type' => 'INTEGER', 'description' => '1 jika hipertensi, 0 jika normal']
                    ]
                ]
            ]
        ];
    }

    /**
     * Memformat dan menormalkan riwayat percakapan sesuai aturan ketat Gemini API:
     * - role harus 'user' atau 'model'
     * - pesan pertama harus 'user'
     * - tidak boleh ada role yang berulang berurutan
     */
    public function formatContents(array $history, string $currentMessage): array
    {
        $normalized = [];
        $lastRole = null;

        // Ambil maksimal 8 pesan terakhir dari riwayat agar token hemat
        $recent = array_slice($history, -8);

        foreach ($recent as $turn) {
            $sender = strtolower(trim($turn['sender'] ?? 'user'));
            $role = ($sender === 'model' || $sender === 'assistant' || $sender === 'bot') ? 'model' : 'user';
            $text = trim($turn['text'] ?? '');

            if ($text === '') {
                continue;
            }

            // Pesan pertama harus ber-role 'user'
            if (empty($normalized) && $role !== 'user') {
                continue;
            }

            // Gabungkan jika ada role berurutan sama
            if ($lastRole === $role) {
                $lastIndex = count($normalized) - 1;
                $normalized[$lastIndex]['parts'][0]['text'] .= "\n\n" . $text;
            } else {
                $normalized[] = [
                    'role' => $role,
                    'parts' => [['text' => $text]]
                ];
                $lastRole = $role;
            }
        }

        // Tambahkan pesan pengguna saat ini
        if ($lastRole === 'user' && !empty($normalized)) {
            $lastIndex = count($normalized) - 1;
            $normalized[$lastIndex]['parts'][0]['text'] .= "\n\n" . $currentMessage;
        } else {
            $normalized[] = [
                'role' => 'user',
                'parts' => [['text' => $currentMessage]]
            ];
        }

        return $normalized;
    }

    /**
     * Menjalankan interaksi chat Agentic AI dengan Gemini API (termasuk Tool Calling loop).
     */
    public function chatWithTools(
        string $systemInstruction,
        array $history,
        string $userMessage,
        callable $toolDispatcher,
        array &$reasoningLogs
    ): array {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'error_type' => 'missing_key',
                'error_message' => 'GEMINI_API_KEY belum dikonfigurasi pada file .env.'
            ];
        }

        $contents = $this->formatContents($history, $userMessage);
        $tools = [
            ['function_declarations' => $this->getToolDeclarations()]
        ];

        $modelsToTry = array_unique([$this->model, 'gemini-3.5-flash', 'gemini-3.5-flash-lite', 'gemini-flash-latest', 'gemini-3.8-flash']);
        $lastException = null;

        foreach ($modelsToTry as $activeModel) {
            $endpoint = "{$this->baseUrl}/models/{$activeModel}:generateContent?key={$this->apiKey}";

            try {
                $payload = [
                    'system_instruction' => [
                        'parts' => [['text' => $systemInstruction]]
                    ],
                    'contents' => $contents,
                    'tools' => $tools,
                    'generationConfig' => [
                        'temperature' => 0.4,
                        'maxOutputTokens' => 2048,
                    ]
                ];

                $response = Http::timeout($this->timeout)->post($endpoint, $payload);

                if (in_array($response->status(), [404, 429, 503])) {
                    $reasoningLogs[] = "⚠️ Model {$activeModel} merespons status {$response->status()}. Mencoba model alternatif...";
                    continue; // Coba model fallback berikutnya
                }

                if ($response->status() === 401 || $response->status() === 403) {
                    return [
                        'success' => false,
                        'error_type' => 'invalid_key',
                        'error_message' => 'Kunci API Gemini tidak valid atau izin akses ditolak. Periksa kembali GEMINI_API_KEY di .env.'
                    ];
                }

                if ($response->successful()) {
                    $data = $response->json();
                    $candidate = $data['candidates'][0] ?? null;

                    if (!$candidate) {
                        continue;
                    }

                    $parts = $candidate['content']['parts'] ?? [];
                    $functionCall = null;
                    $initialText = '';

                    foreach ($parts as $p) {
                        if (isset($p['functionCall'])) {
                            $functionCall = $p['functionCall'];
                            break;
                        }
                        if (isset($p['text'])) {
                            $initialText .= $p['text'];
                        }
                    }

                    // KASUS 1: Gemini meminta Function Calling (Agentic Action)
                    if ($functionCall) {
                        $toolName = $functionCall['name'] ?? '';
                        $toolArgs = $functionCall['args'] ?? [];

                        $reasoningLogs[] = "🛠️ Agentic Decision: Gemini memanggil tool '{$toolName}' dengan parameter: " . json_encode($toolArgs, JSON_UNESCAPED_UNICODE);

                        // Eksekusi Tool melalui callback dispatcher
                        $toolResult = $toolDispatcher($toolName, $toolArgs);

                        $reasoningLogs[] = "✅ Tool '{$toolName}' berhasil dieksekusi dengan luaran tervalidasi.";

                        // Buat riwayat function response untuk putaran ke-2
                        $contents[] = [
                            'role' => 'model',
                            'parts' => [
                                ['functionCall' => $functionCall]
                            ]
                        ];

                        $contents[] = [
                            'role' => 'function',
                            'parts' => [
                                [
                                    'functionResponse' => [
                                        'name' => $toolName,
                                        'response' => [
                                            'name' => $toolName,
                                            'content' => $toolResult
                                        ]
                                    ]
                                ]
                            ]
                        ];

                        // Panggilan putaran ke-2: Gemini menyintesis jawaban akhir berdasarkan luaran tool
                        $secondPayload = [
                            'system_instruction' => [
                                'parts' => [['text' => $systemInstruction]]
                            ],
                            'contents' => $contents,
                            'generationConfig' => [
                                'temperature' => 0.4,
                                'maxOutputTokens' => 2048,
                            ]
                        ];

                        $secondResponse = Http::timeout($this->timeout)->post($endpoint, $secondPayload);

                        if ($secondResponse->successful()) {
                            $secondData = $secondResponse->json();
                            $secondCandidate = $secondData['candidates'][0] ?? null;
                            $finalText = '';
                            if ($secondCandidate && isset($secondCandidate['content']['parts'])) {
                                foreach ($secondCandidate['content']['parts'] as $sp) {
                                    if (isset($sp['text'])) {
                                        $finalText .= $sp['text'];
                                    }
                                }
                            }

                            if (!empty($finalText)) {
                                return [
                                    'success' => true,
                                    'reply' => $finalText,
                                    'provider' => "gemini-api:{$activeModel}",
                                    'tool_used' => $toolName,
                                    'tool_result' => $toolResult
                                ];
                            }
                        }
                    }

                    // KASUS 2: Gemini menjawab langsung (sapaan, penjelasan umum, edukasi)
                    if (!empty($initialText)) {
                        return [
                            'success' => true,
                            'reply' => $initialText,
                            'provider' => "gemini-api:{$activeModel}",
                            'tool_used' => null
                        ];
                    }
                }
            } catch (\Exception $e) {
                $lastException = $e;
                Log::warning("Gemini API call ({$activeModel}) error: " . $e->getMessage());
                $reasoningLogs[] = "⚠️ Koneksi ke {$activeModel} terputus: " . $e->getMessage();
            }
        }

        return [
            'success' => false,
            'error_type' => 'api_error',
            'error_message' => $lastException ? $lastException->getMessage() : 'Gemini API tidak memberikan respons valid.'
        ];
    }
}

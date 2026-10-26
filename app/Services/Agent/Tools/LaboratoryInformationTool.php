<?php

namespace App\Services\Agent\Tools;

class LaboratoryInformationTool
{
    /**
     * Tabel Referensi Baku Laboratorium Klinis (PERKENI 2021 & ADA 2024).
     */
    protected array $standards = [
        'hba1c' => [
            'name' => 'HbA1c (Hemoglobin Terglikasi)',
            'unit' => '%',
            'ranges' => [
                'normal' => ['min' => 0.0, 'max' => 5.6, 'label' => 'Normal (<5.7%)', 'badge' => '✅ Normal'],
                'prediabetes' => ['min' => 5.7, 'max' => 6.4, 'label' => 'Prediabetes (5.7% - 6.4%)', 'badge' => '⚠️ Prediabetes'],
                'diabetes' => ['min' => 6.5, 'max' => 20.0, 'label' => 'Diabetes Melitus (≥6.5%)', 'badge' => '🚨 Diabetes']
            ],
            'description' => 'Mengukur rata-rata konsentrasi glukosa darah selama masa hidup eritrosit (8-12 minggu terakhir). Tidak terpengaruh fluktuasi makanan harian.'
        ],
        'gdp' => [
            'name' => 'Glukosa Darah Puasa (GDP)',
            'unit' => 'mg/dL',
            'ranges' => [
                'normal' => ['min' => 70, 'max' => 99, 'label' => 'Normal (<100 mg/dL)', 'badge' => '✅ Normal'],
                'prediabetes' => ['min' => 100, 'max' => 125, 'label' => 'Glukosa Darah Puasa Terganggu (100 - 125 mg/dL)', 'badge' => '⚠️ Prediabetes'],
                'diabetes' => ['min' => 126, 'max' => 1000, 'label' => 'Diabetes Melitus (≥126 mg/dL)', 'badge' => '🚨 Diabetes']
            ],
            'description' => 'Diambil setelah berpuasa kalori minimal 8-10 jam (hanya boleh air putih).'
        ],
        'gds' => [
            'name' => 'Glukosa Darah Sewaktu (GDS)',
            'unit' => 'mg/dL',
            'ranges' => [
                'normal' => ['min' => 70, 'max' => 139, 'label' => 'Normal (<140 mg/dL)', 'badge' => '✅ Normal'],
                'prediabetes' => ['min' => 140, 'max' => 199, 'label' => 'Toleransi Glukosa Terganggu (140 - 199 mg/dL)', 'badge' => '⚠️ Waspada'],
                'diabetes' => ['min' => 200, 'max' => 1000, 'label' => 'Diabetes Melitus (≥200 mg/dL dengan gejala klasik)', 'badge' => '🚨 Diabetes']
            ],
            'description' => 'Diperiksa kapan saja tanpa memperhatikan waktu makan terakhir.'
        ],
        'kolesterol' => [
            'name' => 'Kolesterol Total',
            'unit' => 'mg/dL',
            'ranges' => [
                'normal' => ['min' => 0, 'max' => 199, 'label' => 'Diinginkan (<200 mg/dL)', 'badge' => '✅ Optimal'],
                'borderline' => ['min' => 200, 'max' => 239, 'label' => 'Batas Tinggi (200 - 239 mg/dL)', 'badge' => '⚠️ Batas Tinggi'],
                'high' => ['min' => 240, 'max' => 1000, 'label' => 'Tinggi (≥240 mg/dL)', 'badge' => '🚨 Tinggi']
            ],
            'description' => 'Total lipid darah yang berkontribusi pada plak pembuluh darah.'
        ]
    ];

    /**
     * Mengevaluasi parameter lab aktual atau memberikan pedoman standar jika tanpa nilai input.
     */
    public function execute(array $params): array
    {
        $inputValues = $params['lab_values'] ?? [];
        $evaluatedMarkers = [];

        // Evaluasi jika ada nilai aktual yang diinputkan pengguna atau diekstrak dari file
        foreach ($inputValues as $markerKey => $rawVal) {
            $key = strtolower(trim($markerKey));
            $val = is_numeric($rawVal) ? (float)$rawVal : null;
            
            if ($val !== null && isset($this->standards[$key])) {
                $std = $this->standards[$key];
                $status = 'Di luar rentang referensi baku';
                $badge = 'ℹ️ Informasi';

                foreach ($std['ranges'] as $rangeKey => $r) {
                    if ($val >= $r['min'] && $val <= $r['max']) {
                        $status = $r['label'];
                        $badge = $r['badge'];
                        break;
                    }
                }

                $evaluatedMarkers[] = [
                    'marker' => $std['name'],
                    'value' => $val,
                    'unit' => $std['unit'],
                    'status' => $status,
                    'badge' => $badge,
                    'clinical_note' => $std['description']
                ];
            }
        }

        return [
            'success' => true,
            'tool' => 'LaboratoryInformationTool',
            'data' => [
                'has_actual_data' => !empty($evaluatedMarkers),
                'evaluated_markers' => $evaluatedMarkers,
                'standard_reference_table' => $this->standards,
                'official_guidelines' => [
                    'PERKENI' => 'Pedoman Pengelolaan dan Pencegahan Diabetes Melitus Tipe 2 Dewasa di Indonesia 2021',
                    'ADA' => 'Standards of Care in Diabetes 2024 (American Diabetes Association)'
                ]
            ]
        ];
    }
}

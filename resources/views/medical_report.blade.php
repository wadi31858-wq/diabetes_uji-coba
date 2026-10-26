<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Medis Skrining Risiko Diabetes #{{ $assessment['id'] ?? 'DRAFT' }}</title>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    @media print {
      .no-print { display: none !important; }
      body { background: white !important; font-size: 12pt; }
      .page-container { border: none !important; box-shadow: none !important; margin: 0 !important; width: 100% !important; padding: 0 !important; }
    }
  </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans min-h-screen py-8 px-4 sm:px-6">

  <!-- Top Action Bar (Non-Printable) -->
  <div class="max-w-4xl mx-auto mb-6 flex flex-wrap items-center justify-between gap-4 no-print">
    <a href="{{ route('result') }}" class="inline-flex items-center text-sm font-semibold text-emerald-700 hover:text-emerald-800 transition">
      &larr; Kembali ke Hasil Skrining
    </a>

    <div class="flex items-center gap-3">
      <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm shadow transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        <span>Cetak / Simpan sebagai PDF</span>
      </button>
    </div>
  </div>

  <!-- Medical Document Container -->
  <div class="max-w-4xl mx-auto bg-white border border-gray-200 rounded-2xl shadow-md p-8 sm:p-12 page-container">
    
    <!-- 1. Header Klinik / Laboratorium -->
    <div class="flex items-center justify-between pb-6 border-b-2 border-gray-900 gap-4">
      <div class="flex items-center space-x-3">
        <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-2xl">
          +
        </div>
        <div>
          <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight uppercase">Pusat Layanan Deteksi Dini & Pencegahan Diabetes</h1>
          <p class="text-xs text-gray-500 font-medium">Platform Uji Coba • Evaluasi Machine Learning XGBoost & AI PINTER</p>
        </div>
      </div>
      <div class="text-right text-xs text-gray-500">
        <p class="font-bold text-gray-800">NO. DOKUMEN: #MED-{{ str_pad($assessment['id'] ?? rand(100, 999), 5, '0', STR_PAD_LEFT) }}</p>
        <p>Tanggal: {{ date('d F Y') }}</p>
        <p>Status: Terverifikasi Sistem</p>
      </div>
    </div>

    <!-- Title Section -->
    <div class="text-center py-6">
      <h2 class="text-lg font-extrabold text-gray-900 tracking-wide uppercase">Laporan Evaluasi Risiko Diabetes & Rekomendasi Klinis</h2>
      <p class="text-xs text-gray-500 mt-1">Dokumen resmi skrining terintegrasi berbasis bukti klinis (*Evidence-Based Screening*)</p>
    </div>

    <!-- 2. Informasi Pasien & Parameter Biometrik -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 bg-gray-50 rounded-xl p-4 border border-gray-200 text-xs mb-6">
      <div>
        <span class="text-gray-500 block">Jenis Kelamin:</span>
        <strong class="text-gray-900 text-sm">{{ $assessment['gender'] ?? 'Laki-laki' }}</strong>
      </div>
      <div>
        <span class="text-gray-500 block">Usia:</span>
        <strong class="text-gray-900 text-sm">{{ $assessment['age'] ?? 30 }} Tahun</strong>
      </div>
      <div>
        <span class="text-gray-500 block">Tinggi / Berat Badan:</span>
        <strong class="text-gray-900 text-sm">{{ $assessment['height'] ?? 170 }} cm / {{ $assessment['weight'] ?? 65 }} kg</strong>
      </div>
      <div>
        <span class="text-gray-500 block">Indeks Massa Tubuh (BMI):</span>
        <strong class="text-gray-900 text-sm">{{ $assessment['bmi'] ?? 24.5 }} kg/m²</strong>
      </div>
    </div>

    <!-- 3. Hasil Analisis Prediktif XGBoost -->
    <div class="border border-gray-200 rounded-xl p-5 mb-6">
      <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">1. Evaluasi Tingkat Risiko (XGBoost Classifier)</h3>
      
      <div class="flex items-center justify-between p-4 rounded-xl" style="background-color: {{ ($assessment['category'] ?? 'Sedang') === 'Rendah' ? '#eef9e6' : (($assessment['category'] ?? 'Sedang') === 'Tinggi' ? '#ffebee' : '#fff4e6') }};">
        <div>
          <span class="text-xs uppercase font-semibold text-gray-600 block">Kategori Risiko Pasien:</span>
          <span class="text-2xl font-black" style="color: {{ $assessment['color'] ?? '#f37920' }};">
            RISIKO {{ strtoupper($assessment['category'] ?? 'SEDANG') }}
          </span>
          <p class="text-xs text-gray-600 mt-0.5">Skor Akumulatif: <strong>{{ $assessment['score'] ?? 10 }} / 25</strong></p>
        </div>
        <div class="text-right text-xs max-w-xs text-gray-700">
          @if(($assessment['category'] ?? 'Sedang') === 'Rendah')
            Probabilitas rendah terjadi gangguan metabolisme glukosa dalam 5 tahun ke depan.
          @elseif(($assessment['category'] ?? 'Sedang') === 'Sedang')
            Terindikasi potensi prediabetes atau resistensi insulin. Memerlukan intervensi gaya hidup terencana.
          @else
            Risiko tinggi terjadi hiperglikemia persisten. Dianjurkan segera melakukan evaluasi laboratorium lengkap.
          @endif
        </div>
      </div>
    </div>

    <!-- 4. Rincian Faktor Klinis yang Dinilai -->
    <div class="border border-gray-200 rounded-xl p-5 mb-6">
      <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">2. Indikator Kesehatan yang Tercatat</h3>
      <table class="w-full text-xs text-left">
        <thead>
          <tr class="border-b border-gray-200 text-gray-500">
            <th class="py-2">Indikator</th>
            <th class="py-2">Status</th>
            <th class="py-2">Pengaruh Metabolik</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr>
            <td class="py-2 font-medium">Tekanan Darah Tinggi / Hipertensi</td>
            <td class="py-2">
              <span class="px-2 py-0.5 rounded font-bold {{ !empty($assessment['high_bp']) ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                {{ !empty($assessment['high_bp']) ? 'Positif (Ada)' : 'Negatif' }}
              </span>
            </td>
            <td class="py-2 text-gray-600">{{ !empty($assessment['high_bp']) ? 'Beban vaskular meningkat, memicu resistensi perifer' : 'Tekanan vaskular dalam batas aman' }}</td>
          </tr>
          <tr>
            <td class="py-2 font-medium">Riwayat Kolesterol Tinggi</td>
            <td class="py-2">
              <span class="px-2 py-0.5 rounded font-bold {{ !empty($assessment['high_chol']) ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                {{ !empty($assessment['high_chol']) ? 'Positif (Ada)' : 'Negatif' }}
              </span>
            </td>
            <td class="py-2 text-gray-600">{{ !empty($assessment['high_chol']) ? 'Risiko dislipidemia dan plak aterosklerotik' : 'Profil lipid terkendali' }}</td>
          </tr>
          <tr>
            <td class="py-2 font-medium">Aktivitas Fisik Rutin</td>
            <td class="py-2">
              <span class="px-2 py-0.5 rounded font-bold {{ !empty($assessment['phys_activity']) ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                {{ !empty($assessment['phys_activity']) ? 'Aktif' : 'Kurang Aktif' }}
              </span>
            </td>
            <td class="py-2 text-gray-600">{{ !empty($assessment['phys_activity']) ? 'Transporter glukosa (GLUT4) aktif di otot' : 'Efisiensi penyerapan glukosa otot berkurang' }}</td>
          </tr>
          <tr>
            <td class="py-2 font-medium">Riwayat Diabetes dalam Keluarga</td>
            <td class="py-2">
              <span class="px-2 py-0.5 rounded font-bold {{ !empty($assessment['family_history']) ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                {{ !empty($assessment['family_history']) ? 'Ada' : 'Tidak Ada' }}
              </span>
            </td>
            <td class="py-2 text-gray-600">{{ !empty($assessment['family_history']) ? 'Kerentanan genetik sel beta pankreas lebih tinggi' : 'Tidak ada faktor genetik langsung' }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- 5. Profil Nutrisi & Target Energi (Health Co-Pilot AI) -->
    <div class="border border-gray-200 rounded-xl p-5 mb-6 bg-emerald-50/40">
      <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">3. Panduan Nutrisi & Energi Harian (AI PINTER)</h3>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs mb-3">
        <div class="bg-white p-3 rounded-lg border border-gray-200">
          <span class="text-gray-500 block">Kebutuhan BMR:</span>
          <strong class="text-base text-gray-900">{{ $biometrics['bmr'] ?? 1600 }} kcal</strong>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200">
          <span class="text-gray-500 block">Target Kalori Harian:</span>
          <strong class="text-base text-emerald-700">{{ $biometrics['target_calories'] ?? 2000 }} kcal</strong>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200">
          <span class="text-gray-500 block">Batas Maksimal Gula:</span>
          <strong class="text-base text-red-600">{{ $biometrics['sugar_limit_grams'] ?? 20 }} gram/hari</strong>
        </div>
        <div class="bg-white p-3 rounded-lg border border-gray-200">
          <span class="text-gray-500 block">Target Serat Pangan:</span>
          <strong class="text-base text-gray-900">{{ $biometrics['fiber_grams'] ?? 25 }} gram/hari</strong>
        </div>
      </div>
      <p class="text-[11px] text-gray-600 leading-relaxed">
        <strong>Anjuran Piring Sehat Kemenkes:</strong> 1/2 piring sayur dan buah rendah indeks glikemik, 1/4 piring karbohidrat kompleks (beras merah/oat/ubi), dan 1/4 piring protein rendah lemak (ikan/tempe/tahu/dada ayam).
      </p>
    </div>

    <!-- 6. Rekomendasi Tindak Lanjut Medis -->
    <div class="border border-gray-200 rounded-xl p-5 mb-8">
      <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">4. Rekomendasi Pemeriksaan Laboratorium Lanjutan</h3>
      <ul class="text-xs space-y-1.5 text-gray-700 list-disc pl-5">
        <li><strong>Tes HbA1c (Hemoglobin A1c):</strong> Mengukur rata-rata glukosa darah 2-3 bulan terakhir (target normal < 5.7%).</li>
        <li><strong>Glukosa Darah Puasa (GDP):</strong> Pengambilan darah setelah 8-10 jam puasa (target normal < 100 mg/dL).</li>
        <li><strong>Pemeriksaan Profil Lipid Lengkap:</strong> Evaluasi Kolesterol Total, LDL, HDL, dan Trigliserida.</li>
        <li><strong>Konsultasi Spesialis:</strong> Bawa lembar laporan ini ke Dokter Spesialis Penyakit Dalam (Sp.PD).</li>
      </ul>
    </div>

    <!-- 7. Tanda Tangan & Validasi -->
    <div class="pt-6 border-t border-gray-200 flex justify-between items-end text-xs text-gray-500">
      <div>
        <p class="font-bold text-gray-700">AI PINTER Screening System</p>
        <p>Kode Hash: {{ substr(md5(($assessment['id'] ?? '1') . ($assessment['score'] ?? '0') . date('Ymd')), 0, 16) }}</p>
        <p class="text-[10px] text-gray-400 mt-1">Dokumen ini digenerate secara otomatis oleh sistem kecerdasan buatan.</p>
      </div>

      <div class="text-center">
        <p class="mb-12 font-medium">Dokter / Petugas Verifikator,</p>
        <div class="border-b border-gray-400 w-44 mx-auto"></div>
        <p class="text-[11px] font-semibold text-gray-700 mt-1">( .................................................. )</p>
      </div>
    </div>

  </div>

</body>
</html>

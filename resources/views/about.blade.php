@extends('layouts.app')

@section('title', 'Tentang Kami - Diabetes Uji Coba')

@section('content')
<div class="bg-gradient-to-b from-[#EAF7FF]/60 via-white to-[#F4F9FD] min-h-[90vh] py-8 sm:py-14">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-16">

    <!-- ============================================================== -->
    <!-- BAGIAN 1: TENTANG KAMI                                         -->
    <!-- ============================================================== -->
    <div id="tentang-kami" class="scroll-mt-24 space-y-8">
      
      <!-- 1. Hero Header Banner -->
      <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#073b45] via-[#0a4e5c] to-[#123B5D] p-6 sm:p-10 text-white shadow-xl border border-[#168FE5]/30">
        <!-- Ambient Glow -->
        <div class="absolute -top-16 -right-16 w-64 h-64 bg-[#00BFA6]/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-16 w-72 h-72 bg-[#43B4F2]/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-3xl space-y-3 sm:space-y-4">
          <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#123B5D]/80 border border-[#00BFA6]/40 text-[#00BFA6] text-xs font-bold tracking-wide">
            <span class="w-2 h-2 rounded-full bg-[#00BFA6] animate-pulse"></span>
            <span>PROFIL PLATFORM</span>
          </div>
          <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-tight">
            Tentang Kami — <span class="text-[#00BFA6]">Diabetes AI Analyzer</span>
          </h1>
          <p class="text-xs sm:text-base text-[#EAF7FF]/90 leading-relaxed font-normal">
            Platform edukasi kesehatan digital dan sarana skrining mandiri risiko diabetes melitus berbasis machine learning, dirancang untuk membantu masyarakat mendeteksi potensi risiko metabolik sejak dini secara mudah, transparan, dan bertanggung jawab.
          </p>
        </div>
      </div>

      <!-- Tujuan & Filosofi Edukasi -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
        
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm hover:shadow-md transition-all space-y-3">
          <div class="w-12 h-12 rounded-2xl bg-[#EAF7FF] text-[#00BFA6] border border-[#00BFA6]/30 flex items-center justify-center text-2xl font-bold">
            🎯
          </div>
          <h2 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight">
            Tujuan Website
          </h2>
          <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
            Diabetes melitus sering dijuluki sebagai <em>"silent killer"</em> karena pada fase awal kerap tidak menunjukkan gejala yang spesifik. Tujuan utama website ini adalah memfasilitasi skrining mandiri cepat bagi masyarakat luas guna mengidentifikasi tingkat risiko lebih dini, sekaligus memberikan edukasi preventif berbasis panduan medis resmi.
          </p>
        </div>

        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm hover:shadow-md transition-all space-y-3">
          <div class="w-12 h-12 rounded-2xl bg-[#EAF7FF] text-[#168FE5] border border-[#168FE5]/30 flex items-center justify-center text-2xl font-bold">
            💡
          </div>
          <h2 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight">
            Filosofi Edukasi Tanpa Stigma
          </h2>
          <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
            Mengetahui adanya faktor risiko bukanlah vonis, melainkan peluang berharga untuk berikhtiar memperbaiki gaya hidup (3J: Jumlah, Jenis, Jadwal makan) dan berkonsultasi dengan tenaga medis sebelum komplikasi terjadi. Kami menyajikan informasi secara ilmiah, damai, dan menguatkan pengguna.
          </p>
        </div>

      </section>

      <!-- Fungsi Prediksi Diabetes (XGBoost) -->
      <section class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-5">
        <div class="flex items-center space-x-3">
          <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#00BFA6] to-[#168FE5] text-white flex items-center justify-center text-2xl shadow-sm shadow-[#00BFA6]/20">
            ⚙️
          </div>
          <div>
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight">
              Fungsi Prediksi Risiko Diabetes
            </h2>
            <p class="text-xs text-gray-400">Algoritma Machine Learning Berbasis Bukti (Evidence-Based)</p>
          </div>
        </div>

        <div class="text-xs sm:text-sm text-gray-600 leading-relaxed space-y-3">
          <p>
            Fungsi evaluasi risiko pada website ini memanfaatkan model machine learning <strong>XGBoost (Extreme Gradient Boosting) Classifier</strong> yang dilatih menggunakan dataset <em>Behavioral Risk Factor Surveillance System (BRFSS)</em> dari Centers for Disease Control and Prevention (CDC).
          </p>
          <p>
            Model memproses <strong>21 parameter klinis, biometrik, dan kebiasaan hidup</strong> untuk memprediksi probabilitas risiko:
          </p>
          
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
            <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
              <span class="text-sm font-bold text-gray-900 block mb-1">⚖️ Parameter Biometrik</span>
              <span class="text-[11px] text-gray-500">Usia, jenis kelamin, Indeks Massa Tubuh (BMI), berat badan, dan tinggi badan.</span>
            </div>
            <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
              <span class="text-sm font-bold text-gray-900 block mb-1">🩺 Riwayat Klinis</span>
              <span class="text-[11px] text-gray-500">Tekanan darah tinggi, kolesterol tinggi, riwayat penyakit jantung/stroke, dan riwayat keluarga.</span>
            </div>
            <div class="p-3.5 bg-gray-50/80 rounded-2xl border border-gray-100">
              <span class="text-sm font-bold text-gray-900 block mb-1">🏃 Kebiasaan Sehari-hari</span>
              <span class="text-[11px] text-gray-500">Aktivitas fisik mingguan, kebiasaan merokok, dan frekuensi konsumsi sayur/buah harian.</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Teknologi yang Digunakan -->
      <section class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-5">
        <div class="flex items-center space-x-3">
          <div class="w-12 h-12 rounded-2xl bg-[#EAF7FF] text-[#168FE5] border border-[#168FE5]/30 flex items-center justify-center text-2xl font-bold">
            💻
          </div>
          <div>
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight">
              Teknologi yang Digunakan
            </h2>
            <p class="text-xs text-gray-400">Fondasi Perangkat Lunak Modern, Cepat &amp; Aman</p>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          
          <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1.5">
            <span class="text-xs font-black text-[#00BFA6] uppercase tracking-wider block">Backend</span>
            <h3 class="font-bold text-gray-900 text-sm">Laravel Framework</h3>
            <p class="text-[11px] text-gray-500 leading-relaxed">
              Arsitektur MVC yang tangguh, routing aman, validasi input, manajemen sesi, dan keamanan CSRF.
            </p>
          </div>

          <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1.5">
            <span class="text-xs font-black text-[#168FE5] uppercase tracking-wider block">Machine Learning</span>
            <h3 class="font-bold text-gray-900 text-sm">Python &amp; XGBoost</h3>
            <p class="text-[11px] text-gray-500 leading-relaxed">
              Model inferensi gradient boosting presisi tinggi yang dihubungkan melalui Python CLI bridge.
            </p>
          </div>

          <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1.5">
            <span class="text-xs font-black text-[#43B4F2] uppercase tracking-wider block">Frontend UI/UX</span>
            <h3 class="font-bold text-gray-900 text-sm">Tailwind CSS &amp; Blade</h3>
            <p class="text-[11px] text-gray-500 leading-relaxed">
              Antarmuka responsif dan ramah sentuhan, optimal untuk layar browser Android maupun desktop.
            </p>
          </div>

          <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 space-y-1.5">
            <span class="text-xs font-black text-[#123B5D] uppercase tracking-wider block">Agentic AI</span>
            <h3 class="font-bold text-gray-900 text-sm">Google Gemini AI</h3>
            <p class="text-[11px] text-gray-500 leading-relaxed">
              Asisten interaktif HILDA / AI Pinter untuk tanya jawab edukasi medis dan pembacaan berkas lab.
            </p>
          </div>

        </div>
      </section>

      <!-- Batasan Sistem & Disclaimer Medis -->
      <section class="bg-amber-50/60 rounded-3xl p-6 sm:p-8 border border-amber-200/80 shadow-xs space-y-3">
        <div class="flex items-start space-x-3.5">
          <span class="text-2xl sm:text-3xl flex-shrink-0">⚠️</span>
          <div class="space-y-1.5">
            <h2 class="text-base sm:text-lg font-extrabold text-amber-950 tracking-tight">
              Batasan Sistem &amp; Penafian Medis (Medical Disclaimer)
            </h2>
            <div class="text-xs sm:text-sm text-amber-900 leading-relaxed space-y-2">
              <p>
                Website <strong>Diabetes Uji Coba</strong> berfungsi sebagai instrumen <strong>skrining risiko mandiri awal</strong> dan sarana literasi kesehatan. Sistem ini <strong>bukan</strong> diagnosis medis resmi pengganti pemeriksaan laboratorium darah (seperti GDP, GD2PP, atau HbA1c) maupun konsultasi langsung dengan dokter.
              </p>
              <p>
                Hasil analisis merupakan estimasi probabilitas statistik berbasis masukan data pengguna. Jika Anda memiliki keluhan gejala khas diabetes (sering haus, sering buang air kecil malam hari, berat badan turun drastis), segera periksakan diri ke fasilitas kesehatan terdekat.
              </p>
            </div>
          </div>
        </div>
      </section>

    </div>

    <!-- ============================================================== -->
    <!-- BAGIAN 2: ORANG YANG ANALISIS                                  -->
    <!-- ============================================================== -->
    <div id="analis" class="scroll-mt-24 space-y-6 pt-4 border-t border-gray-200">
      
      <div class="flex items-center space-x-3">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#168FE5] to-[#00BFA6] text-white flex items-center justify-center text-2xl font-bold shadow-sm shadow-[#168FE5]/20">
          🔬
        </div>
        <div>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EAF7FF] text-[#168FE5] uppercase tracking-wider border border-[#43B4F2]/30">
            TIM ANALIS DATA &amp; AI
          </span>
          <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
            Orang yang Analisis
          </h2>
          <p class="text-xs sm:text-sm text-gray-500">Peran Analis, Metodologi Pengolahan Data &amp; Pemodelan Machine Learning</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <!-- Card 1: Peran Analisis -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-3">
          <div class="w-10 h-10 rounded-xl bg-[#EAF7FF] text-[#168FE5] flex items-center justify-center text-xl font-bold">
            📊
          </div>
          <h3 class="font-bold text-gray-900 text-base">Eksplorasi Data &amp; Preprocessing</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            Menganalisis dataset surveilans CDC BRFSS, membersihkan missing values, melakukan standardisasi variabel kategorikal dan numerik, serta menangani ketidakseimbangan kelas (class imbalance) secara bertanggung jawab.
          </p>
        </div>

        <!-- Card 2: Feature Engineering -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-3">
          <div class="w-10 h-10 rounded-xl bg-[#EAF7FF] text-[#00BFA6] flex items-center justify-center text-xl font-bold">
            🧠
          </div>
          <h3 class="font-bold text-gray-900 text-base">Seleksi &amp; Pembobotan Fitur</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            Menentukan 21 indikator risiko paling signifikan dengan menghitung korelasi Spearman dan feature importance gain pada algoritma XGBoost agar kuesioner ringkas namun tetap representatif.
          </p>
        </div>

        <!-- Card 3: Evaluasi & Validasi Model -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-3">
          <div class="w-10 h-10 rounded-xl bg-[#EAF7FF] text-[#43B4F2] flex items-center justify-center text-xl font-bold">
            📐
          </div>
          <h3 class="font-bold text-gray-900 text-base">Evaluasi Confusion Matrix</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            Menguji performa model melalui metrik Accuracy, Precision, Recall, dan F1-Score untuk meminimalkan risiko false negative serta memastikan pembagian kategori risiko (Rendah, Sedang, Tinggi) terdistribusi proporsional.
          </p>
        </div>

      </div>

      <!-- Ringkasan Alur Analisis -->
      <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-4">
        <h3 class="font-bold text-gray-900 text-sm sm:text-base">
          Alur Kerja Analis Sistem:
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
          <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-center space-y-1">
            <span class="font-extrabold text-[#168FE5] block">1. Pengumpulan Data</span>
            <p class="text-gray-500 text-[11px]">Dataset CDC BRFSS tervalidasi</p>
          </div>
          <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-center space-y-1">
            <span class="font-extrabold text-[#00BFA6] block">2. Training Model</span>
            <p class="text-gray-500 text-[11px]">Algoritma XGBoost &amp; tuning hiperparameter</p>
          </div>
          <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-center space-y-1">
            <span class="font-extrabold text-[#43B4F2] block">3. Integrasi Sistem</span>
            <p class="text-gray-500 text-[11px]">Python CLI Bridge ke Laravel MVC</p>
          </div>
          <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-center space-y-1">
            <span class="font-extrabold text-[#123B5D] block">4. Audit Klinis</span>
            <p class="text-gray-500 text-[11px]">Penyelarasan rekomendasi PERKENI</p>
          </div>
        </div>
      </div>

    </div>

    <!-- ============================================================== -->
    <!-- BAGIAN 3: BIODATA                                              -->
    <!-- ============================================================== -->
    <div id="biodata" class="scroll-mt-24 space-y-6 pt-4 border-t border-gray-200">
      
      <div class="flex items-center space-x-3">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#00BFA6] to-[#168FE5] text-white flex items-center justify-center text-2xl font-bold shadow-sm shadow-[#00BFA6]/20">
          👤
        </div>
        <div>
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#EAF7FF] text-[#00BFA6] uppercase tracking-wider border border-[#00BFA6]/30">
            PROFIL PENGEMBANG
          </span>
          <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">
            Biodata Tim Pengembang
          </h2>
          <p class="text-xs sm:text-sm text-gray-500">Identitas, Peran &amp; Spesialisasi Proyek Diabetes Uji Coba</p>
        </div>
      </div>

      <!-- Card Biodata Utama -->
      <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
          <!-- Avatar Icon -->
          <div class="w-24 h-24 rounded-3xl bg-gradient-to-tr from-[#00BFA6] to-[#168FE5] text-white flex items-center justify-center text-4xl font-extrabold shadow-md shadow-[#00BFA6]/20 flex-shrink-0">
            👨‍💻
          </div>

          <!-- Info Pokok Biodata -->
          <div class="flex-1 text-center sm:text-left space-y-2">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <div>
                <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900">
                  Tim Pengembang &amp; Analis Sistem
                </h3>
                <p class="text-xs font-semibold text-[#168FE5]">
                  Proyek Aplikasi Skrining Diabetes Melitus • Diabetes AI Analyzer
                </p>
              </div>
              <span class="inline-flex items-center self-center sm:self-auto px-3 py-1 rounded-full text-xs font-bold bg-[#EAF7FF] text-[#123B5D] border border-[#43B4F2]/30">
                Informatika &amp; AI Medis
              </span>
            </div>

            <p class="text-xs sm:text-sm text-gray-600 leading-relaxed pt-1">
              Fokus pada perancangan arsitektur web aplikasi kesehatan berbasis data, eksplorasi model prediktif machine learning untuk deteksi dini penyakit metabolik, serta pengembangan antarmuka pengguna yang responsif, humanis, dan inklusif.
            </p>
          </div>
        </div>

        <!-- Tabel Ringkasan Biodata -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-2 border-t border-gray-100 text-xs">
          
          <div class="flex items-start space-x-3 p-3 bg-gray-50/80 rounded-2xl border border-gray-100">
            <span class="text-base">📌</span>
            <div>
              <span class="text-gray-400 block text-[10px] font-bold uppercase">Nama Proyek</span>
              <span class="font-bold text-gray-800 text-xs">Diabetes AI Analyzer</span>
            </div>
          </div>

          <div class="flex items-start space-x-3 p-3 bg-gray-50/80 rounded-2xl border border-gray-100">
            <span class="text-base">🎯</span>
            <div>
              <span class="text-gray-400 block text-[10px] font-bold uppercase">Peran Utama</span>
              <span class="font-bold text-gray-800 text-xs">Pengembang Web Fullstack &amp; Analis Data AI</span>
            </div>
          </div>

          <div class="flex items-start space-x-3 p-3 bg-gray-50/80 rounded-2xl border border-gray-100">
            <span class="text-base">🛠️</span>
            <div>
              <span class="text-gray-400 block text-[10px] font-bold uppercase">Teknologi Keahlian</span>
              <span class="font-bold text-gray-800 text-xs">Laravel, Python (XGBoost), Tailwind CSS, Gemini API</span>
            </div>
          </div>

          <div class="flex items-start space-x-3 p-3 bg-gray-50/80 rounded-2xl border border-gray-100">
            <span class="text-base">📚</span>
            <div>
              <span class="text-gray-400 block text-[10px] font-bold uppercase">Fokus Studi &amp; Minat</span>
              <span class="font-bold text-gray-800 text-xs">Sistem Pendukung Keputusan Klinis &amp; AI Medis</span>
            </div>
          </div>

        </div>

      </div>

    </div>

    <!-- Navigasi Aksi Bawah -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-200">
      <a href="{{ route('home') }}" class="inline-flex items-center text-xs sm:text-sm font-bold text-gray-600 hover:text-[#00BFA6] transition-colors space-x-2">
        <span>&larr;</span>
        <span>Kembali ke Beranda</span>
      </a>

      <div class="flex items-center gap-3 w-full sm:w-auto">
        <a href="{{ route('quiz') }}" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-[#00BFA6] to-[#168FE5] hover:opacity-95 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-[#00BFA6]/20 transition-all text-center">
          🩺 Mulai Cek Risiko Sekarang
        </a>
        <a href="{{ route('articles') }}" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-gray-50 border border-gray-200 text-gray-800 font-bold text-xs sm:text-sm rounded-xl transition-all text-center">
          📚 Baca Artikel Edukasi
        </a>
      </div>
    </div>

  </div>
</div>
@endsection

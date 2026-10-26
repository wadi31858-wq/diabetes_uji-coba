@extends('layouts.admin')

@section('title', 'Pengaturan Logo Website | Panel Administrator')
@section('header_title', 'Pengaturan Logo Website')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

  <!-- Breadcrumb & Top Banner -->
  <div class="bg-gradient-to-r from-[#123B5D] via-[#0f4469] to-[#0a4e5c] rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
    <div class="absolute -right-8 -bottom-8 w-60 h-60 bg-[#00BFA6]/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center space-x-1.5 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-bold text-cyan-200 mb-2 border border-white/15">
          <span>🏷️ Brand Identity Management</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
          Pengaturan Logo Website
        </h1>
        <p class="text-xs sm:text-sm text-cyan-100/80 mt-1 max-w-xl leading-relaxed">
          Ganti logo resmi website Diabetes Uji Coba tanpa mengubah kode. Logo akan otomatis tampil seragam pada Navbar, Sidebar Admin, Halaman Login, dan seluruh halaman website.
        </p>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 bg-white/15 hover:bg-white/25 text-white font-bold text-xs rounded-xl border border-white/20 transition-all flex items-center space-x-1.5 shadow-xs">
          <span>👁️ Cek di Beranda</span>
        </a>
      </div>
    </div>
  </div>

  <!-- Form Pengaturan Logo Card -->
  <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-8">
    
    <!-- Bagian 1: Status Logo Saat Ini -->
    <div class="pb-6 border-b border-slate-100">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-base font-extrabold text-[#123B5D]">1. Logo yang Sedang Aktif</h2>
          <p class="text-xs text-slate-500 mt-0.5">Logo ini yang sedang ditampilkan kepada pengunjung website.</p>
        </div>
        <div>
          @if(!empty($currentLogo))
            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-extrabold rounded-full border border-emerald-200 flex items-center space-x-1">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
              <span>Kustom Unggahan</span>
            </span>
          @else
            <span class="px-3 py-1 bg-sky-50 text-sky-700 text-xs font-extrabold rounded-full border border-sky-200 flex items-center space-x-1">
              <span>Logo Bawaan SVG</span>
            </span>
          @endif
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Tampilan di Latar Terang -->
        <div class="p-5 bg-slate-50/80 rounded-2xl border border-slate-200/70 flex flex-col items-center justify-center text-center">
          <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Tampilan di Latar Terang (Navbar/Admin)</span>
          <div class="h-16 flex items-center justify-center p-2 bg-white rounded-xl border border-slate-100 shadow-2xs w-full max-w-[260px]">
            <x-site-logo size="md" />
          </div>
        </div>

        <!-- Tampilan di Latar Gelap -->
        <div class="p-5 bg-gradient-to-r from-[#073b45] to-[#0a4e5c] rounded-2xl border border-teal-900/40 flex flex-col items-center justify-center text-center text-white">
          <span class="text-[11px] font-bold text-cyan-200/70 uppercase tracking-wider mb-3">Tampilan di Latar Gelap (Hero/Footer)</span>
          <div class="h-16 flex items-center justify-center p-2 bg-black/25 rounded-xl border border-white/10 w-full max-w-[260px]">
            <x-site-logo size="md" theme="dark" />
          </div>
        </div>
      </div>
    </div>

    <!-- Bagian 2: Unggah Logo Baru -->
    <form action="{{ route('admin.logo.update') }}" method="POST" enctype="multipart/form-data" id="logoUploadForm" class="space-y-6">
      @csrf

      <div>
        <h2 class="text-base font-extrabold text-[#123B5D]">2. Unggah Logo Baru</h2>
        <p class="text-xs text-slate-500 mt-0.5">Pilih berkas logo transparan dari komputer atau perangkat Anda.</p>
      </div>

      <!-- Area Drag & Drop / Pilih File -->
      <div 
        id="dropArea"
        onclick="document.getElementById('logoFileInput').click()"
        class="border-2 border-dashed border-slate-300 hover:border-[#00BFA6] bg-slate-50/50 hover:bg-emerald-50/30 rounded-2xl p-8 text-center cursor-pointer transition-all group"
      >
        <input 
          type="file" 
          name="logo_file" 
          id="logoFileInput" 
          accept=".png,.jpg,.jpeg,.svg" 
          class="hidden" 
          onchange="previewSelectedLogo(event)"
        >

        <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-100/60 text-[#00BFA6] flex items-center justify-center text-2xl group-hover:scale-110 transition-transform mb-3 shadow-xs">
          📁
        </div>

        <h3 class="text-sm font-bold text-slate-800">
          Klik untuk memilih berkas logo atau seret ke area ini
        </h3>
        <p class="text-xs text-slate-500 mt-1">
          Mendukung format: <strong class="text-[#123B5D]">PNG (disarankan transparan), SVG, JPG, JPEG</strong>
        </p>
        <p class="text-[11px] text-slate-400 mt-0.5">
          Maksimal ukuran file: <strong class="text-slate-600">2 MB (2048 KB)</strong>. Rasio proporsional persegi atau memanjang horizontal.
        </p>

        <div id="fileSelectedInfo" class="hidden mt-4 inline-flex items-center space-x-2 px-3.5 py-1.5 bg-emerald-100 text-emerald-800 rounded-xl text-xs font-bold border border-emerald-300">
          <span>📄</span>
          <span id="selectedFileName">Nama file</span>
          <span id="selectedFileSize" class="text-emerald-600 font-normal">(ukuran)</span>
        </div>
      </div>

      <!-- Bagian 3: Kotak Pratinjau Langsung Sebelum Disimpan -->
      <div id="previewContainer" class="hidden p-5 bg-gradient-to-r from-sky-50 to-emerald-50 rounded-2xl border border-sky-200/80 space-y-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-2">
            <span class="text-lg">✨</span>
            <h3 class="text-xs font-black uppercase tracking-wider text-[#123B5D]">Pratinjau Logo Baru Sebelum Disimpan</h3>
          </div>
          <button 
            type="button" 
            onclick="cancelSelectedLogo()" 
            class="text-xs text-rose-600 hover:text-rose-800 font-bold hover:underline cursor-pointer"
          >
            Batal Memilih
          </button>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-around gap-4 bg-white/90 p-4 rounded-xl border border-slate-200">
          <div class="text-center">
            <span class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Pratinjau Asli</span>
            <div class="w-24 h-24 p-2 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-center">
              <img id="liveLogoPreview" src="" alt="Pratinjau Logo" class="max-w-full max-h-full object-contain">
            </div>
          </div>

          <div class="text-center">
            <span class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Simulasi Navbar Header</span>
            <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-xs flex items-center space-x-2.5">
              <img id="liveLogoNavPreview" src="" alt="Nav Preview" class="w-10 h-10 object-contain">
              <div class="flex items-baseline space-x-1">
                <span class="text-xl font-extrabold text-[#123B5D]">Diabetes</span>
                <span class="text-xl font-extrabold text-[#168FE5] ml-1">Uji Coba</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pilihan Alternatif: Input URL Logo -->
      <div class="pt-2">
        <label class="block text-xs font-bold text-slate-700 mb-1">Atau masukkan URL Tautan Logo Langsung (Opsional)</label>
        <input 
          type="url" 
          name="logo_url" 
          placeholder="https://domain.com/path-to-logo.png" 
          class="w-full py-2.5 px-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6] focus:bg-white focus:ring-1 focus:ring-[#00BFA6]"
        >
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-100">
        <div class="flex items-center space-x-2">
          <button 
            type="submit" 
            class="px-6 py-3 bg-[#00BFA6] hover:bg-[#00a870] text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md shadow-[#00BFA6]/25 transition-all hover:scale-[1.01] active:scale-95 flex items-center space-x-2 cursor-pointer"
          >
            <span>💾</span>
            <span>Simpan Perubahan Logo</span>
          </button>

          <button 
            type="button" 
            onclick="cancelSelectedLogo()"
            class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm rounded-xl transition-all cursor-pointer"
          >
            Batal
          </button>
        </div>

        @if(!empty($currentLogo))
          <!-- Tombol Reset ke Logo Bawaan -->
          <button 
            type="button" 
            onclick="document.getElementById('resetLogoModal').classList.remove('hidden')" 
            class="px-4 py-3 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs sm:text-sm rounded-xl border border-rose-200 transition-all flex items-center space-x-1.5 cursor-pointer"
          >
            <span>🔄</span>
            <span>Kembalikan ke Logo Bawaan</span>
          </button>
        @endif
      </div>

    </form>

  </div>

  <!-- Petunjuk Praktis Format Logo -->
  <div class="bg-sky-50/70 rounded-2xl p-5 border border-sky-100 text-xs text-sky-900 space-y-2">
    <h4 class="font-extrabold flex items-center space-x-1.5 text-sky-950">
      <span>💡</span>
      <span>Tips Penggunaan Logo yang Optimal</span>
    </h4>
    <ul class="list-disc list-inside space-y-1 text-slate-600 leading-relaxed pl-1">
      <li>Gunakan format <strong>PNG dengan latar belakang transparan</strong> agar logo berpadu sempurna dengan latar putih navbar maupun latar gelap.</li>
      <li>Format <strong>SVG</strong> memberikan ketajaman resolusi terbaik di layar resolusi tinggi (Retina / Android AMOLED).</li>
      <li>Ukuran logo yang direkomendasikan minimal <strong>120x120 pixel</strong> atau rasio persegi/landscape rapi.</li>
      <li>Perubahan logo langsung disimpan permanen dan dapat dilihat secara instan di seluruh halaman tanpa restart server.</li>
    </ul>
  </div>

</div>

<!-- Modal Konfirmasi Reset Logo Bawaan -->
<div id="resetLogoModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
  <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4 text-center">
    <div class="w-14 h-14 mx-auto rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl">
      ⚠️
    </div>
    <h3 class="text-base font-extrabold text-slate-900">Kembalikan ke Logo Bawaan?</h3>
    <p class="text-xs text-slate-500 leading-relaxed">
      Logo kustom yang saat ini aktif akan dihapus dan website akan kembali menggunakan logo SVG bawaan sistem (Simbol Pita Hati & Medis).
    </p>
    <div class="flex items-center justify-center space-x-3 pt-2">
      <button 
        type="button" 
        onclick="document.getElementById('resetLogoModal').classList.add('hidden')" 
        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all cursor-pointer"
      >
        Batalkan
      </button>
      <form action="{{ route('admin.logo.reset') }}" method="POST">
        @csrf
        <button 
          type="submit" 
          class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md transition-all cursor-pointer"
        >
          Ya, Kembalikan Logo
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function previewSelectedLogo(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
      const file = input.files[0];

      // Validasi ukuran sisi klien (maksimal 2MB)
      if (file.size > 2 * 1024 * 1024) {
        alert("Ukuran file terlalu besar! Maksimal ukuran file logo adalah 2 MB (2048 KB).");
        input.value = '';
        cancelSelectedLogo();
        return;
      }

      // Validasi ekstensi yang diizinkan
      const ext = file.name.split('.').pop().toLowerCase();
      if (!['png', 'jpg', 'jpeg', 'svg'].includes(ext)) {
        alert("Format file tidak didukung! Harap pilih format PNG, JPG, JPEG, atau SVG.");
        input.value = '';
        cancelSelectedLogo();
        return;
      }

      // Tampilkan badge nama file terpilih
      document.getElementById('fileSelectedInfo').classList.remove('hidden');
      document.getElementById('selectedFileName').textContent = file.name;
      document.getElementById('selectedFileSize').textContent = `(${(file.size / 1024).toFixed(1)} KB)`;

      // Baca pratinjau instan dengan FileReader
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('liveLogoPreview').src = e.target.result;
        document.getElementById('liveLogoNavPreview').src = e.target.result;
        document.getElementById('previewContainer').classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    }
  }

  function cancelSelectedLogo() {
    const input = document.getElementById('logoFileInput');
    if (input) input.value = '';
    document.getElementById('fileSelectedInfo').classList.add('hidden');
    document.getElementById('previewContainer').classList.add('hidden');
    document.getElementById('liveLogoPreview').src = '';
    document.getElementById('liveLogoNavPreview').src = '';
  }
</script>
@endpush

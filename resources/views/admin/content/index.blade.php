@extends('layouts.admin')

@section('title', 'Pengaturan Hero & Konten Website (CMS) | Panel Administrator')
@section('header_title', 'Pengaturan Hero & Konten Website')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

  <!-- Header Banner -->
  <div class="bg-gradient-to-r from-[#123B5D] via-[#0f4469] to-[#0a4e5c] rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
    <div class="absolute -right-8 -bottom-8 w-60 h-60 bg-[#00BFA6]/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center space-x-1.5 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-bold text-cyan-200 mb-2 border border-white/15">
          <span>🎨 Central Content Management System</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
          Pengaturan Hero &amp; Konten Tampilan
        </h1>
        <p class="text-xs sm:text-sm text-cyan-100/80 mt-1 max-w-xl leading-relaxed">
          Ubah judul utama, subjudul, gambar latar belakang, tombol aksi, hingga disclaimer hasil pemeriksaan secara instan dan langsung aktif di Beranda.
        </p>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 bg-white/15 hover:bg-white/25 text-white font-bold text-xs rounded-xl border border-white/20 transition-all flex items-center space-x-1.5 shadow-xs">
          <span>👁️ Cek di Beranda</span>
        </a>
      </div>
    </div>
  </div>

  <!-- Main Card Container -->
  <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm space-y-6">

    <!-- Navigation Tabs -->
    <div class="flex flex-wrap items-center gap-2 p-1.5 bg-slate-100 rounded-2xl">
      <button 
        type="button" 
        onclick="switchCmsTab('hero')" 
        id="tabBtnHero"
        class="flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold transition-all bg-white text-[#123B5D] shadow-sm cursor-pointer text-center"
      >
        🌟 1. Banner Hero Beranda
      </button>
      <button 
        type="button" 
        onclick="switchCmsTab('beranda')" 
        id="tabBtnBeranda"
        class="flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold transition-all text-slate-500 hover:text-slate-800 cursor-pointer text-center"
      >
        🏠 2. Bagian Pemicu &amp; 5 Pilar
      </button>
      <button 
        type="button" 
        onclick="switchCmsTab('identitas')" 
        id="tabBtnIdentitas"
        class="flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold transition-all text-slate-500 hover:text-slate-800 cursor-pointer text-center"
      >
        🌐 3. Identitas &amp; Footer
      </button>
      <button 
        type="button" 
        onclick="switchCmsTab('skrining')" 
        id="tabBtnSkrining"
        class="flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold transition-all text-slate-500 hover:text-slate-800 cursor-pointer text-center"
      >
        🩺 4. Kuesioner &amp; Hasil Analisis
      </button>
    </div>

    <form action="{{ route('admin.content.update') }}" method="POST" enctype="multipart/form-data" id="cmsContentForm" class="space-y-8">
      @csrf

      <!-- ============================================================ -->
      <!-- TAB 1: PENGATURAN HERO BERANDA (DENGAN PRATINJAU LANGSUNG)   -->
      <!-- ============================================================ -->
      <div id="tabContentHero" class="space-y-6">
        
        <!-- Interactive Live Hero Preview Box -->
        <div class="space-y-2">
          <div class="flex items-center justify-between">
            <h3 class="text-xs font-black uppercase tracking-wider text-[#123B5D] flex items-center space-x-1.5">
              <span>👁️</span>
              <span>Pratinjau Langsung Hero Beranda (Live Preview)</span>
            </h3>
            <span class="text-[11px] text-slate-400">Pratinjau otomatis berubah saat Anda mengedit</span>
          </div>

          @php
            $currentHeroBg = \App\Models\SiteContent::get('hero_bg_image');
            $heroBgDisplay = !empty($currentHeroBg) ? $currentHeroBg : asset('assets/hero_hospital.jpg');
            $currentOverlay = (int)\App\Models\SiteContent::get('hero_overlay_opacity', '85');
          @endphp

          <div 
            id="heroPreviewContainer" 
            class="relative rounded-2xl overflow-hidden p-6 sm:p-8 min-h-[260px] flex flex-col justify-center border border-slate-300 shadow-md transition-all"
            style="background-image: url('{{ $heroBgDisplay }}'); background-size: cover; background-position: center;"
          >
            <!-- Overlay Gelap Dinamis -->
            <div 
              id="heroPreviewOverlay" 
              class="absolute inset-0 bg-gradient-to-r from-[#073b45] via-[#0a4e5c] to-[#0e6170] pointer-events-none transition-opacity duration-200"
              style="opacity: {{ $currentOverlay / 100 }};"
            ></div>

            <div class="relative z-10 max-w-xl text-white space-y-3">
              <!-- Pill Tag Preview -->
              <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-[11px] font-bold text-cyan-200 border border-cyan-400/30">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span>
                <span id="previewPill">{{ \App\Models\SiteContent::get('hero_pill', 'Deteksi Dini Diabetes Uji Coba') }}</span>
              </div>

              <!-- Title Preview -->
              <h2 id="previewTitle" class="text-xl sm:text-2xl lg:text-3xl font-black text-white leading-tight tracking-tight drop-shadow-sm whitespace-pre-line">
                {{ \App\Models\SiteContent::get('hero_title', "Ketahui Risiko Diabetes\nAnda Sejak Dini") }}
              </h2>

              <!-- Description Preview -->
              <p id="previewDesc" class="text-xs sm:text-sm text-cyan-50/90 leading-relaxed font-normal">
                {{ \App\Models\SiteContent::get('hero_description', 'Skrining risiko diabetes mandiri dalam 1 menit menggunakan algoritma Machine Learning XGBoost tervalidasi CDC BRFSS.') }}
              </p>

              <!-- Buttons Preview -->
              <div class="flex flex-wrap items-center gap-2.5 pt-1">
                <span id="previewCta1" class="px-4 py-2 bg-[#00BFA6] text-white font-extrabold text-xs rounded-xl shadow-xs">
                  {{ \App\Models\SiteContent::get('hero_cta_text', 'Mulai Cek Risiko Sekarang') }} &rarr;
                </span>
                <span id="previewCta2" class="px-4 py-2 bg-[#073943]/80 text-white font-semibold text-xs rounded-xl border border-[#43B4F2]/30 backdrop-blur-md">
                  {{ \App\Models\SiteContent::get('hero_cta2_text', 'Explore 5 Pilar ↓') }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Inputs Group: Teks & Headline Hero -->
        <div class="p-5 sm:p-6 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-5">
          <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div>
              <h3 class="text-sm font-extrabold text-[#123B5D]">Pengaturan Teks &amp; Headline Hero</h3>
              <p class="text-[11px] text-slate-500">Sesuaikan kata sambutan dan penjelasan yang tampil di bagian teratas Beranda.</p>
            </div>
          </div>

          <!-- Pill Tag -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Teks Pill Tag Kecil (Di Atas Headline)</label>
            <input 
              type="text" 
              name="contents[hero_pill]" 
              id="inputHeroPill"
              value="{{ \App\Models\SiteContent::get('hero_pill', 'Deteksi Dini Diabetes Uji Coba') }}" 
              class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6] focus:ring-1 focus:ring-[#00BFA6]"
              oninput="document.getElementById('previewPill').textContent = this.value || 'Pill Tag'"
            >
          </div>

          <!-- Headline H1 -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Headline Utama (H1) — Gunakan Enter/baris baru untuk memisahkan baris teks</label>
            <textarea 
              name="contents[hero_title]" 
              id="inputHeroTitle"
              rows="2" 
              class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800 outline-none focus:border-[#00BFA6] focus:ring-1 focus:ring-[#00BFA6]"
              oninput="document.getElementById('previewTitle').textContent = this.value || 'Judul Utama Hero'"
            >{{ \App\Models\SiteContent::get('hero_title', "Ketahui Risiko Diabetes\nAnda Sejak Dini") }}</textarea>
          </div>

          <!-- Hero Description -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Subjudul Hero Banner</label>
            <textarea 
              name="contents[hero_description]" 
              id="inputHeroDesc"
              rows="3" 
              class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 outline-none focus:border-[#00BFA6] focus:ring-1 focus:ring-[#00BFA6]"
              oninput="document.getElementById('previewDesc').textContent = this.value || 'Deskripsi singkat hero'"
            >{{ \App\Models\SiteContent::get('hero_description', 'Skrining risiko diabetes mandiri dalam 1 menit menggunakan algoritma Machine Learning XGBoost tervalidasi CDC BRFSS.') }}</textarea>
          </div>
        </div>

        <!-- Inputs Group: Gambar Background & Efek Overlay -->
        <div class="p-5 sm:p-6 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-5">
          <div class="pb-3 border-b border-slate-200">
            <h3 class="text-sm font-extrabold text-[#123B5D]">Gambar Latar Belakang (Background Hero) &amp; Overlay</h3>
            <p class="text-[11px] text-slate-500">Unggah foto latar rumah sakit/kesehatan kustom atau tentukan URL gambar dan tingkat kegelapan overlay.</p>
          </div>

          <!-- File Upload Background -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Unggah Foto Background Baru (JPG, PNG, WEBP, Maks 5MB)</label>
            <div class="flex flex-col sm:flex-row items-center gap-3">
              <input 
                type="file" 
                name="hero_bg_file" 
                id="heroBgFileInput"
                accept=".jpg,.jpeg,.png,.webp"
                class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-[#123B5D] file:text-white hover:file:bg-[#00BFA6] file:cursor-pointer cursor-pointer bg-white border border-slate-200 rounded-xl"
                onchange="previewHeroBgFile(event)"
              >
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Disarankan foto lanskap horizontal resolusi tinggi (1920x1080 atau 2560x1440).</p>
          </div>

          <!-- Input URL Gambar Alternatif -->
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Atau Gunakan Tautan URL Gambar Langsung</label>
            <input 
              type="text" 
              name="contents[hero_bg_image]" 
              id="inputHeroBgUrl"
              value="{{ \App\Models\SiteContent::get('hero_bg_image', '') }}" 
              placeholder="Kosongkan untuk menggunakan gambar rumah sakit bawaan website" 
              class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6]"
              oninput="if(this.value) { document.getElementById('heroPreviewContainer').style.backgroundImage = `url('${this.value}')`; }"
            >
          </div>

          <!-- Overlay Darkness Slider -->
          <div>
            <div class="flex items-center justify-between mb-1">
              <label class="text-xs font-bold text-slate-700">Tingkat Kegelapan Overlay Teal / Kontras Teks (0% - 100%)</label>
              <span id="overlayValueBadge" class="px-2 py-0.5 bg-cyan-100 text-[#123B5D] text-xs font-extrabold rounded-md">
                {{ $currentOverlay }}%
              </span>
            </div>
            <input 
              type="range" 
              name="contents[hero_overlay_opacity]" 
              id="inputHeroOverlay"
              min="20" 
              max="100" 
              value="{{ $currentOverlay }}" 
              class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[#00BFA6]"
              oninput="updateHeroOverlayPreview(this.value)"
            >
            <p class="text-[11px] text-slate-400 mt-1">Nilai lebih tinggi (80-90%) membuat teks putih terbaca sangat kontras dan jelas.</p>
          </div>
        </div>

        <!-- Inputs Group: Tombol Aksi (CTA) & Tautan Tujuan -->
        <div class="p-5 sm:p-6 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-4">
          <div class="pb-3 border-b border-slate-200">
            <h3 class="text-sm font-extrabold text-[#123B5D]">Tombol Aksi Hero &amp; Tautan Tujuan</h3>
            <p class="text-[11px] text-slate-500">Tentukan teks label tombol dan alamat link URL tujuan saat tombol diklik.</p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- CTA 1 -->
            <div class="space-y-3 p-4 bg-white rounded-xl border border-slate-200">
              <span class="text-[10px] font-black uppercase text-emerald-700 tracking-wider">Tombol Aksi Utama (Hijau Toska)</span>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Teks Tombol Utama</label>
                <input 
                  type="text" 
                  name="contents[hero_cta_text]" 
                  value="{{ \App\Models\SiteContent::get('hero_cta_text', 'Mulai Cek Risiko Sekarang') }}" 
                  class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6]"
                  oninput="document.getElementById('previewCta1').textContent = this.value + ' →'"
                >
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tautan / Link Tujuan (Default: /cek-risiko)</label>
                <input 
                  type="text" 
                  name="contents[hero_cta_url]" 
                  value="{{ \App\Models\SiteContent::get('hero_cta_url', route('quiz')) }}" 
                  class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 outline-none focus:border-[#00BFA6]"
                >
              </div>
            </div>

            <!-- CTA 2 -->
            <div class="space-y-3 p-4 bg-white rounded-xl border border-slate-200">
              <span class="text-[10px] font-black uppercase text-[#168FE5] tracking-wider">Tombol Aksi Kedua (Transparan / Pilar)</span>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Teks Tombol Kedua</label>
                <input 
                  type="text" 
                  name="contents[hero_cta2_text]" 
                  value="{{ \App\Models\SiteContent::get('hero_cta2_text', 'Explore 5 Pilar ↓') }}" 
                  class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6]"
                  oninput="document.getElementById('previewCta2').textContent = this.value"
                >
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tautan / Link Tujuan (Default: #sectionPilar)</label>
                <input 
                  type="text" 
                  name="contents[hero_cta2_url]" 
                  value="{{ \App\Models\SiteContent::get('hero_cta2_url', '#sectionPilar') }}" 
                  class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 outline-none focus:border-[#00BFA6]"
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Hero Action Toolbar -->
        <div class="pt-4 border-t border-slate-200 flex flex-wrap items-center justify-between gap-3">
          <div class="flex items-center space-x-2">
            <button 
              type="submit" 
              class="px-6 py-3 bg-[#00BFA6] hover:bg-[#00a870] text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md shadow-[#00BFA6]/25 transition-all hover:scale-[1.01] active:scale-95 flex items-center space-x-2 cursor-pointer"
            >
              <span>💾</span>
              <span>Simpan Perubahan Hero</span>
            </button>
            <a 
              href="{{ route('admin.content.index') }}" 
              class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm rounded-xl transition-all"
            >
              Batal
            </a>
          </div>

          <button 
            type="button" 
            onclick="document.getElementById('resetHeroModal').classList.remove('hidden')" 
            class="px-4 py-3 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs sm:text-sm rounded-xl border border-amber-200 transition-all flex items-center space-x-1.5 cursor-pointer"
          >
            <span>🔄</span>
            <span>Kembalikan Hero ke Default</span>
          </button>
        </div>

      </div>

      <!-- ============================================================ -->
      <!-- TAB 2: BAGIAN PEMICU & 5 PILAR                               -->
      <!-- ============================================================ -->
      <div id="tabContentBeranda" class="space-y-6 hidden">
        
        <!-- Pemicu & Faktor Risiko -->
        <div class="p-5 sm:p-6 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-4">
          <div class="flex items-center space-x-2 pb-3 border-b border-slate-200">
            <span class="text-xl">⚖️</span>
            <div>
              <h3 class="text-sm font-extrabold text-[#123B5D]">Faktor Risiko &amp; Pemicu Diabetes</h3>
              <p class="text-[11px] text-slate-500">Judul, subjudul, dan catatan edukasi medis terkait pemicu metabolik &amp; genetik.</p>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Judul Bagian Pemicu</label>
            <input 
              type="text" 
              name="contents[pemicu_title]" 
              value="{{ \App\Models\SiteContent::get('pemicu_title', 'Faktor Risiko & Pemicu Diabetes Melitus') }}" 
              class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6]"
            >
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Subjudul Penjelasan Pemicu</label>
            <textarea 
              name="contents[pemicu_subtitle]" 
              rows="2" 
              class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 outline-none focus:border-[#00BFA6]"
            >{{ \App\Models\SiteContent::get('pemicu_subtitle', 'Memahami pemicu membantu mendeteksi risiko sejak dini. Risiko diabetes dipengaruhi oleh kombinasi faktor gaya hidup (dapat diubah) dan faktor biologis (tidak dapat diubah).') }}</textarea>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Edukasi / Disclaimer Bagian Pemicu</label>
            <textarea 
              name="contents[pemicu_note]" 
              rows="3" 
              class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 outline-none focus:border-[#00BFA6]"
            >{{ \App\Models\SiteContent::get('pemicu_note', 'Adanya satu atau lebih faktor pemicu di atas bukan berarti Anda pasti terkena diabetes. Faktor tersebut merupakan indikator probabilitas. Pencegahan melalui kebiasaan sehat harian terbukti secara klinis mampu menurunkan risiko hingga lebih dari 58%.') }}</textarea>
          </div>
        </div>

        <!-- 5 Pilar Utama -->
        <div class="p-5 sm:p-6 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-4">
          <div class="flex items-center space-x-2 pb-3 border-b border-slate-200">
            <span class="text-xl">🏛️</span>
            <div>
              <h3 class="text-sm font-extrabold text-[#123B5D]">5 Pilar Pengendalian &amp; Pencegahan</h3>
              <p class="text-[11px] text-slate-500">Judul, pengantar, dan penegasan medis terkait 5 pilar hidup sehat.</p>
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Judul Bagian 5 Pilar</label>
            <input 
              type="text" 
              name="contents[pilar_title]" 
              value="{{ \App\Models\SiteContent::get('pilar_title', '5 Pilar Utama Pengendalian & Pencegahan Diabetes') }}" 
              class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6]"
            >
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Subjudul / Arahan Pengunjung</label>
            <textarea 
              name="contents[pilar_subtitle]" 
              rows="2" 
              class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 outline-none focus:border-[#00BFA6]"
            >{{ \App\Models\SiteContent::get('pilar_subtitle', 'Pilih salah satu pilar untuk mencoba alat interaktif dan panduan ringkasnya.') }}</textarea>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Disclaimer Medis Bagian Pilar</label>
            <textarea 
              name="contents[pilar_disclaimer]" 
              rows="3" 
              class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 outline-none focus:border-[#00BFA6]"
            >{{ \App\Models\SiteContent::get('pilar_disclaimer', 'Penerapan 5 pilar kebiasaan sehat di atas terbukti klinis dapat menurunkan risiko diabetes dan komplikasi metabolik secara signifikan. Langkah ini adalah ikhtiar pencegahan dan bukan jaminan mutlak 100% bebas diabetes, karena faktor keturunan dan biologis tetap memiliki peranan. Konsultasikan status kesehatan Anda secara teratur kepada tenaga medis.') }}</textarea>
          </div>
        </div>

        <div class="pt-4 border-t border-slate-200">
          <button 
            type="submit" 
            class="px-6 py-3 bg-[#00BFA6] hover:bg-[#00a870] text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md transition-all cursor-pointer"
          >
            💾 Simpan Bagian Pemicu &amp; Pilar
          </button>
        </div>
      </div>

      <!-- ============================================================ -->
      <!-- TAB 3: IDENTITAS PLATFORM & FOOTER                           -->
      <!-- ============================================================ -->
      <div id="tabContentIdentitas" class="space-y-6 hidden">
        <div class="p-5 sm:p-6 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-4">
          <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div>
              <h3 class="text-sm font-extrabold text-[#123B5D]">Identitas Website &amp; Informasi Footer</h3>
              <p class="text-[11px] text-slate-500">Nama sistem, tagline, teks hak cipta, dan kontak yang muncul di seluruh halaman.</p>
            </div>
            <a href="{{ route('admin.logo.index') }}" class="px-3 py-1.5 bg-white text-[#123B5D] hover:text-[#00BFA6] border border-slate-200 rounded-xl text-xs font-bold transition-all flex items-center space-x-1">
              <span>🏷️ Kelola Logo Khusus</span>
            </a>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Nama Website / Brand</label>
              <input 
                type="text" 
                name="contents[site_name]" 
                value="{{ \App\Models\SiteContent::get('site_name', 'Diabetes Uji Coba') }}" 
                class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6]"
              >
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Slogan / Tagline Website</label>
              <input 
                type="text" 
                name="contents[site_tagline]" 
                value="{{ \App\Models\SiteContent::get('site_tagline', 'Deteksi Dini Diabetes, Jaga Kesehatan Anda') }}" 
                class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6]"
              >
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat di Footer</label>
            <textarea 
              name="contents[footer_desc]" 
              rows="2" 
              class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 outline-none focus:border-[#00BFA6]"
            >{{ \App\Models\SiteContent::get('footer_desc', 'Platform edukasi dan skrining dini diabetes mandiri berbasis algoritma Machine Learning XGBoost.') }}</textarea>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Teks Hak Cipta / Copyright Footer</label>
            <input 
              type="text" 
              name="contents[footer_copyright]" 
              value="{{ \App\Models\SiteContent::get('footer_copyright', 'Diabetes Uji Coba • Sistem Skrining Dini Diabetes Berbasis Machine Learning XGBoost.') }}" 
              class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6]"
            >
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Email Layanan &amp; Bantuan Pengguna</label>
              <input 
                type="email" 
                name="contents[contact_email]" 
                value="{{ \App\Models\SiteContent::get('contact_email', 'bantuan@diabetesujicoba.com') }}" 
                class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6]"
              >
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-700 mb-1">Nomor Kontak / WhatsApp Layanan</label>
              <input 
                type="text" 
                name="contents[contact_phone]" 
                value="{{ \App\Models\SiteContent::get('contact_phone', '0812-3456-7890') }}" 
                class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6]"
              >
            </div>
          </div>
        </div>

        <div class="pt-4 border-t border-slate-200">
          <button 
            type="submit" 
            class="px-6 py-3 bg-[#00BFA6] hover:bg-[#00a870] text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md transition-all cursor-pointer"
          >
            💾 Simpan Identitas &amp; Kontak
          </button>
        </div>
      </div>

      <!-- ============================================================ -->
      <!-- TAB 4: KUESIONER & HASIL ANALISIS                            -->
      <!-- ============================================================ -->
      <div id="tabContentSkrining" class="space-y-6 hidden">
        <div class="p-5 sm:p-6 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-4">
          <div class="pb-3 border-b border-slate-200">
            <h3 class="text-sm font-extrabold text-[#123B5D]">Teks Pengantar Kuesioner &amp; Disclaimer Analisis</h3>
            <p class="text-[11px] text-slate-500">Teks pembimbing saat pengguna mengisi 8 parameter klinis dan pesan kehati-hatian medis pada lembar hasil.</p>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Subjudul Kuesioner Skrining</label>
            <input 
              type="text" 
              name="contents[quiz_subtitle]" 
              value="{{ \App\Models\SiteContent::get('quiz_subtitle', 'Jawab 8 pertanyaan klinis berikut secara jujur untuk mendapatkan estimasi risiko diabetes mandiri.') }}" 
              class="w-full py-2.5 px-3.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:border-[#00BFA6]"
            >
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Disclaimer Medis di Halaman Hasil</label>
            <textarea 
              name="contents[result_disclaimer]" 
              rows="3" 
              class="w-full p-3 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-800 outline-none focus:border-[#00BFA6]"
            >{{ \App\Models\SiteContent::get('result_disclaimer', 'Hasil analisis ini merupakan estimasi skrining berbasis data statistik probabilitas XGBoost, bukan diagnosis medis final. Disarankan untuk berkonsultasi langsung dengan dokter spesialis atau fasilitas kesehatan terdekat.') }}</textarea>
          </div>
        </div>

        <div class="pt-4 border-t border-slate-200">
          <button 
            type="submit" 
            class="px-6 py-3 bg-[#00BFA6] hover:bg-[#00a870] text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md transition-all cursor-pointer"
          >
            💾 Simpan Kuesioner &amp; Disclaimer
          </button>
        </div>
      </div>

    </form>
  </div>

</div>

<!-- Modal Konfirmasi Reset Hero ke Default -->
<div id="resetHeroModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
  <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 space-y-4 text-center">
    <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl">
      ⚠️
    </div>
    <h3 class="text-base font-extrabold text-slate-900">Kembalikan Hero ke Pengaturan Default?</h3>
    <p class="text-xs text-slate-500 leading-relaxed">
      Judul, subjudul, gambar latar belakang rumah sakit, teks tombol, dan tautan hero akan dikembalikan ke setelan awal sistem.
    </p>
    <div class="flex items-center justify-center space-x-3 pt-2">
      <button 
        type="button" 
        onclick="document.getElementById('resetHeroModal').classList.add('hidden')" 
        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all cursor-pointer"
      >
        Batalkan
      </button>
      <form action="{{ route('admin.hero.reset') }}" method="POST">
        @csrf
        <button 
          type="submit" 
          class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-md transition-all cursor-pointer"
        >
          Ya, Kembalikan Hero
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function switchCmsTab(tab) {
    const tabs = ['hero', 'beranda', 'identitas', 'skrining'];
    tabs.forEach(t => {
      const content = document.getElementById(`tabContent${t.charAt(0).toUpperCase() + t.slice(1)}`);
      const btn = document.getElementById(`tabBtn${t.charAt(0).toUpperCase() + t.slice(1)}`);
      
      if (t === tab) {
        if (content) content.classList.remove('hidden');
        if (btn) {
          btn.className = 'flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold transition-all bg-white text-[#123B5D] shadow-sm cursor-pointer text-center';
        }
      } else {
        if (content) content.classList.add('hidden');
        if (btn) {
          btn.className = 'flex-1 min-w-[140px] py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold transition-all text-slate-500 hover:text-slate-800 cursor-pointer text-center';
        }
      }
    });
  }

  function previewHeroBgFile(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
      const file = input.files[0];
      if (file.size > 5 * 1024 * 1024) {
        alert("Ukuran gambar terlalu besar! Maksimal 5 MB.");
        input.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('heroPreviewContainer').style.backgroundImage = `url('${e.target.result}')`;
      };
      reader.readAsDataURL(file);
    }
  }

  function updateHeroOverlayPreview(val) {
    document.getElementById('overlayValueBadge').textContent = val + '%';
    const overlay = document.getElementById('heroPreviewOverlay');
    if (overlay) {
      overlay.style.opacity = (val / 100);
    }
  }
</script>
@endpush

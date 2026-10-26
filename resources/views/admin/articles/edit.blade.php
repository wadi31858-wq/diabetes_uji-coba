@extends('layouts.admin')

@section('title', 'Edit Berita: ' . $article->title . ' | Panel Admin Diabetes Uji Coba')
@section('header_title', 'Edit Berita')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

  <!-- Breadcrumb -->
  <div class="flex items-center space-x-2 text-xs text-slate-500">
    <a href="{{ route('admin.dashboard') }}" class="hover:underline">Admin</a>
    <span>/</span>
    <a href="{{ route('admin.articles.index') }}" class="hover:underline">Kelola Berita</a>
    <span>/</span>
    <span class="text-slate-900 font-semibold">Edit Artikel #{{ $article->id }}</span>
  </div>

    <!-- Title Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm">
      <div class="flex items-center justify-between pb-5 border-b border-gray-100 mb-6">
        <div>
          <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">
            Edit Artikel Berita
          </h1>
          <p class="text-xs text-gray-400 mt-0.5">
            Perbarui data judul, kategori topik, upload gambar artikel, ringkasan, dan konten lengkap.
          </p>
        </div>
        <div class="flex items-center space-x-2">
          <a href="{{ route('articles.detail', $article->slug) }}" target="_blank" class="text-xs font-bold text-emerald-600 hover:underline">
            Lihat di Website &rarr;
          </a>
          <span class="text-gray-300">|</span>
          <a href="{{ route('admin.articles.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 transition-colors">
            Kembali
          </a>
        </div>
      </div>

      @if($errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800">
          <strong class="font-bold block mb-1">Terdapat kesalahan pengisian:</strong>
          <ul class="list-disc pl-5 space-y-0.5">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form id="editArticleForm" action="{{ route('admin.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Judul Artikel -->
        <div>
          <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-1.5">
            Judul Artikel <span class="text-rose-500">*</span>
          </label>
          <input 
            type="text" 
            name="title" 
            value="{{ old('title', $article->title) }}" 
            required
            class="w-full py-3 px-4 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-900 outline-none focus:bg-white focus:border-[#00a870] focus:ring-1 focus:ring-[#00a870]"
          >
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Kategori: Rol Pemilihan (Select Dropdown) -->
          <div>
            <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-1.5">
              Kategori Artikel (Rol Pemilihan) <span class="text-rose-500">*</span>
            </label>
            @php
              $currentCat = old('category', $article->category);
              $categoryOptions = [
                'Diabetes' => 'Diabetes & Gula Darah',
                'Nutrisi' => 'Nutrisi & Pola Makan Sehat',
                'Aktivitas' => 'Gaya Hidup & Olahraga',
                'Edukasi Medis' => 'Edukasi Medis & Panduan Klinis',
                'Deteksi Dini' => 'Deteksi Dini & Skrining',
                'Komplikasi Medis' => 'Komplikasi & Perawatan Kaki',
                'Pencegahan' => 'Pencegahan Diabetes',
                'Kesehatan Holistik' => 'Kesehatan Holistik & Kebugaran',
                'Umum' => 'Umum / Lainnya'
              ];
            @endphp
            <div class="relative">
              <select 
                name="category" 
                required
                class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-900 outline-none focus:bg-white focus:border-[#00a870] appearance-none cursor-pointer pr-10"
              >
                <option value="">-- Pilih Kategori Artikel --</option>
                @foreach($categoryOptions as $catVal => $catLabel)
                  <option value="{{ $catVal }}" {{ (strcasecmp($currentCat, $catVal) === 0 || strcasecmp($currentCat, $catLabel) === 0) ? 'selected' : '' }}>
                    {{ $catLabel }}
                  </option>
                @endforeach
                @if(!empty($currentCat) && !array_key_exists($currentCat, $categoryOptions) && !in_array($currentCat, $categoryOptions))
                  <option value="{{ $currentCat }}" selected>{{ $currentCat }} (Kustom)</option>
                @endif
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </div>
            </div>
            <p class="text-[11px] text-gray-400 mt-1">Pilih rol kategori yang sesuai dengan topik bahasan.</p>
          </div>

          <!-- Penulis / Sumber -->
          <div>
            <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-1.5">
              Penulis / Narasumber Medis
            </label>
            <input 
              type="text" 
              name="author" 
              value="{{ old('author', $article->author) }}" 
              placeholder="Contoh: dr. Spesialis Penyakit Dalam / Tim Medis"
              class="w-full py-2.5 px-3.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-900 outline-none focus:bg-white focus:border-[#00a870]"
            >
            <p class="text-[11px] text-gray-400 mt-1">Identitas dokter, pakar, atau tim redaksi medis.</p>
          </div>
        </div>

        <!-- Bagian Gambar: Input File & Preview -->
        <div class="p-4 sm:p-5 bg-gray-50 rounded-2xl border border-gray-200 space-y-3">
          <div class="flex items-center justify-between">
            <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-800">
              🖼️ Gambar Header / Sampul Artikel
            </label>
            <span class="text-[11px] text-gray-400 font-medium">Bisa Upload File atau Pakai URL</span>
          </div>

          <!-- Input File Upload Gambar -->
          <div>
            <label class="block text-[11px] font-bold text-gray-600 mb-1">
              Upload Gambar dari Perangkat (Input File):
            </label>
            <input 
              type="file" 
              name="image_file" 
              id="imageFileInput"
              accept="image/*"
              class="w-full text-xs text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#00a870] file:text-white hover:file:bg-[#008f5d] file:cursor-pointer cursor-pointer border border-gray-200 rounded-xl bg-white p-1.5"
              onchange="previewArticleImage(this)"
            >
            <p class="text-[10px] text-gray-400 mt-1">Format didukung: JPG, JPEG, PNG, WEBP, GIF (Maks. 10 MB).</p>
          </div>

          <!-- Input Alternatif URL Gambar -->
          <div>
            <label class="block text-[11px] font-bold text-gray-600 mb-1">
              Atau Gunakan Tautan URL Gambar Eksternal:
            </label>
            <input 
              type="text" 
              name="image" 
              id="imageUrlInput"
              value="{{ old('image', $article->image) }}" 
              placeholder="https://images.unsplash.com/..."
              class="w-full py-2 px-3 bg-white border border-gray-200 rounded-xl text-xs font-medium text-gray-700 outline-none focus:border-[#00a870]"
            >
          </div>

          <!-- Pratinjau Gambar -->
          <div id="imagePreviewContainer" class="mt-2 flex items-center space-x-3 p-2.5 bg-white rounded-xl border border-gray-100">
            <img 
              id="articlePreviewImg"
              src="{{ $article->image ?: 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=800' }}" 
              alt="Preview Gambar" 
              class="w-24 h-16 object-cover rounded-lg border border-gray-200 shadow-xs"
            >
            <div>
              <p class="text-xs font-bold text-gray-800" id="imagePreviewStatus">Pratinjau Gambar Aktif</p>
              <p class="text-[10px] text-gray-400">Gambar ini yang akan tampil di halaman depan dan detail artikel.</p>
            </div>
          </div>
        </div>

        <!-- Ringkasan Singkat / Lead Paragraph -->
        <div>
          <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-1.5">
            Ringkasan Singkat (Lead di Kartu Depan) <span class="text-rose-500">*</span>
          </label>
          <textarea 
            name="summary" 
            rows="2" 
            required
            class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 outline-none focus:bg-white focus:border-[#00a870]"
          >{{ old('summary', $article->summary) }}</textarea>
        </div>

        @php
          $structured = $article->structured_data;
          $oldIntroTitle = old('intro_title', $article->intro_title ?? ($structured['intro_title'] ?? ''));
          $oldIntro = old('intro', $structured['intro'] ?? '');
          $oldConclusionTitle = old('conclusion_title', $article->conclusion_title ?? ($structured['conclusion_title'] ?? ''));
          $oldConclusion = old('conclusion', $structured['conclusion'] ?? '');
          $tagsString = is_array($structured['tags'] ?? null) ? implode(', ', $structured['tags']) : ($structured['tags'] ?? 'Gaya Hidup, Pola Hidup Sehat');
          $refsString = is_array($structured['references'] ?? null) ? implode("\n", $structured['references']) : ($structured['references'] ?? '');
        @endphp

        <!-- Paragraf Pengantar / Pembuka Artikel (Sub Pembuka dengan Fitur Judul) -->
        <div class="p-4 sm:p-5 bg-blue-50/40 rounded-2xl border border-blue-200/80 space-y-3">
          <div class="flex items-center space-x-2">
            <span class="text-base">💬</span>
            <label class="block text-xs sm:text-sm font-extrabold uppercase tracking-wider text-blue-950">
              Sub Pembuka / Pengantar Artikel
            </label>
          </div>

          <!-- Fitur Judul Sub Pembuka -->
          <div>
            <label class="block text-[11px] font-bold text-blue-900 mb-1">
              Judul Sub Pembuka (Fitur Judul):
            </label>
            <input 
              type="text" 
              name="intro_title" 
              value="{{ $oldIntroTitle }}" 
              placeholder="Contoh: Pengantar & Latar Belakang Medis"
              class="w-full py-2 px-3.5 bg-white border border-blue-200 rounded-xl text-xs sm:text-sm font-bold text-gray-900 outline-none focus:border-[#0085d0] focus:ring-1 focus:ring-[#0085d0]"
            >
            <p class="text-[10px] text-gray-400 mt-0.5">Judul sub-bagian pembuka yang tampil sebelum paragraf pengantar artikel.</p>
          </div>

          <!-- Teks Paragraf Pembuka -->
          <div>
            <label class="block text-[11px] font-bold text-blue-900 mb-1">
              Isi Paragraf Pembuka (Teks Biasa):
            </label>
            <textarea 
              name="intro" 
              rows="3" 
              placeholder="Contoh: Diabetes melitus adalah penyakit kronis yang ditandai dengan tingginya kadar gula darah..."
              class="w-full p-3 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-800 leading-relaxed outline-none focus:border-[#0085d0]"
            >{{ $oldIntro }}</textarea>
            <p class="text-[10px] text-gray-400 mt-0.5">Tuliskan kalimat pengantar awal artikel tanpa kode HTML.</p>
          </div>
        </div>

        <!-- Isi Lengkap Konten Artikel: Nomer, Judul, Penjelasan & File (Tanpa Kode HTML) -->
        <div class="p-4 sm:p-5 bg-emerald-50/40 rounded-2xl border border-emerald-200 space-y-4">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-emerald-200/70">
            <div>
              <div class="flex items-center space-x-2">
                <span class="text-base">📝</span>
                <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wider text-emerald-950">
                  Isi Lengkap Penjelasan Artikel (Tanpa Kode HTML)
                </h2>
              </div>
              <p class="text-xs text-gray-600 mt-0.5">
                Ketik nomor, judul poin, penjelasan biasa, serta input file/foto jika ada. Gunakan tanda <code class="bg-white px-1 rounded font-bold text-emerald-800">-</code> atau <code class="bg-white px-1 rounded font-bold text-emerald-800">•</code> untuk membuat sub-bullet rapi.
              </p>
            </div>
            <button 
              type="button" 
              onclick="addContentPointRow()" 
              class="inline-flex items-center space-x-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-extrabold text-xs rounded-xl shadow-xs transition-all cursor-pointer self-start sm:self-auto shrink-0"
            >
              <span>+</span>
              <span>Tambah Poin Baru</span>
            </button>
          </div>

          <!-- Container Poin-Poin Konten -->
          <div id="content-points-container" class="space-y-3.5">
            <!-- Dynamic rows will be inserted here -->
          </div>

          <div class="pt-1 flex items-center justify-between text-[11px] text-gray-500">
            <span>💡 Ketik teks penjelasan biasa. Tanda "-" otomatis jadi bullet rapi seperti di majalah kesehatan.</span>
            <button 
              type="button" 
              onclick="addContentPointRow()" 
              class="text-emerald-700 font-bold hover:underline cursor-pointer"
            >
              + Tambah Poin Baru
            </button>
          </div>
        </div>

        <!-- Paragraf Penutup / Kesimpulan Artikel (Sub Penutup dengan Fitur Judul) -->
        <div class="p-4 sm:p-5 bg-teal-50/40 rounded-2xl border border-teal-200/80 space-y-3">
          <div class="flex items-center space-x-2">
            <span class="text-base">🏁</span>
            <label class="block text-xs sm:text-sm font-extrabold uppercase tracking-wider text-teal-950">
              Sub Penutup / Kesimpulan Artikel
            </label>
          </div>

          <!-- Fitur Judul Sub Penutup -->
          <div>
            <label class="block text-[11px] font-bold text-teal-900 mb-1">
              Judul Sub Penutup (Fitur Judul):
            </label>
            <input 
              type="text" 
              name="conclusion_title" 
              value="{{ $oldConclusionTitle }}" 
              placeholder="Contoh: Kesimpulan & Pesan Sehat Dokter"
              class="w-full py-2 px-3.5 bg-white border border-teal-200 rounded-xl text-xs sm:text-sm font-bold text-gray-900 outline-none focus:border-teal-600 focus:ring-1 focus:ring-teal-600"
            >
            <p class="text-[10px] text-gray-400 mt-0.5">Judul sub-bagian penutup yang tampil sebelum kalimat kesimpulan akhir.</p>
          </div>

          <!-- Teks Paragraf Penutup -->
          <div>
            <label class="block text-[11px] font-bold text-teal-900 mb-1">
              Isi Paragraf Penutup (Teks Biasa):
            </label>
            <textarea 
              name="conclusion" 
              rows="3" 
              placeholder="Contoh: Diabetes melitus dapat dicegah dengan menerapkan pola hidup sehat, termasuk makan seimbang dan olahraga teratur.&#10;**Mulai hari ini, sayangi tubuh Anda dengan hidup sehat!**"
              class="w-full p-3 bg-white border border-gray-200 rounded-xl text-xs sm:text-sm font-medium text-gray-800 leading-relaxed outline-none focus:border-teal-600"
            >{{ $oldConclusion }}</textarea>
            <p class="text-[10px] text-gray-400 mt-0.5">Tuliskan pesan penutup atau kalimat ajakan hidup sehat. Beri tanda **tebal** jika ingin teks ditebalkan.</p>
          </div>
        </div>

        <!-- Tags & Referensi Medis (Sesuai Screenshot Tampilan) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Tags Artikel -->
          <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
            <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-800">
              🏷️ Tags Artikel (Dipisah Koma)
            </label>
            <input 
              type="text" 
              name="tags" 
              value="{{ old('tags', $tagsString) }}" 
              placeholder="Contoh: Gaya Hidup, Pola Hidup Sehat, Olahraga"
              class="w-full py-2.5 px-3 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 outline-none focus:border-[#00a870]"
            >
            <p class="text-[11px] text-gray-400">Akan tampil di kotak Tags biru muda pada artikel.</p>
          </div>

          <!-- Referensi Medis Ilmiah -->
          <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
            <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-800">
              📚 Referensi Medis (1 Baris Tiap Sumber)
            </label>
            <textarea 
              name="references" 
              rows="3" 
              placeholder="1. PERKENI. Pedoman Pengelolaan dan Pencegahan Diabetes Melitus...&#10;2. Rini S, Manto OAD. Hubungan Pola Hidup dengan Kadar Gula Darah..."
              class="w-full p-2.5 bg-white border border-gray-200 rounded-xl text-xs font-medium text-gray-800 outline-none focus:border-[#00a870]"
            >{{ old('references', $refsString) }}</textarea>
            <p class="text-[11px] text-gray-400">Akan tampil otomatis dengan nomor urut rapi di bagian bawah artikel.</p>
          </div>
        </div>

        <!-- Berkas Dokumen / Lampiran Utama (Input File) -->
        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
          <label class="block text-xs font-extrabold uppercase tracking-wider text-gray-800">
            📎 Berkas Dokumen / Lampiran Utama (Opsional)
          </label>

          @if(!empty($article->attachment_file))
            <div class="flex items-center justify-between p-2.5 bg-emerald-50 border border-emerald-200 rounded-xl text-xs">
              <div class="flex items-center space-x-2 truncate">
                <span class="text-base">📄</span>
                <span class="font-bold text-gray-800 truncate">{{ $article->attachment_name ?: 'Berkas Lampiran Utama' }}</span>
                <a href="{{ $article->attachment_file }}" target="_blank" class="text-sky-600 font-bold hover:underline">
                  Lihat Berkas &nearr;
                </a>
              </div>
              <label class="inline-flex items-center space-x-1 text-xs text-rose-600 font-bold cursor-pointer shrink-0">
                <input type="checkbox" name="remove_attachment" value="1" class="rounded text-rose-600">
                <span>Hapus Berkas</span>
              </label>
            </div>
          @endif

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
            <div>
              <input 
                type="file" 
                name="attachment_file" 
                class="w-full text-xs text-gray-700 file:mr-2.5 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#0085d0] file:text-white hover:file:bg-[#006ca8] file:cursor-pointer cursor-pointer border border-gray-200 rounded-xl bg-white p-1"
              >
              <p class="text-[10px] text-gray-400 mt-1">Upload PDF, Word, atau Excel buku panduan medis (Maks. 20MB)</p>
            </div>
            <div>
              <input 
                type="text" 
                name="attachment_name" 
                value="{{ old('attachment_name', $article->attachment_name) }}" 
                placeholder="Label Berkas (Contoh: Panduan_Lengkap_Diabetes.pdf)"
                class="w-full py-2 px-3 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-800 outline-none focus:border-[#0085d0]"
              >
              <p class="text-[10px] text-gray-400 mt-1">Nama tombol unduh berkas untuk pengunjung</p>
            </div>
          </div>
        </div>

        <!-- Checkbox Publikasikan -->
        <div class="pt-2">
          <label class="inline-flex items-center space-x-2.5 cursor-pointer">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }} class="rounded text-[#00a870] focus:ring-[#00a870] w-4 h-4">
            <span class="text-xs font-bold text-gray-800">Status Tayang: Tampilkan artikel ini kepada pengunjung website</span>
          </label>
        </div>

        <!-- Tombol Aksi Simpan & Hapus (Non-nested) -->
        <div class="pt-5 border-t border-gray-100 flex items-center justify-between">
          <button 
            type="button" 
            onclick="if(confirm('Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.')) { document.getElementById('deleteArticleForm').submit(); }" 
            class="px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer"
          >
            🗑️ Hapus Artikel
          </button>

          <div class="flex items-center space-x-3">
            <a href="{{ route('admin.articles.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 font-bold text-xs rounded-xl hover:bg-gray-50 transition-colors">
              Batal
            </a>
            <button 
              type="submit" 
              class="px-6 py-2.5 bg-[#00a870] hover:bg-[#008f5d] text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md transition-all cursor-pointer hover:shadow-lg"
            >
              💾 Perbarui Artikel
            </button>
          </div>
        </div>

      </form>

      <!-- Form Hapus Terpisah (Diluar form edit utama) -->
      <form id="deleteArticleForm" action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
      </form>

    </div>

</div>

<script>
  function previewArticleImage(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const previewImg = document.getElementById('articlePreviewImg');
        const statusText = document.getElementById('imagePreviewStatus');
        if (previewImg) {
          previewImg.src = e.target.result;
        }
        if (statusText) {
          statusText.textContent = 'Pratinjau Berkas Baru: ' + input.files[0].name;
          statusText.classList.add('text-emerald-600');
        }
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  // Dynamic Poin Penjelasan Konten (Nomer, Judul, Penjelasan & File tanpa kode HTML)
  let pointCounter = 0;

  function addContentPointRow(initialText = '', initialNumber = '', initialTitle = '', initialFileName = '', existingFile = '') {
    const container = document.getElementById('content-points-container');
    const idx = pointCounter++;
    const currentCount = container.querySelectorAll('.content-point-card').length + 1;
    const num = initialNumber || currentCount;

    const card = document.createElement('div');
    card.className = 'content-point-card bg-white p-4 rounded-xl border border-emerald-100 shadow-2xs space-y-3 transition-all';
    card.setAttribute('data-index', idx);

    let existingFileHtml = '';
    if (existingFile) {
      existingFileHtml = `
        <div class="mb-2 p-2.5 bg-sky-50 rounded-lg border border-sky-200 flex items-center justify-between text-xs">
          <div class="flex items-center space-x-2 truncate">
            <span class="text-base shrink-0">📄</span>
            <div class="truncate">
              <span class="font-bold text-gray-800 block truncate text-[11px]">${initialFileName || 'Berkas Lampiran'}</span>
              <a href="${existingFile}" target="_blank" class="text-[10px] text-sky-600 hover:underline">
                Lihat File Saat Ini &nearr;
              </a>
            </div>
          </div>
          <label class="inline-flex items-center space-x-1 text-[11px] text-rose-600 font-bold shrink-0 cursor-pointer ml-2">
            <input type="checkbox" name="content_items[${idx}][remove_file]" value="1" class="rounded text-rose-600 focus:ring-rose-500">
            <span>Hapus File</span>
          </label>
        </div>
        <input type="hidden" name="content_items[${idx}][existing_file]" value="${existingFile}">
        <input type="hidden" name="content_items[${idx}][existing_file_name]" value="${initialFileName}">
      `;
    }

    card.innerHTML = `
      <div class="flex items-center justify-between pb-2 border-b border-gray-100">
        <div class="flex items-center space-x-2.5">
          <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs font-black point-badge">
            ${num}
          </span>
          <div class="flex items-center space-x-1.5">
            <span class="text-xs font-extrabold text-emerald-950">Nomor:</span>
            <input 
              type="number" 
              name="content_items[${idx}][number]" 
              value="${num}" 
              class="w-16 py-1 px-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-900 text-center outline-none focus:bg-white focus:border-[#00a870] point-num-input"
              min="1"
            >
          </div>
        </div>
        <button 
          type="button" 
          onclick="removeContentPointRow(this)" 
          class="text-xs text-rose-500 hover:text-rose-700 hover:bg-rose-50 font-bold px-2.5 py-1 rounded-lg transition-colors flex items-center space-x-1 cursor-pointer"
          title="Hapus poin ini"
        >
          <span>🗑️</span>
          <span>Hapus</span>
        </button>
      </div>

      <!-- 1. Judul Poin / Subjudul -->
      <div>
        <label class="block text-[11px] font-bold text-gray-700 mb-1">
          Judul Poin / Subjudul:
        </label>
        <input 
          type="text" 
          name="content_items[${idx}][title]" 
          placeholder="Contoh: Konsumsi Makanan Seimbang (atau Rutin Berolahraga)"
          class="w-full py-1.5 px-3 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-900 outline-none focus:bg-white focus:border-[#00a870] point-title-input"
        >
      </div>

      <!-- 2. File / Foto Pendukung (Di Bawah Judul, Di Atas Penjelasan) -->
      <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100/80 space-y-2">
        ${existingFileHtml}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-[11px] font-bold text-emerald-950 mb-1">
              📎 ${existingFile ? 'Ganti File / Foto (Input File Baru):' : 'File / Foto Pendukung Poin Ini (Opsional):'}
            </label>
            <input 
              type="file" 
              name="content_items[${idx}][file]" 
              class="w-full text-xs text-gray-700 file:mr-2.5 file:py-1.5 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#0085d0] file:text-white hover:file:bg-[#006ca8] file:cursor-pointer cursor-pointer border border-gray-200 rounded-lg bg-white p-1"
            >
            <p class="text-[10px] text-gray-400 mt-0.5">Mendukung Foto/Gambar (JPG/PNG), Dokumen PDF, Word (Maks 20MB)</p>
          </div>

          <div>
            <label class="block text-[11px] font-bold text-gray-700 mb-1">
              Nama Label Berkas (Opsional):
            </label>
            <input 
              type="text" 
              name="content_items[${idx}][file_name]" 
              placeholder="Contoh: Tabel_Menu_Seimbang.pdf"
              class="w-full py-1.5 px-2.5 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-800 outline-none focus:border-[#0085d0] point-filename-input"
            >
            <p class="text-[10px] text-gray-400 mt-0.5">Nama tampilan tombol unduh untuk pembaca</p>
          </div>
        </div>
      </div>

      <!-- 3. Teks Penjelasan & Sub-Poin (Di Bawah File) -->
      <div>
        <label class="block text-[11px] font-bold text-gray-700 mb-1">
          Penjelasan / Rincian (Teks Biasa, Tanpa Kode HTML):
        </label>
        <textarea 
          name="content_items[${idx}][text]" 
          rows="3" 
          placeholder="Tuliskan materi penjelasan biasa. Awali dengan tanda '-' atau '•' untuk membuat bullet point rapi seperti:&#10;- Perbanyak serat dari sayuran dan buah&#10;- Batasi gula dan karbohidrat sederhana"
          class="w-full p-2.5 bg-gray-50 border border-gray-200 rounded-lg text-xs font-medium text-gray-800 leading-relaxed outline-none focus:bg-white focus:border-[#00a870] point-text-input"
        ></textarea>
      </div>
    `;

    // Safe property assignment
    const titleInput = card.querySelector('.point-title-input');
    const filenameInput = card.querySelector('.point-filename-input');
    const textInput = card.querySelector('.point-text-input');

    if (titleInput) titleInput.value = initialTitle || '';
    if (filenameInput) filenameInput.value = initialFileName || '';
    if (textInput) textInput.value = initialText || '';

    container.appendChild(card);
    reindexContentPoints();
  }

  function removeContentPointRow(button) {
    const card = button.closest('.content-point-card');
    const container = document.getElementById('content-points-container');
    if (card) {
      if (container.querySelectorAll('.content-point-card').length <= 1) {
        alert('Minimal harus ada 1 poin penjelasan artikel.');
        return;
      }
      card.remove();
      reindexContentPoints();
    }
  }

  function reindexContentPoints() {
    const cards = document.querySelectorAll('.content-point-card');
    cards.forEach((card, index) => {
      const num = index + 1;
      const badge = card.querySelector('.point-badge');
      const numInput = card.querySelector('.point-num-input');
      if (badge) badge.textContent = num;
      if (numInput && !numInput.value) numInput.value = num;
    });
  }

  // Inisialisasi awal formulir edit dengan data poin tanpa kode HTML
  document.addEventListener('DOMContentLoaded', function() {
    const structuredPoints = @json($article->structured_data['points'] ?? []);
    let existingPoints = @json(old('content_items')) || structuredPoints;
    if (existingPoints && typeof existingPoints === 'object' && !Array.isArray(existingPoints)) {
      existingPoints = Object.values(existingPoints);
    }

    if (Array.isArray(existingPoints) && existingPoints.length > 0) {
      existingPoints.forEach(item => {
        addContentPointRow(
          item.text || '', 
          item.number || '', 
          item.title || '',
          item.file_name || '', 
          item.file || ''
        );
      });
    } else {
      addContentPointRow('', 1, '');
    }
  });
</script>
@endsection

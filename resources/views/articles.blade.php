@extends('layouts.app')

@section('title', 'Berita & Artikel Kesehatan | Diabetes Uji Coba')

@section('content')
  <!-- ============================================================== -->
  <!-- HEADER BANNER FITUR                                            -->
  <!-- ============================================================== -->
  <section class="bg-gradient-to-r from-[#073b45] via-[#0a4e5c] to-[#123B5D] py-12 text-white relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-30 pointer-events-none">
      <div class="absolute -top-16 -left-16 w-72 h-72 bg-[#00BFA6] rounded-full blur-3xl"></div>
      <div class="absolute -bottom-16 -right-16 w-72 h-72 bg-[#168FE5] rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
          <div class="inline-flex items-center space-x-2 px-3 py-1 bg-[#123B5D]/80 backdrop-blur-md rounded-full text-xs font-semibold text-[#00BFA6] border border-[#00BFA6]/30 mb-3">
            <span>📚 Portal Edukasi & Artikel Medis</span>
          </div>
          <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
            Berita & Artikel Kesehatan
          </h1>
          <p class="text-[#EAF7FF]/90 text-xs sm:text-sm mt-2 max-w-2xl leading-relaxed">
            Kumpulan panduan terverifikasi dokter spesialis mengenai nutrisi, gaya hidup sehat, deteksi dini prediabetes, dan tips klinis pencegahan komplikasi.
          </p>
        </div>

        <div class="flex items-center gap-3">
          <a 
            href="{{ route('quiz') }}" 
            class="px-5 py-3 bg-gradient-to-r from-[#00BFA6] to-[#168FE5] hover:opacity-95 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-[#00BFA6]/20 transition-all hover:scale-[1.02] flex items-center space-x-2"
          >
            <span>🩺 Cek Risiko Sekarang</span>
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================================== -->
  <!-- MAIN CONTENT: 2-COLUMN LAYOUT                                  -->
  <!-- ============================================================== -->
  <section class="py-12 sm:py-16 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
        
        <!-- ========================================================== -->
        <!-- KOLOM KIRI: BERITA & EDUKASI UTAMA                         -->
        <!-- ========================================================== -->
        <div class="lg:col-span-7 space-y-6">
          
          <div class="flex items-center justify-between pb-3 border-b border-gray-200">
            <div>
              <span class="text-xs font-bold text-[#00BFA6] uppercase tracking-wider">Artikel Sorotan</span>
              <h2 class="text-xl sm:text-2xl font-extrabold text-[#123B5D] tracking-tight mt-0.5">
                Panduan Medis Terkini
              </h2>
            </div>
            <span class="inline-flex items-center px-3 py-1 bg-[#EAF7FF] text-[#168FE5] rounded-full text-xs font-bold border border-[#43B4F2]/30">
              Ditinjau Tim Dokter
            </span>
          </div>

          @php
            $articlesList = array_values($articles);
            $featured = $articlesList[0] ?? null;
            $sub1 = $articlesList[1] ?? null;
            $sub2 = $articlesList[2] ?? null;
          @endphp

          @if($featured)
            <!-- Featured Main Article Card -->
            <article class="bg-white rounded-3xl p-5 sm:p-6 border border-gray-200 transition-all hover:shadow-lg group">
              <div class="relative rounded-2xl overflow-hidden mb-5 aspect-[16/9] shadow-sm">
                <img 
                  src="{{ $featured['image'] ?? 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=800&auto=format&fit=crop&q=80' }}" 
                  alt="{{ $featured['title'] }}" 
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                >
                <div class="absolute top-3 left-3 bg-gradient-to-r from-[#00BFA6] to-[#168FE5] text-white text-[11px] font-extrabold px-3 py-1 rounded-full shadow-md uppercase tracking-wide">
                  {{ $featured['category'] ?? 'Diabetes' }}
                </div>
              </div>

              <div class="space-y-3">
                <div class="flex items-center space-x-3 text-xs text-gray-400">
                  <span>🩺 Ditinjau oleh {{ $featured['author'] ?? 'Tim Medis' }}</span>
                  <span>•</span>
                  <span>⏱️ 5 Menit Baca</span>
                  <span>•</span>
                  <span>📅 {{ $featured['date'] ?? now()->format('d/m/Y') }}</span>
                </div>

                <a href="{{ route('articles.detail', $featured['slug']) }}" class="block">
                  <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 group-hover:text-[#168FE5] transition-colors leading-snug">
                    {{ $featured['title'] }}
                  </h3>
                </a>

                <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                  {{ $featured['summary'] ?? '' }}
                </p>

                <div class="pt-2 flex items-center justify-between">
                  <a 
                    href="{{ route('articles.detail', $featured['slug']) }}"
                    class="inline-flex items-center space-x-2 text-sm font-bold text-[#168FE5] hover:text-[#00BFA6] transition-colors"
                  >
                    <span>Baca Selengkapnya</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                  </a>

                  <div class="flex space-x-1.5">
                    <span class="px-2.5 py-0.5 bg-[#EAF7FF] text-[#00BFA6] rounded-lg text-[11px] font-bold border border-[#00BFA6]/20">{{ $featured['category'] ?? 'Diabetes' }}</span>
                  </div>
                </div>
              </div>
            </article>
          @endif

          <!-- 2 Compact Grid Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            @if($sub1)
              <div class="p-5 bg-white rounded-2xl border border-gray-200 hover:border-[#00BFA6]/40 hover:shadow-md transition-all">
                <span class="text-xs font-bold text-[#00BFA6] uppercase">{{ $sub1['category'] ?? 'Aktivitas' }}</span>
                <a href="{{ route('articles.detail', $sub1['slug']) }}">
                  <h4 class="font-bold text-gray-900 text-sm mt-1 mb-1.5 hover:text-[#168FE5]">
                    {{ $sub1['title'] }}
                  </h4>
                </a>
                <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                  {{ $sub1['summary'] ?? '' }}
                </p>
                <a href="{{ route('articles.detail', $sub1['slug']) }}" class="text-xs font-bold text-[#168FE5] mt-2 inline-block hover:underline">
                  Pelajari Tips &rarr;
                </a>
              </div>
            @endif

            @if($sub2)
              <div class="p-5 bg-white rounded-2xl border border-gray-200 hover:border-[#168FE5]/40 hover:shadow-md transition-all">
                <span class="text-xs font-bold text-[#168FE5] uppercase">{{ $sub2['category'] ?? 'Klinis' }}</span>
                <a href="{{ route('articles.detail', $sub2['slug']) }}">
                  <h4 class="font-bold text-gray-900 text-sm mt-1 mb-1.5 hover:text-[#168FE5]">
                    {{ $sub2['title'] }}
                  </h4>
                </a>
                <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                  {{ $sub2['summary'] ?? '' }}
                </p>
                <a href="{{ route('articles.detail', $sub2['slug']) }}" class="text-xs font-bold text-[#168FE5] mt-2 inline-block hover:underline">
                  Pelajari Tips &rarr;
                </a>
              </div>
            @endif

          </div>

        </div>

        <!-- ========================================================== -->
        <!-- KOLOM KANAN: WIDGET "BACA JUGA" (STICKY & SEBAGIAN SCROLL) -->
        <!-- ========================================================== -->
        <div class="lg:col-span-5 md:sticky top-20 md:top-24 self-start space-y-4" style="position: sticky; top: 90px; align-self: flex-start; z-index: 20;">
          
          <!-- Kotak Pencarian Artikel (Tetap Diam di Atas Sidebar) -->
          <div class="relative">
            <div class="relative flex items-center">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
              <input 
                type="text" 
                id="newsSearchInput" 
                oninput="filterNewsList(this.value)" 
                placeholder="Cari berita atau artikel kesehatan..." 
                class="w-full bg-white border border-gray-200 rounded-2xl py-3 pl-11 pr-10 text-sm text-gray-900 placeholder-gray-400 focus:border-[#0085d0] focus:ring-2 focus:ring-[#0085d0]/20 shadow-sm transition-all outline-none"
              >
              <button 
                type="button" 
                id="clearSearchBtn" 
                onclick="clearNewsSearch()" 
                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 hidden cursor-pointer"
                title="Hapus pencarian"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </div>

          <!-- SECTION: BACA JUGA (HEADER DIAM, LIST GESER KE ATAS) -->
          <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            
            <!-- Header Widget: DIAM DI TEMPAT -->
            <div class="p-5 border-b border-gray-100 bg-white sticky top-0 z-10 flex items-center justify-between">
              <div>
                <span class="text-[10px] font-extrabold text-[#00BFA6] uppercase tracking-wider block">Koleksi Terkait</span>
                <h3 class="text-xl sm:text-2xl font-extrabold text-[#123B5D] tracking-tight">
                  Baca Juga
                </h3>
              </div>
              <div class="flex items-center space-x-2">
                <span id="searchResultCount" class="hidden text-xs font-semibold px-2.5 py-1 bg-blue-50 text-[#168FE5] rounded-full border border-blue-100">
                  Hasil Pencarian
                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#EAF7FF] text-[#168FE5] border border-[#43B4F2]/30">
                  {{ count($articles) }} Artikel
                </span>
              </div>
            </div>

            <!-- List Artikel: SEBAGIAN GESER KE ATAS DENGAN SCROLL MANDIRI & AUTO SYNC -->
            <div class="max-h-[calc(100vh-250px)] overflow-y-auto custom-scrollbar" id="newsItemsContainer">
              @forelse($articles as $item)
              <div class="news-item p-5 border-b border-gray-100 hover:bg-gray-50/60 transition-colors">
                <div class="flex items-center justify-between gap-4">
                  <div class="flex-1">
                    @if(!empty($item['category']))
                      <span class="text-[10px] font-bold text-[#00BFA6] uppercase bg-[#EAF7FF] px-2 py-0.5 rounded-full inline-block mb-1.5 border border-[#00BFA6]/20">
                        {{ $item['category'] }}
                      </span>
                    @endif
                    <a href="{{ route('articles.detail', $item['slug']) }}" class="block">
                      <h4 class="font-semibold text-gray-900 text-sm sm:text-[15px] leading-snug hover:text-[#168FE5]">
                        {{ $item['title'] }}
                      </h4>
                    </a>
                    @if(!empty($item['summary']))
                      <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                        {{ $item['summary'] }}
                      </p>
                    @endif
                    <div class="mt-2.5">
                      <a 
                        href="{{ route('articles.detail', $item['slug']) }}" 
                        class="inline-flex items-center text-xs font-bold text-[#168FE5] hover:text-[#00BFA6] tracking-wide uppercase space-x-1"
                      >
                        <span>SELENGKAPNYA</span>
                        <span class="text-sm font-bold">&gt;</span>
                      </a>
                    </div>
                  </div>
                  @if(!empty($item['image']))
                  <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 rounded-2xl overflow-hidden shadow-sm bg-gray-100">
                    <a href="{{ route('articles.detail', $item['slug']) }}">
                      <img 
                        src="{{ $item['image'] }}" 
                        alt="{{ $item['title'] }}" 
                        class="w-full h-full object-cover hover:scale-105 transition-transform"
                        loading="lazy"
                        onerror="this.src='https://images.unsplash.com/photo-1505751172876-fa1923c5c528?w=300&auto=format&fit=crop&q=80'"
                      >
                    </a>
                  </div>
                  @endif
                </div>
              </div>
              @empty
              <div class="p-8 text-center text-xs text-gray-400">
                Belum ada artikel tambahan.
              </div>
              @endforelse

              <!-- Empty Search Result State -->
              <div id="noResultsMsg" class="hidden p-8 text-center bg-gray-50/50">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
                <p class="text-sm font-bold text-gray-700">Tidak ada artikel yang cocok</p>
                <p class="text-xs text-gray-500 mt-1">Coba kata kunci lain atau periksa ejaan judul artikel Anda.</p>
                <button 
                  type="button" 
                  onclick="clearNewsSearch()" 
                  class="mt-3 px-3.5 py-1.5 text-xs font-bold text-[#168FE5] bg-[#EAF7FF] hover:bg-blue-100 rounded-xl transition-colors cursor-pointer"
                >
                  Reset Pencarian
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================================== -->
  <!-- MODAL BACA ARTIKEL EDUKASI                                     -->
  <!-- ============================================================== -->
  <div id="newsArticleModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[85vh] overflow-y-auto p-6 sm:p-8 shadow-2xl relative">
      <button 
        type="button" 
        onclick="closeArticleModal()" 
        class="absolute top-5 right-5 text-gray-400 hover:text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center font-bold text-lg"
      >
        &times;
      </button>
      
      <div id="modalArticleBadge" class="inline-block text-[11px] font-bold px-3 py-1 bg-[#EAF7FF] text-[#00BFA6] border border-[#00BFA6]/20 rounded-full mb-3 uppercase tracking-wide">
        Panduan Medis
      </div>
      
      <h3 id="modalArticleTitle" class="text-xl sm:text-2xl font-extrabold text-[#123B5D] leading-snug mb-3">
        Judul Artikel
      </h3>
      
      <div id="modalArticleMeta" class="text-xs text-gray-400 mb-4 pb-4 border-b border-gray-100 flex items-center space-x-3">
        <span>🩺 Ditinjau oleh Tim Medis</span>
        <span>•</span>
        <span>⏱️ 3 Menit Baca</span>
      </div>

      <div id="modalArticleBody" class="text-xs sm:text-sm text-gray-700 leading-relaxed space-y-3">
        <!-- Dynamic Content Injected via JS -->
      </div>

      <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
        <button 
          type="button" 
          onclick="closeArticleModal()" 
          class="px-5 py-2.5 bg-gradient-to-r from-[#00BFA6] to-[#168FE5] hover:opacity-95 text-white font-bold text-xs rounded-xl shadow-md transition-all"
        >
          Tutup & Kembali
        </button>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  function filterNewsList(val) {
    const input = document.getElementById('newsSearchInput');
    const query = ((typeof val === 'string' ? val : input?.value) || '').toLowerCase().trim();
    const items = document.querySelectorAll('.news-item');
    const clearBtn = document.getElementById('clearSearchBtn');
    const resultBadge = document.getElementById('searchResultCount');
    const noResults = document.getElementById('noResultsMsg');

    if (query.length > 0) {
      if (clearBtn) clearBtn.classList.remove('hidden');
    } else {
      if (clearBtn) clearBtn.classList.add('hidden');
      if (resultBadge) resultBadge.classList.add('hidden');
    }

    let matchCount = 0;
    items.forEach(item => {
      const heading = item.querySelector('h4');
      const text = heading ? heading.innerText.toLowerCase() : '';
      if (!query || text.includes(query)) {
        item.style.display = '';
        matchCount++;
      } else {
        item.style.display = 'none';
      }
    });

    if (query.length > 0) {
      if (resultBadge) {
        resultBadge.innerText = `${matchCount} Ditemukan`;
        resultBadge.classList.remove('hidden');
      }
    }

    if (noResults) {
      if (matchCount === 0) {
        noResults.classList.remove('hidden');
      } else {
        noResults.classList.add('hidden');
      }
    }
  }

  function clearNewsSearch() {
    const input = document.getElementById('newsSearchInput');
    if (input) {
      input.value = '';
      filterNewsList('');
      input.focus();
    }
  }

  const articlesDb = {
    0: {
      badge: "Artikel Utama & Panduan Medis",
      title: "Panduan Komprehensif Pencegahan Diabetes Melitus: Deteksi Gejala Awal dan Pola Hidup Sehat",
      meta: "🩺 Ditinjau oleh Tim Dokter Spesialis Penyakit Dalam • ⏱️ 5 Menit Baca",
      body: `
        <p>Diabetes Melitus Tipe 2 merupakan penyakit metabolik kronis yang ditandai dengan hiperglikemia akibat resistensi insulin atau defisiensi sekresi insulin oleh sel beta pankreas.</p>
        <h5 class="font-bold text-gray-900 text-sm mt-3">Gejala Klasik 3P yang Wajib Diwaspadai:</h5>
        <ul class="list-disc pl-5 space-y-1">
          <li><strong>Poliuria (Sering Buang Air Kecil):</strong> Terutama di malam hari karena ginjal berusaha membuang kelebihan glukosa.</li>
          <li><strong>Polidipsia (Sering Merasa Haus):</strong> Akibat dehidrasi intraseluler dari hilangnya cairan.</li>
          <li><strong>Polifagia (Cepat Merasa Lapar):</strong> Sel tubuh kekurangan energi karena glukosa tidak dapat masuk ke dalam sel.</li>
        </ul>
        <h5 class="font-bold text-gray-900 text-sm mt-3">Prinsip Pencegahan 3J:</h5>
        <p>1. <strong>Jadwal:</strong> Makan tepat waktu dengan 3 kali makan utama dan 2 kali selingan sehat.<br>
           2. <strong>Jumlah:</strong> Porsi disesuaikan dengan kebutuhan kalori harian dan Indeks Massa Tubuh (BMI).<br>
           3. <strong>Jenis:</strong> Utamakan karbohidrat kompleks dengan indeks glikemik rendah dan tinggi serat.</p>
      `
    },
    1: {
      badge: "Nutrisi & Buah Sehat",
      title: "10 Pilihan Buah untuk Penderita Diabetes yang Aman dan Sehat",
      meta: "🩺 Ditinjau oleh Ahli Gizi Klinis • ⏱️ 3 Menit Baca",
      body: `
        <p>Penderita diabetes (diabetesi) tetap dianjurkan mengonsumsi buah karena kaya vitamin, antioksidan, dan serat alami. Kuncinya adalah memilih buah dengan <strong>Indeks Glikemik (IG) Rendah (&lt; 55)</strong> dan mengonsumsinya dalam porsi yang tepat.</p>
        <h5 class="font-bold text-gray-900 text-sm mt-3">10 Buah Ramah Diabetes:</h5>
        <ol class="list-decimal pl-5 space-y-1.5">
          <li><strong>Apel (dengan kulit):</strong> Kaya serat pektin yang memperlambat penyerapan glukosa.</li>
          <li><strong>Pir:</strong> Mengandung antosianin yang membantu meningkatkan sensitivitas insulin.</li>
          <li><strong>Alpukat:</strong> Lemak tak jenuh tunggal yang menstabilkan gula darah tanpa lonjakan insulin.</li>
          <li><strong>Jeruk utuh:</strong> Mengandung vitamin C tinggi dan serat (hindari dibuat jus).</li>
          <li><strong>Stroberi & Beri:</strong> Rendah gula dan sarat antioksidan flavonoid.</li>
          <li><strong>Kiwi:</strong> Kaya serat larut air dan vitamin E.</li>
          <li><strong>Jambu Biji:</strong> Mengandung serat pektin dan likopen tinggi.</li>
          <li><strong>Plum:</strong> Membantu regulasi metabolik asam lemak.</li>
          <li><strong>Persik:</strong> Indeks glikemik rendah dan menyegarkan.</li>
          <li><strong>Tomat:</strong> Rendah karbohidrat dan sangat aman untuk camilan harian.</li>
        </ol>
        <p class="text-xs text-amber-800 bg-amber-50 p-2.5 rounded-xl border border-amber-200 mt-3">
          💡 <em>Tips Dokter:</em> Selalu konsumsi buah segar utuh, bukan jus kemasan atau manisan buah yang telah ditambahkan gula.
        </p>
      `
    },
    2: {
      badge: "Gizi & Suplementasi",
      title: "Diabetesi Harus Memenuhi 5 Nutrisi Penting Ini",
      meta: "🩺 Ditinjau oleh Dokter Spesialis Gizi Klinis • ⏱️ 4 Menit Baca",
      body: `
        <p>Pengaturan nutrisi mikro dan makro memegang peranan krusial dalam mengendalikan kadar HbA1c dan mencegah peradangan seluler pembuluh darah.</p>
        <h5 class="font-bold text-gray-900 text-sm mt-3">5 Nutrisi Kunci Bagi Diabetesi:</h5>
        <ul class="list-disc pl-5 space-y-1.5">
          <li><strong>Serat Larut Air (Soluble Fiber):</strong> Membentuk gel di saluran cerna untuk memperlambat absorpsi glukosa (Oatmeal, chia seed, brokoli).</li>
          <li><strong>Magnesium:</strong> Kofaktor esensial bagi lebih dari 300 enzim metabolik, termasuk reseptor insulin.</li>
          <li><strong>Kromium (Chromium):</strong> Meningkatkan efektivitas kerja insulin dalam mengangkut gula ke dalam sel.</li>
          <li><strong>Asam Lemak Omega-3:</strong> Menekan risiko inflamasi kardiovaskular dan menjaga elastisitas pembuluh darah.</li>
          <li><strong>Vitamin D:</strong> Membantu fungsi optimal sel beta pankreas dalam mensekresi insulin.</li>
        </ul>
      `
    },
    3: {
      badge: "Panduan Puasa & Sahur",
      title: "Bagaimana Sahur yang Baik bagi Diabetesi?",
      meta: "🩺 Ditinjau oleh Tim PERKENI • ⏱️ 3 Menit Baca",
      body: `
        <p>Bagi diabetesi yang menjalani puasa, sahur adalah fondasi energi untuk mencegah hipoglikemia (gula darah drop) di siang hari dan hiperglikemia rebound saat berbuka.</p>
        <h5 class="font-bold text-gray-900 text-sm mt-3">Panduan Menu Sahur Ideal:</h5>
        <ul class="list-disc pl-5 space-y-1.5">
          <li><strong>Dekatkan Waktu Sahur:</strong> Makanlah menjelang imsak agar cadangan energi bertahan lebih lama.</li>
          <li><strong>Pilih Karbohidrat Kompleks Lepas Lambat:</strong> Nasi merah, beras cokelat, oatmeal, atau roti gandum utuh.</li>
          <li><strong>Cukupi Protein Berkualitas:</strong> Telur rebus, tahu, tempe, atau dada ayam tanpa kulit untuk memperpanjang rasa kenyang.</li>
          <li><strong>Hindari Makanan Asin & Gurih Berlebih:</strong> Mengurangi rasa haus ekstrem dan dehidrasi selama siang hari.</li>
          <li><strong>Minum 2-3 Gelas Air Putih:</strong> Pastikan hidrasi optimal selama jendela sahur.</li>
        </ul>
      `
    },
    4: {
      badge: "Solusi Nutrisi Khusus",
      title: "Solusi Susu Diabetes dengan Nutrisi Lengkap",
      meta: "🩺 Ditinjau oleh Tim Nutrisi Klinis • ⏱️ 3 Menit Baca",
      body: `
        <p>Bolehkah penderita diabetes minum susu? Boleh, asalkan memilih susu formula khusus diabetes yang diformulasikan rendah indeks glikemik.</p>
        <h5 class="font-bold text-gray-900 text-sm mt-3">Kriteria Susu yang Tepat untuk Diabetes:</h5>
        <ul class="list-disc pl-5 space-y-1.5">
          <li><strong>Mengandung Karbohidrat Isomaltulosa:</strong> Karbohidrat lepas lambat yang tidak menyebabkan lonjakan gula darah mendadak.</li>
          <li><strong>Bebas Gula Tambahan (No Added Sugar):</strong> Tanpa sukrosa atau pemanis sintetis berlebih.</li>
          <li><strong>Tinggi Kalsium & Vitamin D:</strong> Menjaga densitas tulang yang rentan melemah pada diabetesi lanjut usia.</li>
          <li><strong>Dapat Berfungsi sebagai Pengganti Makan (Meal Replacement):</strong> Sangat cocok dikonsumsi saat sarapan atau camilan malam sebelum tidur.</li>
        </ul>
      `
    },
    5: {
      badge: "Manajemen Berat Badan",
      title: "Tentang Berat Badan Ideal dan Cara Mencapainya",
      meta: "🩺 Ditinjau oleh Konsultan Obesitas & Metabolik • ⏱️ 4 Menit Baca",
      body: `
        <p>Penelitian klinis membuktikan bahwa <strong>penurunan 5-10% berat badan</strong> pada individu dengan kelebihan berat badan mampu menurunkan risiko terkena diabetes tipe 2 hingga <strong>58%</strong>.</p>
        <h5 class="font-bold text-gray-900 text-sm mt-3">Langkah Praktis Menuju Berat Badan Ideal:</h5>
        <ul class="list-disc pl-5 space-y-1.5">
          <li><strong>Hitung BMI & Lingkar Pinggang:</strong> Target lingkar pinggang sehat adalah &lt; 90 cm untuk pria Asia dan &lt; 80 cm untuk wanita Asia.</li>
          <li><strong>Defisit Kalori Terkendali:</strong> Kurangi 300-500 kkal per hari secara konsisten tanpa melewatkan jadwal makan utama.</li>
          <li><strong>Kombinasi Latihan Aerobik & Beban:</strong> Latihan beban meningkatkan massa otot yang merupakan organ pembakar glukosa terbesar dalam tubuh.</li>
          <li><strong>Tidur Cukup 7-8 Jam:</strong> Kurang tidur meningkatkan hormon ghrelin yang memicu nafsu makan berlebih.</li>
        </ul>
      `
    },
    6: {
      badge: "Kecerdasan Buatan Medis",
      title: "Bagaimana XGBoost Mendeteksi Risiko Diabetes dalam 1 Menit?",
      meta: "🔬 Data Mining & AI Healthcare Team • ⏱️ 4 Menit Baca",
      body: `
        <p>Algoritma <strong>Extreme Gradient Boosting (XGBoost)</strong> membangun ansambel pohon keputusan (decision trees) secara berurutan, di mana setiap pohon baru memperbaiki residu kesalahan klasifikasi dari pohon sebelumnya.</p>
        <p>Dengan melatih 21 variabel gaya hidup (BMI, tekanan darah, riwayat kolesterol, aktivitas fisik, konsumsi sayur/buah), sistem mampu menghitung skor probabilitas risiko secara objektif dan tervalidasi dataset CDC BRFSS.</p>
      `
    },
    7: {
      badge: "Panduan Latihan Jasmani",
      title: "Target 150 Menit Olahraga: Kunci Sensitivitas Insulin",
      meta: "🩺 Ditinjau oleh Dokter Spesialis Kedokteran Olahraga • ⏱️ 3 Menit Baca",
      body: `
        <p>Ketika otot rangka berkontraksi saat berolahraga (seperti jalan cepat, bersepeda santai, atau berenang), transporter glukosa <strong>GLUT-4</strong> bergerak ke membran sel untuk menyerap gula darah secara mandiri tanpa memerlukan stimulasi insulin.</p>
        <p>Efek peningkatan sensitivitas insulin ini bertahan hingga 24-48 jam setelah sesi latihan selesai, sehingga membagi 150 menit menjadi 3-5 sesi per minggu adalah frekuensi yang paling direkomendasikan secara klinis.</p>
      `
    }
  };

  function openArticleDetail(id) {
    const data = articlesDb[id];
    if (!data) return;

    document.getElementById('modalArticleBadge').innerText = data.badge;
    document.getElementById('modalArticleTitle').innerText = data.title;
    document.getElementById('modalArticleMeta').innerText = data.meta;
    document.getElementById('modalArticleBody').innerHTML = data.body;
    
    document.getElementById('newsArticleModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }

  function closeArticleModal() {
    document.getElementById('newsArticleModal').classList.add('hidden');
    document.body.style.overflow = '';
  }

  // SINKRONISASI GESER: Ketika user menggeser berita utama,
  // daftar artikel di widget "Baca Juga" ikut bergeser ke atas secara halus (sebagian diam, sebagian geser)
  document.addEventListener('DOMContentLoaded', function() {
    const newsContainer = document.getElementById('newsItemsContainer');
    if (!newsContainer) return;

    let isHoveringSidebar = false;
    newsContainer.addEventListener('mouseenter', function() { isHoveringSidebar = true; });
    newsContainer.addEventListener('mouseleave', function() { isHoveringSidebar = false; });
    newsContainer.addEventListener('touchstart', function() { isHoveringSidebar = true; }, { passive: true });
    newsContainer.addEventListener('touchend', function() { isHoveringSidebar = false; });

    window.addEventListener('scroll', function() {
      if (isHoveringSidebar) return;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      if (docHeight <= 0) return;

      const progress = Math.min(Math.max(window.scrollY / docHeight, 0), 1);
      const maxScroll = newsContainer.scrollHeight - newsContainer.clientHeight;
      if (maxScroll > 0) {
        newsContainer.scrollTop = progress * maxScroll;
      }
    }, { passive: true });
  });
</script>
@endpush

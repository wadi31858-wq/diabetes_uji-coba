@extends('layouts.app')

@section('title', $article['title'] . ' | Diabetes Uji Coba')

@section('content')
  <!-- Main Article Section -->
  <article class="py-10 sm:py-14 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      @if(auth()->check() && (auth()->user()->role ?? null) === 'admin' && !empty($article['id']))
        <!-- Admin Fast Actions Bar -->
        <div class="mb-6 p-3 bg-emerald-50 border border-emerald-200 rounded-2xl flex flex-wrap items-center justify-between gap-3 shadow-xs">
          <div class="flex items-center space-x-2 text-xs text-emerald-900 font-bold">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Panel Pengelola: Anda melihat halaman ini sebagai Administrator</span>
          </div>
          <div class="flex items-center space-x-2">
            <a 
              href="{{ route('admin.articles.edit', $article['id']) }}" 
              class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center space-x-1"
            >
              <span>✏️</span>
              <span>Edit Artikel Ini</span>
            </a>
            <form 
              action="{{ route('admin.articles.destroy', $article['id']) }}" 
              method="POST" 
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.')"
              class="inline"
            >
              @csrf
              @method('DELETE')
              <button 
                type="submit" 
                class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center space-x-1 cursor-pointer"
              >
                <span>🗑️</span>
                <span>Hapus Artikel Ini</span>
              </button>
            </form>
          </div>
        </div>
      @endif

      <!-- Top Meta: Category & Date -->
      <div class="flex items-center space-x-2 text-xs text-gray-500 mb-2">
        <a href="{{ route('articles') }}" class="font-bold text-kd-green hover:underline uppercase tracking-wider">
          {{ $article['category'] ?? 'Gaya Hidup' }}
        </a>
      </div>

      <!-- Main Headline (H1) -->
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#0d2137] tracking-tight leading-tight max-w-5xl">
        {{ $article['title'] }}
      </h1>

      <!-- Meta Row: Date, Time & Views Count (Sesuai Screenshot) -->
      <div class="flex items-center space-x-3 text-xs text-sky-600 font-medium mt-3 mb-6">
        <span class="w-2.5 h-2.5 rounded-full bg-sky-500 inline-block shrink-0"></span>
        <span class="text-gray-500 font-semibold">{{ $article['date'] ?? now()->format('d M Y') }}</span>
        <span class="text-gray-300">|</span>
        <div class="flex items-center space-x-1 text-gray-500 font-semibold">
          <span>{{ $article['views'] ?? 36 }}</span>
          <svg class="w-4 h-4 text-sky-500 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
        </div>
      </div>

      <!-- 2-Column Grid Content -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
        
        <!-- Left Column: Article Body (65-70%) -->
        <div class="lg:col-span-8 space-y-6">
          
          @if(!empty($article['image']))
            <!-- Featured Article Hero Image -->
            <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 max-h-[460px]">
              <img 
                src="{{ $article['image'] }}" 
                alt="{{ $article['title'] }}" 
                class="w-full h-full object-cover max-h-[460px]"
              >
            </div>
          @endif

          <!-- Article Content Body (Editorial Flow: Paragraf, Poin Bernomor, & Bullets) -->
          <div class="article-prose text-sm sm:text-base text-gray-800 leading-relaxed space-y-4 pt-1">
            {!! $article['content'] !!}
          </div>

          @php
            $tags = $article['tags_list'] ?? ['Gaya Hidup', 'Pola Hidup Sehat'];
            $references = $article['references_list'] ?? [];
            $attachmentFile = $article['attachment_file'] ?? null;
            $attachmentName = $article['attachment_name'] ?? 'Unduh Berkas Lampiran';
          @endphp

          <!-- Berkas Lampiran Utama (Jika Ada) -->
          @if(!empty($attachmentFile))
            <div class="my-6 p-4 bg-sky-50/70 border border-sky-200/80 rounded-2xl flex items-center justify-between gap-3 shadow-2xs">
              <div class="flex items-center space-x-2.5 min-w-0">
                <span class="text-xl shrink-0">📎</span>
                <div>
                  <h4 class="text-xs font-bold text-sky-950 truncate">{{ $attachmentName }}</h4>
                  <p class="text-[10px] text-gray-500">Materi rujukan dokumen pendukung artikel edukasi ini.</p>
                </div>
              </div>
              <a 
                href="{{ $attachmentFile }}" 
                target="_blank" 
                download
                class="px-4 py-2 bg-sky-600 hover:bg-sky-700 active:scale-95 text-white text-xs font-bold rounded-xl transition-all shrink-0 shadow-2xs inline-flex items-center space-x-1.5"
              >
                <span>Unduh File</span>
                <span>&darr;</span>
              </a>
            </div>
          @endif

          <!-- Kotak Tags (Sesuai Screenshot: Background biru muda lembut & badge putih) -->
          <div class="my-7 p-3.5 sm:p-4 bg-sky-50/70 border border-sky-100 rounded-2xl flex flex-wrap items-center gap-2">
            <span class="text-xs font-bold text-sky-950 mr-1">Tags:</span>
            @foreach($tags as $t)
              <span class="px-3 py-1 bg-white border border-sky-200/80 rounded-lg text-xs font-medium text-sky-850 shadow-2xs">
                {{ $t }}
              </span>
            @endforeach
          </div>

          <!-- Bagian Referensi Medis (Sesuai Screenshot: Judul tebal & nomor rujukan ilmiah) -->
          @if(!empty($references) && count($references) > 0)
            <div class="my-7 pt-6 border-t border-gray-100">
              <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-3 tracking-tight">Referensi</h2>
              <ol class="list-decimal pl-5 space-y-2 text-xs text-gray-600 leading-relaxed">
                @foreach($references as $ref)
                  <li>{{ $ref }}</li>
                @endforeach
              </ol>
            </div>
          @endif

          <!-- Bagian Bagikan (Share Row di Bawah - Tombol Pill Outline Sesuai Screenshot) -->
          <div class="my-8 pt-5 border-t border-gray-100 flex flex-wrap items-center gap-2 sm:gap-2.5">
            <span class="text-xs sm:text-sm font-bold text-gray-900 mr-2">Bagikan :</span>
            
            <!-- Facebook -->
            <a 
              href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
              target="_blank" 
              rel="noopener noreferrer" 
              class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg border border-sky-400 text-sky-600 hover:bg-sky-50 hover:border-sky-500 transition-all text-xs font-semibold"
              title="Bagikan ke Facebook"
            >
              <span class="font-black text-xs">f</span>
              <span>Facebook</span>
            </a>

            <!-- Twitter -->
            <a 
              href="https://twitter.com/intent/tweet?text={{ urlencode($article['title']) }}&url={{ urlencode(url()->current()) }}" 
              target="_blank" 
              rel="noopener noreferrer" 
              class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg border border-sky-400 text-sky-600 hover:bg-sky-50 hover:border-sky-500 transition-all text-xs font-semibold"
              title="Bagikan ke Twitter"
            >
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.936 9.936 0 0024 4.59z"/>
              </svg>
              <span>Twitter</span>
            </a>

            <!-- LinkedIn -->
            <a 
              href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" 
              target="_blank" 
              rel="noopener noreferrer" 
              class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg border border-sky-400 text-sky-600 hover:bg-sky-50 hover:border-sky-500 transition-all text-xs font-semibold"
              title="Bagikan ke LinkedIn"
            >
              <span class="font-black text-xs">in</span>
              <span>LinkedIn</span>
            </a>

            <!-- WhatsApp -->
            <a 
              href="https://api.whatsapp.com/send?text={{ urlencode($article['title'] . ' ' . url()->current()) }}" 
              target="_blank" 
              rel="noopener noreferrer" 
              class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg border border-sky-400 text-sky-600 hover:bg-sky-50 hover:border-sky-500 transition-all text-xs font-semibold"
              title="Bagikan ke WhatsApp"
            >
              <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
              </svg>
              <span>WhatsApp</span>
            </a>

            <!-- Salin Tautan -->
            <button 
              type="button" 
              onclick="copyArticleLink()" 
              class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg border border-sky-400 text-sky-600 hover:bg-sky-50 hover:border-sky-500 transition-all text-xs font-semibold cursor-pointer"
              title="Salin Tautan Artikel"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
              </svg>
              <span>Salin tautan</span>
            </button>
          </div>

          <!-- Bottom Action Buttons -->
          <div class="pt-8 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <a 
              href="{{ route('articles') }}" 
              class="inline-flex items-center text-xs sm:text-sm font-bold text-[#0085d0] hover:text-[#006ca8] space-x-1.5"
            >
              <span>&larr;</span>
              <span>Kembali ke Semua Artikel</span>
            </a>

            <a 
              href="{{ route('quiz') }}" 
              class="px-5 py-2.5 bg-kd-green hover:bg-kd-greenDark text-white font-bold text-xs rounded-xl shadow-md transition-all hover:scale-[1.02]"
            >
              Cek Risiko Diabetes Mandiri &rarr;
            </a>
          </div>

        </div>

        <!-- Right Column: Sidebar "Baca Juga" (Sticky & Sebagian Diam / Sebagian Geser) -->
        <div class="lg:col-span-4 md:sticky top-20 md:top-24 self-start space-y-4" style="position: sticky; top: 90px; align-self: flex-start; z-index: 20;">
          
          <!-- Container Card dengan Header Diam & List Scroll Mandiri -->
          <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            
            <!-- Header Widget: DIAM DI TEMPAT -->
            <div class="p-5 border-b border-gray-100 bg-white sticky top-0 z-10 flex items-center justify-between">
              <div>
                <span class="text-[10px] font-extrabold text-kd-green uppercase tracking-wider block">Rekomendasi Terkait</span>
                <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">
                  Baca Juga
                </h2>
              </div>
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                Pilihan Dokter
              </span>
            </div>

            <!-- List Artikel: SEBAGIAN GESER KE ATAS DENGAN SCROLL MANDIRI & AUTO SYNC -->
            <div class="max-h-[calc(100vh-260px)] overflow-y-auto custom-scrollbar divide-y divide-gray-100" id="relatedArticlesContainer">
              @foreach($relatedArticles as $related)
                <div class="p-4 sm:p-5 hover:bg-gray-50/60 transition-colors">
                  <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">
                    {{ $related['category'] ?? 'Artikel Medis' }}
                  </span>
                  <a href="{{ route('articles.detail', $related['slug']) }}" class="block">
                    <h3 class="font-semibold text-gray-900 text-sm leading-snug hover:text-[#0085d0] transition-colors">
                      {{ $related['title'] }}
                    </h3>
                  </a>
                  <div class="mt-2.5">
                    <a 
                      href="{{ route('articles.detail', $related['slug']) }}" 
                      class="inline-flex items-center text-[11px] font-bold text-[#0085d0] hover:text-[#006ca8] tracking-wide uppercase space-x-1"
                    >
                      <span>SELENGKAPNYA</span>
                      <span class="text-xs font-bold">&gt;</span>
                    </a>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <!-- CTA Box in Sidebar -->
          <div class="p-5 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-3xl border border-emerald-100 text-center space-y-2 shadow-sm">
            <span class="text-2xl block">🩺</span>
            <h4 class="text-xs font-extrabold text-emerald-900">Ingin Tahu Risiko Diabetes Anda?</h4>
            <p class="text-[11px] text-gray-600 leading-relaxed">
              Analisis 21 indikator kesehatan menggunakan algoritma AI XGBoost terakreditasi CDC.
            </p>
            <div class="pt-1">
              <a 
                href="{{ route('quiz') }}" 
                class="inline-block px-4 py-2 bg-kd-green hover:bg-kd-greenDark text-white text-xs font-bold rounded-xl shadow-sm transition-all"
              >
                Mulai Skrining (1 Mnt)
              </a>
            </div>
          </div>

        </div>

      </div>

    </div>
  </article>

  <!-- Toast Copy Notification -->
  <div id="copyToast" class="fixed bottom-6 right-6 z-50 hidden bg-gray-900 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-lg transition-all">
    Tautan artikel berhasil disalin ke clipboard!
  </div>
@endsection

@push('scripts')
<script>
  function copyArticleLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
      const toast = document.getElementById('copyToast');
      if (toast) {
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
      }
    }).catch(err => {
      alert('Tautan artikel: ' + window.location.href);
    });
  }

  // SINKRONISASI GESER: Ketika user menggeser bacaan artikel utama,
  // daftar "Baca Juga" ikut bergeser ke atas secara halus (sebagian diam, sebagian geser)
  document.addEventListener('DOMContentLoaded', function() {
    const relatedContainer = document.getElementById('relatedArticlesContainer');
    if (!relatedContainer) return;

    let isHoveringSidebar = false;
    relatedContainer.addEventListener('mouseenter', function() { isHoveringSidebar = true; });
    relatedContainer.addEventListener('mouseleave', function() { isHoveringSidebar = false; });
    relatedContainer.addEventListener('touchstart', function() { isHoveringSidebar = true; }, { passive: true });
    relatedContainer.addEventListener('touchend', function() { isHoveringSidebar = false; });

    window.addEventListener('scroll', function() {
      if (isHoveringSidebar) return;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      if (docHeight <= 0) return;

      const progress = Math.min(Math.max(window.scrollY / docHeight, 0), 1);
      const maxScroll = relatedContainer.scrollHeight - relatedContainer.clientHeight;
      if (maxScroll > 0) {
        relatedContainer.scrollTop = progress * maxScroll;
      }
    }, { passive: true });
  });
</script>
@endpush

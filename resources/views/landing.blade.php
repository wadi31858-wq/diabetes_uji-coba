@extends('layouts.app')

@section('title', 'Diabetes Uji Coba | Deteksi Dini & Pencegahan Diabetes Melitus')

@section('content')
  {{-- 2026 Welcome / Splash Screen Overlay --}}
  @include('components.splash-screen')

  <!-- ============================================================== -->
  <!-- HERO BANNER                                                    -->
  <!-- ============================================================== -->
  <!-- HERO BANNER                                                    -->
  <!-- ============================================================== -->
  <section class="relative min-h-[580px] lg:min-h-[620px] flex items-center overflow-hidden pt-10 sm:pt-14 pb-20 sm:pb-28">
    
    <!-- Modern Hospital Background with Smooth Ambient Motion -->
    @php
      $customHeroBg = \App\Models\SiteContent::get('hero_bg_image');
      $heroBgUrl = !empty($customHeroBg) ? $customHeroBg : asset('assets/hero_hospital.jpg');
      $overlayOpacityVal = (int)\App\Models\SiteContent::get('hero_overlay_opacity', '85');
      $overlayOpacityDecimal = max(0.1, min(1.0, $overlayOpacityVal / 100));
    @endphp
    <div 
      class="hospital-bg-animated absolute inset-0 bg-cover bg-center sm:bg-[center_top_15%] bg-no-repeat pointer-events-none filter brightness-95" 
      style="background-image: url('{{ $heroBgUrl }}');"
    ></div>

    <!-- Soft Ocean-Teal Gradient Overlay for optimal readability and ambiance -->
    <div class="absolute inset-0 bg-gradient-to-r from-[#073b45]/90 via-[#0a4e5c]/75 to-[#0e6170]/40 pointer-events-none" style="opacity: {{ $overlayOpacityDecimal }};"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-[#073b45]/85 via-transparent to-[#073b45]/20 pointer-events-none" style="opacity: {{ $overlayOpacityDecimal }};"></div>

    <!-- Background Ambient Glow & Lighting -->
    <div class="absolute inset-0 z-0 pointer-events-none">
      <div class="absolute -top-24 -left-24 w-[32rem] h-[32rem] bg-[#00BFA6]/15 rounded-full blur-3xl"></div>
      <div class="absolute -top-10 -right-10 w-[28rem] h-[28rem] bg-[#43B4F2]/20 rounded-full blur-3xl"></div>
      <div class="absolute bottom-10 right-1/4 w-96 h-96 bg-[#168FE5]/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Flying Birds Animation Layer -->
    @include('components.birds-animation')

    <!-- Hero Content: 2-Column Grid -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
        
        <!-- Left Column: Headline, CTA & Highlights -->
        <div class="lg:col-span-7 text-white space-y-5 text-left">
          
          <!-- Pill Tag: Deteksi Dini Diabetes Uji Coba -->
          <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 bg-[#06313a]/80 backdrop-blur-md rounded-full text-xs font-semibold text-cyan-200 border border-cyan-400/30 shadow-sm">
            <div class="w-4 h-4 rounded-full bg-cyan-400/25 flex items-center justify-center">
              <svg class="w-2.5 h-2.5 text-cyan-200" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
              </svg>
            </div>
            <span>{{ \App\Models\SiteContent::get('hero_pill', 'Deteksi Dini Diabetes Uji Coba') }}</span>
          </div>

          <!-- Headline H1 -->
          <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[3.25rem] font-extrabold leading-[1.12] tracking-tight text-white drop-shadow-md">
            {!! nl2br(e(\App\Models\SiteContent::get('hero_title', "Ketahui Risiko Diabetes\nAnda Sejak Dini"))) !!}
          </h1>
          
          <!-- Subtitle -->
          <p class="text-cyan-50/90 text-xs sm:text-sm md:text-base leading-relaxed max-w-xl font-normal drop-shadow-sm">
            {{ \App\Models\SiteContent::get('hero_description', 'Skrining risiko diabetes mandiri dalam 1 menit menggunakan algoritma Machine Learning XGBoost tervalidasi CDC BRFSS.') }}
          </p>

          <!-- CTA Buttons -->
          <div class="flex flex-wrap items-center gap-3 pt-2">
            <a 
              href="{{ \App\Models\SiteContent::get('hero_cta_url', route('quiz')) }}" 
              class="inline-flex items-center px-6 py-3.5 bg-[#00BFA6] hover:bg-[#00ab95] text-white font-bold text-sm sm:text-base rounded-2xl shadow-lg shadow-[#00BFA6]/30 transition-all hover:scale-[1.02] space-x-2.5"
            >
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
              <span>{{ \App\Models\SiteContent::get('hero_cta_text', 'Mulai Cek Risiko Sekarang') }}</span>
              <svg class="w-4 h-4 ml-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
              </svg>
            </a>

            <a 
              href="{{ \App\Models\SiteContent::get('hero_cta2_url', '#sectionPilar') }}" 
              class="inline-flex items-center px-6 py-3.5 bg-[#073943]/80 hover:bg-[#0b4d5b] text-white font-semibold text-sm sm:text-base rounded-2xl backdrop-blur-md border border-[#43B4F2]/30 transition-all space-x-2.5"
            >
              <svg class="w-5 h-5 text-cyan-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
              </svg>
              <span>{{ \App\Models\SiteContent::get('hero_cta2_text', 'Explore 5 Pilar ↓') }}</span>
            </a>
          </div>

        </div>

        <!-- Right Column: Kartu Berita & Edukasi Terbaru (Menyatu dengan Background Hero) -->
        <div class="lg:col-span-5 flex justify-center lg:justify-end">
          <div 
            class="relative w-full max-w-[330px] sm:max-w-[360px]" 
            id="heroNewsCardContainer"
            onmouseenter="pauseHeroCarousel()" 
            onmouseleave="resumeHeroCarousel()"
          >
            <!-- Ambient Soft Glow behind Card (Diperkecil & Efek Buram Dikurangi) -->
            <div class="absolute -inset-0.5 bg-gradient-to-r from-[#00BFA6]/20 via-[#43B4F2]/15 to-[#168FE5]/15 rounded-3xl blur-md opacity-40 transition-opacity pointer-events-none"></div>

            <!-- Card Body: Glassmorphism Elegan Menyatu dengan Latar Hero -->
            <div class="relative bg-gradient-to-b from-[#073943]/85 via-[#0a4e5c]/80 to-[#062c35]/90 backdrop-blur-md rounded-3xl p-4 sm:p-5 border border-cyan-400/25 shadow-xl shadow-teal-950/40 text-white transition-all hover:border-cyan-300/40">
              
              <!-- Header: Badge & Carousel Indicators -->
              <div class="flex items-center justify-between gap-2 mb-3">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 bg-cyan-950/70 text-cyan-300 rounded-full text-[10px] font-bold uppercase tracking-wider border border-cyan-400/30 backdrop-blur-sm shadow-xs">
                  <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                  <span>BERITA TERBARU</span>
                </span>
                
                <!-- 3 Indicator Dots -->
                <div class="flex items-center space-x-1.5">
                  @foreach(($topArticles ?? [$latestArticle]) as $idx => $art)
                    <button 
                      type="button" 
                      onclick="goToHeroSlide({{ $idx }})" 
                      id="heroDot{{ $idx }}" 
                      class="h-1.5 rounded-full transition-all duration-300 cursor-pointer {{ $idx === 0 ? 'w-5 bg-cyan-400 shadow-xs' : 'w-1.5 bg-white/30 hover:bg-white/60' }}"
                      title="Berita {{ $idx + 1 }}"
                    ></button>
                  @endforeach
                </div>
              </div>

              <!-- Slider Viewport: overflow-hidden -->
              <div class="overflow-hidden w-full rounded-2xl">
                <div 
                  id="heroNewsSliderTrack" 
                  class="flex transition-transform duration-700 ease-in-out w-full"
                  style="transform: translateX(0%);"
                >
                  @foreach(($topArticles ?? [$latestArticle]) as $idx => $item)
                    <div class="w-full flex-shrink-0">
                      <!-- Compact Thumbnail Foto Berita -->
                      <div class="relative rounded-2xl overflow-hidden h-32 sm:h-36 bg-slate-900/60 mb-3 group shadow-inner border border-white/10">
                        <a href="{{ route('articles.detail', $item['slug'] ?? 'tips-berolahraga-dengan-aktivitas-sehari-hari-menjadi-tetap-bugar') }}" class="block w-full h-full">
                          <img 
                            src="{{ $item['image'] ?? 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=1000&auto=format&fit=crop&q=80' }}" 
                            alt="{{ $item['title'] ?? 'Berita' }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-95 group-hover:opacity-100"
                          >
                          <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-transparent to-transparent"></div>
                        </a>
                        
                        <!-- Badge Kategori (GAYA HIDUP) -->
                        <span class="absolute bottom-2 left-2 px-2.5 py-0.5 bg-[#00BFA6] text-white text-[9px] font-extrabold rounded-full uppercase tracking-wider shadow-sm flex items-center space-x-1">
                          <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                          </svg>
                          <span>{{ $item['category'] ?? 'Gaya Hidup' }}</span>
                        </span>

                        <!-- Badge Tanggal -->
                        <span class="absolute bottom-2 right-2 text-[9px] text-cyan-100 bg-black/65 px-2 py-0.5 rounded-full backdrop-blur-sm border border-white/15 font-medium">
                          📅 {{ $item['date'] ?? '28 Jul 2026' }}
                        </span>
                      </div>

                      <!-- Judul Berita -->
                      <a href="{{ route('articles.detail', $item['slug'] ?? 'tips-berolahraga-dengan-aktivitas-sehari-hari-menjadi-tetap-bugar') }}" class="block group">
                        <h3 class="text-sm sm:text-[15px] font-bold text-white group-hover:text-cyan-300 transition-colors leading-snug line-clamp-2">
                          {{ $item['title'] ?? 'Pola Hidup Sehat, Hindari Risiko Diabetes Melitus Tipe 2' }}
                        </h3>
                      </a>

                      <!-- Keterangan / Deskripsi Berita -->
                      <p class="text-[11px] sm:text-xs text-cyan-100/75 mt-1.5 leading-relaxed line-clamp-2">
                        {{ $item['summary'] ?? 'Kenali langkah pencegahan diabetes melitus tipe 2 melalui gaya hidup sehat, pola makan bernutrisi...' }}
                      </p>

                      <!-- Tombol Aksi & Navigasi Slide -->
                      <div class="mt-3.5 pt-3 border-t border-white/10 flex items-center justify-between">
                        <a 
                          href="{{ route('articles.detail', $item['slug'] ?? 'tips-berolahraga-dengan-aktivitas-sehari-hari-menjadi-tetap-bugar') }}"
                          class="inline-flex items-center space-x-1 text-xs font-bold text-cyan-300 hover:text-cyan-200 transition-colors"
                        >
                          <span>Baca Selengkapnya</span>
                          <span class="text-xs font-bold">&rarr;</span>
                        </a>

                        <div class="flex items-center space-x-2">
                          <button 
                            type="button" 
                            onclick="prevHeroSlide(event)" 
                            class="w-7 h-7 rounded-full bg-white/10 hover:bg-cyan-500/30 border border-white/15 text-cyan-200 hover:text-white flex items-center justify-center text-xs transition-all cursor-pointer shadow-xs"
                            title="Berita Sebelumnya"
                          >
                            &larr;
                          </button>
                          <button 
                            type="button" 
                            onclick="nextHeroSlide(event)" 
                            class="w-7 h-7 rounded-full bg-white/10 hover:bg-cyan-500/30 border border-white/15 text-cyan-200 hover:text-white flex items-center justify-center text-xs transition-all cursor-pointer shadow-xs"
                            title="Berita Selanjutnya"
                          >
                            &rarr;
                          </button>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>

            </div>

          </div>
        </div>

      </div>
    </div>

    <!-- Decorative Bottom-Left Stylized Teal/Cyan Foliage -->
    <div class="absolute bottom-0 left-0 w-44 sm:w-64 h-36 sm:h-52 pointer-events-none z-10 select-none opacity-95">
      <svg viewBox="0 0 260 210" fill="none" class="w-full h-full drop-shadow-md">
        <!-- Leaf 1 (Leftmost large) -->
        <path d="M-10 210 C-10 140, 20 80, 80 50 C100 110, 60 180, -10 210 Z" fill="#00BFA6" fill-opacity="0.9" />
        <!-- Leaf 2 (Center arched) -->
        <path d="M-10 210 C15 150, 70 100, 140 90 C145 140, 90 190, -10 210 Z" fill="#43B4F2" fill-opacity="0.8" />
        <!-- Leaf 3 (Soft smaller foreground) -->
        <path d="M-10 210 C30 170, 75 140, 115 140 C110 175, 60 200, -10 210 Z" fill="#7EE3D6" fill-opacity="0.85" />
      </svg>
    </div>

    <!-- Decorative Bottom-Right Stylized Blue/Cyan Foliage -->
    <div class="absolute bottom-0 right-0 w-44 sm:w-64 h-36 sm:h-52 pointer-events-none z-10 select-none opacity-95">
      <svg viewBox="0 0 260 210" fill="none" class="w-full h-full drop-shadow-md">
        <!-- Leaf 1 (Rightmost tall) -->
        <path d="M270 210 C270 140, 240 80, 180 50 C160 110, 200 180, 270 210 Z" fill="#168FE5" fill-opacity="0.85" />
        <!-- Leaf 2 (Arched towards center) -->
        <path d="M270 210 C245 150, 190 100, 120 90 C115 140, 170 190, 270 210 Z" fill="#43B4F2" fill-opacity="0.85" />
        <!-- Leaf 3 (Small soft cyan) -->
        <path d="M270 210 C230 170, 185 140, 145 140 C150 175, 200 200, 270 210 Z" fill="#00BFA6" fill-opacity="0.9" />
      </svg>
    </div>

    <!-- Soft Bottom Blur Gradient Transition into Page Content -->
    <div class="absolute bottom-0 left-0 right-0 h-28 sm:h-40 pointer-events-none z-[12] bg-gradient-to-t from-white via-white/70 to-transparent backdrop-blur-[2px]"></div>

    <!-- Multi-layered Curved Wave Separator -->
    <div class="hero-wave pointer-events-none z-20">
      <svg viewBox="0 0 1440 96" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-14 sm:h-24 preserve-3d">
        <!-- Back Wave: Translucent Sky Blue (#EAF7FF) & Cyan (#43B4F2) -->
        <path d="M0 96 L1440 96 L1440 50 C1200 20 950 70 650 30 C380 -5 180 40 0 25 Z" fill="#EAF7FF" fill-opacity="0.8"/>
        <path d="M0 96 L1440 96 L1440 65 C1150 35 900 80 550 45 C300 15 120 60 0 40 Z" fill="#00BFA6" fill-opacity="0.25"/>
        <!-- Front Wave: Crisp Clean White transitioning to page body -->
        <path d="M0 96 L1440 96 L1440 60 C1180 90 880 25 500 50 C260 70 110 88 0 96 Z" fill="#FFFFFF"/>
      </svg>
    </div>

  </section>

  <!-- ============================================================== -->
  <!-- SECTION 1: FAKTOR RISIKO & PEMICU DIABETES (PERKENI & WHO)     -->
  <!-- ============================================================== -->
  <div id="tentang-kami" class="scroll-mt-24"></div>
  <section class="py-14 sm:py-20 bg-white" id="sectionPemicu">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="text-center max-w-2xl mx-auto mb-10">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#EAF7FF] text-[#168FE5] border border-[#43B4F2]/30 mb-2">
          Edukasi Medis Berbasis Bukti (Evidence-Based)
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#123B5D] tracking-tight">
          {{ \App\Models\SiteContent::get('pemicu_title', 'Faktor Risiko & Pemicu Diabetes Melitus') }}
        </h2>
        <p class="text-xs sm:text-sm text-[#5C7893] mt-1.5">
          {{ \App\Models\SiteContent::get('pemicu_subtitle', 'Memahami pemicu membantu mendeteksi risiko sejak dini. Risiko diabetes dipengaruhi oleh kombinasi faktor gaya hidup (dapat diubah) dan faktor biologis (tidak dapat diubah).') }}
        </p>
      </div>

      <!-- Kategori 1: Faktor yang Dapat Diubah (Modifiable) -->
      <div class="mb-8">
        <div class="flex items-center space-x-2 mb-4">
          <span class="w-2.5 h-2.5 rounded-full bg-[#00BFA6]"></span>
          <h3 class="text-xs sm:text-sm font-extrabold text-[#123B5D] uppercase tracking-wider">
            Faktor yang Dapat Diubah (Gaya Hidup &amp; Metabolik)
          </h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          
          <div class="bg-gray-50/80 hover:bg-[#EAF7FF]/40 rounded-2xl p-5 border border-gray-100 hover:border-[#43B4F2]/40 transition-all hover:shadow-md text-center group">
            <div class="w-14 h-14 mx-auto mb-3.5 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
              ⚖️
            </div>
            <span class="px-2 py-0.5 bg-[#EAF7FF] text-[#00BFA6] border border-[#00BFA6]/20 text-[10px] font-bold rounded-md uppercase">Dapat Diubah</span>
            <h4 class="font-bold text-[#123B5D] text-sm mt-2 mb-1">Berat Badan &amp; Lemak Perut</h4>
            <p class="text-xs text-[#5C7893] leading-relaxed">Indeks Massa Tubuh (BMI) &ge; 25 dan penumpukan lemak viseral perut dapat memicu resistensi hormon insulin.</p>
          </div>

          <div class="bg-gray-50/80 hover:bg-[#EAF7FF]/40 rounded-2xl p-5 border border-gray-100 hover:border-[#43B4F2]/40 transition-all hover:shadow-md text-center group">
            <div class="w-14 h-14 mx-auto mb-3.5 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
              🥤
            </div>
            <span class="px-2 py-0.5 bg-[#EAF7FF] text-[#00BFA6] border border-[#00BFA6]/20 text-[10px] font-bold rounded-md uppercase">Dapat Diubah</span>
            <h4 class="font-bold text-[#123B5D] text-sm mt-2 mb-1">Pola Makan Tinggi Gula</h4>
            <p class="text-xs text-[#5C7893] leading-relaxed">Konsumsi minuman manis kemasan, karbohidrat olahan sederhana berlebih, dan minim asupan serat sayuran.</p>
          </div>

          <div class="bg-gray-50/80 hover:bg-[#EAF7FF]/40 rounded-2xl p-5 border border-gray-100 hover:border-[#43B4F2]/40 transition-all hover:shadow-md text-center group">
            <div class="w-14 h-14 mx-auto mb-3.5 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
              🛋️
            </div>
            <span class="px-2 py-0.5 bg-[#EAF7FF] text-[#00BFA6] border border-[#00BFA6]/20 text-[10px] font-bold rounded-md uppercase">Dapat Diubah</span>
            <h4 class="font-bold text-[#123B5D] text-sm mt-2 mb-1">Kurang Gerak Fisik</h4>
            <p class="text-xs text-[#5C7893] leading-relaxed">Kebiasaan banyak duduk (sedentary) dan aktivitas fisik kurang dari 150 menit per minggu menurunkan sensitivitas sel otot.</p>
          </div>

          <div class="bg-gray-50/80 hover:bg-[#EAF7FF]/40 rounded-2xl p-5 border border-gray-100 hover:border-[#43B4F2]/40 transition-all hover:shadow-md text-center group">
            <div class="w-14 h-14 mx-auto mb-3.5 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
              🩺
            </div>
            <span class="px-2 py-0.5 bg-[#EAF7FF] text-[#00BFA6] border border-[#00BFA6]/20 text-[10px] font-bold rounded-md uppercase">Dapat Diubah</span>
            <h4 class="font-bold text-[#123B5D] text-sm mt-2 mb-1">Tekanan Darah &amp; Lipid</h4>
            <p class="text-xs text-[#5C7893] leading-relaxed">Riwayat tekanan darah &ge; 140/90 mmHg atau kadar kolesterol HDL rendah dan trigliserida tinggi yang belum terkontrol.</p>
          </div>

        </div>
      </div>

      <!-- Kategori 2: Faktor yang Tidak Dapat Diubah (Non-Modifiable) -->
      <div>
        <div class="flex items-center space-x-2 mb-4">
          <span class="w-2.5 h-2.5 rounded-full bg-[#168FE5]"></span>
          <h3 class="text-xs sm:text-sm font-extrabold text-[#123B5D] uppercase tracking-wider">
            Faktor yang Tidak Dapat Diubah (Biologis &amp; Genetik)
          </h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          
          <div class="bg-gray-50/80 hover:bg-blue-50/40 rounded-2xl p-5 border border-gray-100 hover:border-blue-200 transition-all hover:shadow-md flex items-start space-x-4 group">
            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
              🧬
            </div>
            <div>
              <span class="px-2 py-0.5 bg-[#EAF7FF] text-[#168FE5] border border-[#43B4F2]/30 text-[10px] font-bold rounded-md uppercase">Faktor Genetik</span>
              <h4 class="font-bold text-[#123B5D] text-sm mt-1.5 mb-1">Riwayat Keluarga Inti</h4>
              <p class="text-xs text-[#5C7893] leading-relaxed">Memiliki orang tua atau saudara kandung dengan diabetes meningkatkan kecenderungan kerentanan biologis, namun risiko tetap dapat ditekan dengan pola hidup sehat.</p>
            </div>
          </div>

          <div class="bg-gray-50/80 hover:bg-blue-50/40 rounded-2xl p-5 border border-gray-100 hover:border-blue-200 transition-all hover:shadow-md flex items-start space-x-4 group">
            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
              ⏳
            </div>
            <div>
              <span class="px-2 py-0.5 bg-[#EAF7FF] text-[#168FE5] border border-[#43B4F2]/30 text-[10px] font-bold rounded-md uppercase">Faktor Biologis</span>
              <h4 class="font-bold text-[#123B5D] text-sm mt-1.5 mb-1">Usia &amp; Riwayat Khusus</h4>
              <p class="text-xs text-[#5C7893] leading-relaxed">Risiko meningkat secara alami pada usia &ge; 35 tahun akibat penurunan fungsi metabolisme sel, serta pada wanita dengan riwayat diabetes saat masa kehamilan (gestasional).</p>
            </div>
          </div>

        </div>
      </div>

      <!-- Edukasi Damai Tanpa Menakut-nakuti -->
      <div class="mt-8 p-4 bg-[#EAF7FF]/60 rounded-2xl border border-[#43B4F2]/25 text-xs text-[#5C7893] leading-relaxed flex items-start space-x-3">
        <span class="text-[#168FE5] text-base flex-shrink-0">💡</span>
        <p>
          <strong class="text-[#123B5D] font-bold">Catatan Edukasi:</strong> {{ \App\Models\SiteContent::get('pemicu_note', 'Adanya satu atau lebih faktor pemicu di atas bukan berarti Anda pasti terkena diabetes. Faktor tersebut merupakan indikator probabilitas. Pencegahan melalui kebiasaan sehat harian terbukti secara klinis mampu menurunkan risiko hingga lebih dari 58%.') }}
        </p>
      </div>

    </div>
  </section>

  <!-- ============================================================== -->
  <!-- SECTION 2: 5 PILAR PENGENDALIAN DIABETES (INTERAKTIF & SIMPEL) -->
  <!-- ============================================================== -->
  <section class="py-14 sm:py-18 bg-gray-50/80 border-t border-gray-100" id="sectionPilar">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="text-center max-w-xl mx-auto mb-8">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#EAF7FF] text-[#168FE5] border border-[#43B4F2]/30 mb-2">
          Panduan Praktis PERKENI & WHO
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#123B5D] tracking-tight">
          {{ \App\Models\SiteContent::get('pilar_title', '5 Pilar Utama Pengendalian & Pencegahan Diabetes') }}
        </h2>
        <p class="text-xs sm:text-sm text-[#5C7893] mt-1">{{ \App\Models\SiteContent::get('pilar_subtitle', 'Pilih salah satu pilar untuk mencoba alat interaktif dan panduan ringkasnya.') }}</p>
      </div>

      <!-- 5 Interactive Selector Cards -->
      <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        
        <button 
          type="button" 
          onclick="activatePillar(1)" 
          id="pilarNav1"
          class="pilar-nav-btn active flex flex-col items-center justify-center p-3.5 sm:p-4 rounded-2xl bg-white border-2 border-kd-green shadow-sm transition-all text-center cursor-pointer"
        >
          <span class="text-2xl sm:text-3xl mb-1">🥗</span>
          <span class="text-xs font-bold text-[#123B5D]">1. Pola Makan</span>
          <span class="text-[10px] text-[#00BFA6] font-semibold mt-0.5">Seimbang (3J)</span>
        </button>

        <button 
          type="button" 
          onclick="activatePillar(2)" 
          id="pilarNav2"
          class="pilar-nav-btn flex flex-col items-center justify-center p-3.5 sm:p-4 rounded-2xl bg-white border-2 border-transparent hover:border-gray-200 shadow-sm transition-all text-center cursor-pointer"
        >
          <span class="text-2xl sm:text-3xl mb-1">🏃</span>
          <span class="text-xs font-bold text-[#123B5D]">2. Aktivitas Fisik</span>
          <span class="text-[10px] text-[#5C7893] font-semibold mt-0.5">Sesuai Kondisi</span>
        </button>

        <button 
          type="button" 
          onclick="activatePillar(3)" 
          id="pilarNav3"
          class="pilar-nav-btn flex flex-col items-center justify-center p-3.5 sm:p-4 rounded-2xl bg-white border-2 border-transparent hover:border-gray-200 shadow-sm transition-all text-center cursor-pointer"
        >
          <span class="text-2xl sm:text-3xl mb-1">🩸</span>
          <span class="text-xs font-bold text-[#123B5D]">3. Cek Berkala</span>
          <span class="text-[10px] text-[#5C7893] font-semibold mt-0.5">Gula Darah & Lab</span>
        </button>

        <button 
          type="button" 
          onclick="activatePillar(4)" 
          id="pilarNav4"
          class="pilar-nav-btn flex flex-col items-center justify-center p-3.5 sm:p-4 rounded-2xl bg-white border-2 border-transparent hover:border-gray-200 shadow-sm transition-all text-center cursor-pointer"
        >
          <span class="text-2xl sm:text-3xl mb-1">🌙</span>
          <span class="text-xs font-bold text-[#123B5D]">4. Tidur & Stres</span>
          <span class="text-[10px] text-[#5C7893] font-semibold mt-0.5">Kelola Kortisol</span>
        </button>

        <button 
          type="button" 
          onclick="activatePillar(5)" 
          id="pilarNav5"
          class="pilar-nav-btn col-span-2 sm:col-span-1 flex flex-col items-center justify-center p-3.5 sm:p-4 rounded-2xl bg-white border-2 border-transparent hover:border-gray-200 shadow-sm transition-all text-center cursor-pointer"
        >
          <span class="text-2xl sm:text-3xl mb-1">👨‍⚕️</span>
          <span class="text-xs font-bold text-[#123B5D]">5. Edukasi & Nakes</span>
          <span class="text-[10px] text-[#5C7893] font-semibold mt-0.5">Konsultasi Medis</span>
        </button>

      </div>

      <!-- ============================================================ -->
      <!-- UNIFIED INTERACTIVE WORKSPACE CARD                           -->
      <!-- ============================================================ -->
      <div class="bg-white rounded-3xl p-5 sm:p-8 border border-gray-200 shadow-sm transition-all">
        
        <!-- ========================================================== -->
        <!-- VIEW 1: POLA MAKAN SEHAT                                   -->
        <!-- ========================================================== -->
        <div id="viewPillar1" class="pillar-view space-y-6">
          
          <!-- Quick 3J Summary Badges -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="flex items-center space-x-3 p-3 bg-[#EAF7FF]/60 rounded-2xl border border-[#43B4F2]/20">
              <span class="text-2xl">⏰</span>
              <div>
                <strong class="text-xs font-extrabold text-[#123B5D] block">Jadwal Teratur</strong>
                <span class="text-[11px] text-[#5C7893]">3x makan utama & 2x selingan ringan</span>
              </div>
            </div>
            <div class="flex items-center space-x-3 p-3 bg-[#EAF7FF]/60 rounded-2xl border border-[#43B4F2]/20">
              <span class="text-2xl">⚖️</span>
              <div>
                <strong class="text-xs font-extrabold text-[#123B5D] block">Piring Model T</strong>
                <span class="text-[11px] text-[#5C7893]">½ sayur, ¼ karbohidrat, ¼ lauk sehat</span>
              </div>
            </div>
            <div class="flex items-center space-x-3 p-3 bg-[#EAF7FF]/60 rounded-2xl border border-[#43B4F2]/20">
              <span class="text-2xl">🥦</span>
              <div>
                <strong class="text-xs font-extrabold text-[#123B5D] block">Jenis Karbo Kompleks</strong>
                <span class="text-[11px] text-[#5C7893]">Pilih IG rendah (oat, beras merah, sayur)</span>
              </div>
            </div>
          </div>

          <!-- Interactive Fast Calorie Estimator -->
          <div class="bg-gray-50/80 rounded-2xl p-5 border border-gray-200/80">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              
              <div class="grid grid-cols-3 gap-3 flex-1">
                <div>
                  <label class="block text-[11px] font-bold text-[#5C7893] mb-1">Berat (Kg)</label>
                  <input type="number" id="quickWeight" value="65" oninput="quickDietCalc()" class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl p-2.5">
                </div>
                <div>
                  <label class="block text-[11px] font-bold text-[#5C7893] mb-1">Tinggi (Cm)</label>
                  <input type="number" id="quickHeight" value="170" oninput="quickDietCalc()" class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl p-2.5">
                </div>
                <div>
                  <label class="block text-[11px] font-bold text-[#5C7893] mb-1">Gender</label>
                  <select id="quickGender" onchange="quickDietCalc()" class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl p-2.5">
                    <option value="m">Pria</option>
                    <option value="f">Wanita</option>
                  </select>
                </div>
              </div>

              <!-- Output Badge -->
              <div class="sm:w-56 p-3 bg-white rounded-2xl border border-[#00BFA6]/40 shadow-sm text-center">
                <span class="text-[10px] uppercase font-bold text-[#5C7893] block">Kebutuhan Kalori Harian</span>
                <span id="quickCalResult" class="text-xl font-black text-kd-green">~2.050 kkal</span>
                <span class="text-[10px] text-[#5C7893] block mt-0.5">Batas Gula: <strong>Maks 4 sdm/hari</strong></span>
              </div>

            </div>
          </div>

          <!-- Swap Chips -->
          <div class="flex flex-wrap items-center gap-2 text-xs pt-1">
            <span class="text-[#5C7893] font-bold">Tips Cepat:</span>
            <span class="px-3 py-1 bg-gray-100 rounded-full text-gray-700">❌ Nasi Pulen &rarr; <strong class="text-[#00BFA6]">✅ Nasi Merah / Jagung</strong></span>
            <span class="px-3 py-1 bg-gray-100 rounded-full text-gray-700">❌ Minuman Boba &rarr; <strong class="text-[#00BFA6]">✅ Air Mineral</strong></span>
            <span class="px-3 py-1 bg-gray-100 rounded-full text-gray-700">❌ Gorengan &rarr; <strong class="text-[#00BFA6]">✅ Pepes / Kukus</strong></span>
          </div>

        </div>

        <!-- ========================================================== -->
        <!-- VIEW 2: LATIHAN JASMANI                                    -->
        <!-- ========================================================== -->
        <div id="viewPillar2" class="pillar-view hidden space-y-6">
          
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-blue-50/70 rounded-2xl border border-blue-100">
            <div>
              <h4 class="font-extrabold text-blue-900 text-sm">Target Mingguan: 150 Menit (3-5 Hari)</h4>
              <p class="text-xs text-gray-600 mt-0.5">Olahraga rutin meningkatkan sensitivitas insulin otot hingga 48 jam.</p>
            </div>
            <div class="text-center sm:text-right">
              <span id="exDisplayMin" class="text-2xl font-black text-blue-600">0</span>
              <span class="text-xs font-bold text-gray-500">/ 150 Menit</span>
            </div>
          </div>

          <!-- Interactive 1-Tap Weekday Tracker -->
          <div>
            <label class="block text-xs font-bold text-gray-700 mb-2">Pilih sesi olahraga Anda minggu ini (Tap untuk centang):</label>
            <div class="grid grid-cols-5 gap-2 sm:gap-3">
              <button type="button" onclick="toggleExDay(this, 30)" class="ex-day-btn p-3 rounded-2xl border-2 border-gray-200 bg-gray-50 font-bold text-xs text-gray-700 hover:border-blue-400 transition-all flex flex-col items-center">
                <span>Senin</span>
                <span class="text-[10px] text-gray-400 font-normal mt-1">+30 mnt</span>
              </button>
              <button type="button" onclick="toggleExDay(this, 30)" class="ex-day-btn p-3 rounded-2xl border-2 border-gray-200 bg-gray-50 font-bold text-xs text-gray-700 hover:border-blue-400 transition-all flex flex-col items-center">
                <span>Rabu</span>
                <span class="text-[10px] text-gray-400 font-normal mt-1">+30 mnt</span>
              </button>
              <button type="button" onclick="toggleExDay(this, 30)" class="ex-day-btn p-3 rounded-2xl border-2 border-gray-200 bg-gray-50 font-bold text-xs text-gray-700 hover:border-blue-400 transition-all flex flex-col items-center">
                <span>Jumat</span>
                <span class="text-[10px] text-gray-400 font-normal mt-1">+30 mnt</span>
              </button>
              <button type="button" onclick="toggleExDay(this, 30)" class="ex-day-btn p-3 rounded-2xl border-2 border-gray-200 bg-gray-50 font-bold text-xs text-gray-700 hover:border-blue-400 transition-all flex flex-col items-center">
                <span>Sabtu</span>
                <span class="text-[10px] text-gray-400 font-normal mt-1">+30 mnt</span>
              </button>
              <button type="button" onclick="toggleExDay(this, 30)" class="ex-day-btn p-3 rounded-2xl border-2 border-gray-200 bg-gray-50 font-bold text-xs text-gray-700 hover:border-blue-400 transition-all flex flex-col items-center">
                <span>Minggu</span>
                <span class="text-[10px] text-gray-400 font-normal mt-1">+30 mnt</span>
              </button>
            </div>
            
            <!-- Progress Bar -->
            <div class="mt-4">
              <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                <div id="exBar" class="bg-blue-500 h-full w-0 transition-all duration-300"></div>
              </div>
              <span id="exStatusTxt" class="text-xs text-gray-500 font-medium block mt-1.5">Klik salah satu hari untuk mencatat latihan.</span>
            </div>
          </div>

          <!-- Quick 3 Rules -->
          <div class="grid grid-cols-3 gap-2 text-center text-xs">
            <div class="p-2.5 bg-gray-50 rounded-xl border">👟 Pakai sepatu pas</div>
            <div class="p-2.5 bg-gray-50 rounded-xl border">🍬 Sediakan permen</div>
            <div class="p-2.5 bg-gray-50 rounded-xl border">⏱️ Jalan cepat 30 mnt</div>
          </div>

        </div>

        <!-- ========================================================== -->
        <!-- VIEW 3: PEMERIKSAAN KESEHATAN BERKALA                      -->
        <!-- ========================================================== -->
        <div id="viewPillar3" class="pillar-view hidden space-y-5">
          
          <div class="p-3.5 bg-blue-50/70 rounded-2xl border border-blue-100 flex items-start space-x-3">
            <span class="text-2xl flex-shrink-0">🩺</span>
            <div>
              <h4 class="font-extrabold text-blue-900 text-xs sm:text-sm">Pemeriksaan Rutin: Deteksi Sebelum Komplikasi</h4>
              <p class="text-[11px] sm:text-xs text-gray-600 mt-0.5 leading-relaxed">
                Diabetes sering kali tidak bergejala pada tahap awal. Skrining berkala (Gula Darah Puasa, GDS, HbA1c, dan Tekanan Darah) minimal 3-6 bulan sekali sangat disarankan untuk mengetahui status metabolik sedini mungkin.
              </p>
            </div>
          </div>

          <!-- Blood Sugar Levels Infographic Illustration -->
          <div class="rounded-2xl overflow-hidden border border-gray-100 bg-white p-3 flex justify-center shadow-sm">
            <img src="{{ asset('assets/blood_sugar_levels.png') }}" alt="Blood Sugar Levels Chart" class="max-h-60 sm:max-h-72 object-contain rounded-xl">
          </div>

          <div class="flex flex-col sm:flex-row items-center gap-4">
            
            <div class="flex-1 w-full space-y-3">
              <label class="block text-xs font-bold text-gray-700">Simulasi Kondisi Tes & Masukkan Angka:</label>
              
              <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="setGluPreset('gdp')" id="btnGdp" class="glu-type-btn active py-2 text-xs font-bold rounded-xl border-2 border-kd-green bg-emerald-50 text-kd-green">
                  Puasa (GDP)
                </button>
                <button type="button" onclick="setGluPreset('gd2pp')" id="btnGd2pp" class="glu-type-btn py-2 text-xs font-bold rounded-xl border-2 border-gray-200 bg-white text-gray-700">
                  2 Jam PP
                </button>
                <button type="button" onclick="setGluPreset('gds')" id="btnGds" class="glu-type-btn py-2 text-xs font-bold rounded-xl border-2 border-gray-200 bg-white text-gray-700">
                  Sewaktu (GDS)
                </button>
              </div>

              <div class="relative flex items-center">
                <input 
                  type="number" 
                  id="fastGluVal" 
                  value="110" 
                  oninput="evalQuickGlu()"
                  class="w-full text-lg font-black text-gray-800 bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-3 outline-none"
                >
                <span class="absolute right-3 text-xs font-bold text-gray-400 pointer-events-none">mg/dL</span>
              </div>
            </div>

            <!-- Instant Status Card -->
            <div id="fastGluCard" class="w-full sm:w-64 p-4 rounded-2xl bg-amber-50 border border-amber-300 text-center transition-all">
              <span id="fastGluIcon" class="text-3xl block mb-1">⚠️</span>
              <span id="fastGluStatus" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 inline-block uppercase">
                Pra-Diabetes
              </span>
              <span id="fastGluRange" class="text-[11px] text-gray-600 block mt-2">Rentang 100 - 125 mg/dL. Mulai perbaiki pola makan & olahraga.</span>
            </div>

          </div>

          <!-- Standard Target Reference -->
          <div class="grid grid-cols-3 gap-2 text-center text-[11px] text-gray-600 border-t pt-3">
            <div>🟢 Normal: <strong>&lt; 100 mg/dL</strong></div>
            <div>🟡 Pra-Diabetes: <strong>100 - 125 mg/dL</strong></div>
            <div>🔴 Tinggi: <strong>&ge; 126 mg/dL</strong></div>
          </div>

        </div>

        <!-- ========================================================== -->
        <!-- VIEW 4: TIDUR YANG CUKUP & PENGELOLAAN STRES               -->
        <!-- ========================================================== -->
        <div id="viewPillar4" class="pillar-view hidden space-y-5">
          
          <div class="p-4 bg-indigo-50/70 rounded-2xl border border-indigo-100 flex items-start space-x-3">
            <span class="text-2xl flex-shrink-0">🌙</span>
            <div>
              <h4 class="font-extrabold text-indigo-900 text-xs sm:text-sm">Keseimbangan Hormon & Sensitivitas Insulin</h4>
              <p class="text-[11px] sm:text-xs text-gray-600 mt-0.5 leading-relaxed">
                Stres berkepanjangan memicu pelepasan hormon kortisol dan adrenalin yang meningkatkan resistensi insulin. Kurang tidur (&lt; 6 jam) terbukti mengganggu metabolisme glukosa tubuh hingga 30%.
              </p>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            
            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 flex flex-col justify-between">
              <div>
                <span class="text-2xl">🛌</span>
                <h5 class="text-xs font-extrabold text-gray-900 mt-2 mb-1">Durasi Tidur 7-8 Jam</h5>
                <p class="text-[11px] text-gray-500 leading-relaxed">
                  Tidur cukup memberi waktu bagi sel beta pankreas dan hati untuk meregulasi produksi glukosa basal.
                </p>
              </div>
              <span class="text-[10px] font-bold text-indigo-600 mt-3 block">Jadwal Tidur Konsisten</span>
            </div>

            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 flex flex-col justify-between">
              <div>
                <span class="text-2xl">🧘</span>
                <h5 class="text-xs font-extrabold text-gray-900 mt-2 mb-1">Relaksasi & Napas 4-7-8</h5>
                <p class="text-[11px] text-gray-500 leading-relaxed">
                  Lakukan pernapasan dalam 5 menit saat penat untuk menurunkan detak jantung dan menekan lonjakan kortisol.
                </p>
              </div>
              <span class="text-[10px] font-bold text-emerald-600 mt-3 block">Manajemen Stres Aktif</span>
            </div>

            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 flex flex-col justify-between">
              <div>
                <span class="text-2xl">📱</span>
                <h5 class="text-xs font-extrabold text-gray-900 mt-2 mb-1">Higienitas Tidur (No Blue Light)</h5>
                <p class="text-[11px] text-gray-500 leading-relaxed">
                  Hindari layar ponsel 30-60 menit sebelum tidur untuk memastikan sekresi melatonin berlangsung optimal.
                </p>
              </div>
              <span class="text-[10px] font-bold text-amber-600 mt-3 block">Kualitas Tidur Nyenyak</span>
            </div>

          </div>

          <div class="p-3 bg-indigo-50/50 rounded-xl border border-indigo-100 text-xs text-indigo-900 flex items-center justify-between">
            <span>✨ <em>"Pikiran yang tenang dan tidur yang cukup adalah obat alami terbaik bagi kestabilan gula darah."</em></span>
          </div>

        </div>

        <!-- ========================================================== -->
        <!-- VIEW 5: EDUKASI SERTA KONSULTASI TENAGA KESEHATAN          -->
        <!-- ========================================================== -->
        <div id="viewPillar5" class="pillar-view hidden space-y-5">
          
          <div class="p-4 bg-emerald-50/70 rounded-2xl border border-emerald-100 flex items-start space-x-3">
            <span class="text-2xl flex-shrink-0">👨‍⚕️</span>
            <div>
              <h4 class="font-extrabold text-emerald-950 text-xs sm:text-sm">Bimbingan Profesional & Kepatuhan Terapi</h4>
              <p class="text-[11px] sm:text-xs text-gray-600 mt-0.5 leading-relaxed">
                Edukasi mandiri dan kemitraan aktif dengan dokter serta tenaga kesehatan (nutrisionis/edukator diabetes) memastikan rencana terapi dan pencegahan berjalan aman dan terarah.
              </p>
            </div>
          </div>

          <!-- Panduan Kepatuhan Terapi Obat Singkat -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-200">
              <span class="text-xl">🍽️</span>
              <h5 class="text-xs font-bold text-gray-900 mt-1.5">Metformin</h5>
              <span class="text-[10px] text-kd-green font-bold block mb-1">Saat / Sesudah Makan</span>
              <p class="text-[11px] text-gray-500">Membantu sensitivitas sel terhadap insulin.</p>
            </div>

            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-200">
              <span class="text-xl">🌅</span>
              <h5 class="text-xs font-bold text-gray-900 mt-1.5">Sulfonilurea</h5>
              <span class="text-[10px] text-amber-700 font-bold block mb-1">15-30 Mnt Sebelum Makan</span>
              <p class="text-[11px] text-gray-500">Memicu pelepasan insulin alami dari pankreas.</p>
            </div>

            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-200">
              <span class="text-xl">💉</span>
              <h5 class="text-xs font-bold text-gray-900 mt-1.5">Terapi Insulin</h5>
              <span class="text-[10px] text-blue-700 font-bold block mb-1">Sesuai Jadwal Tetap Dokter</span>
              <p class="text-[11px] text-gray-500">Simpan di suhu sejuk dan gunakan jarum steril.</p>
            </div>
          </div>

          <!-- Kuis Mitos vs Fakta -->
          <div class="pt-2">
            <div class="flex items-center justify-between text-xs pb-2">
              <span class="font-bold text-gray-700">Uji Literasi Medis Anda (Tap Mitos atau Fakta):</span>
              <span id="kScore" class="font-extrabold text-kd-green">Skor: 0/3</span>
            </div>

            <div class="space-y-2.5">
              <!-- Q1 -->
              <div class="p-3 bg-gray-50 rounded-xl border flex items-center justify-between gap-3" id="qCard1">
                <span class="text-xs text-gray-800 font-medium">1. "Madu murni bebas dikonsumsi penderita diabetes tanpa batas."</span>
                <div class="flex items-center space-x-1.5 flex-shrink-0">
                  <button type="button" onclick="kAns(1, 'mitos', true)" class="k-btn-1 px-3 py-1 bg-white border rounded-lg text-xs font-bold hover:bg-gray-100">Mitos</button>
                  <button type="button" onclick="kAns(1, 'fakta', false)" class="k-btn-1 px-3 py-1 bg-white border rounded-lg text-xs font-bold hover:bg-gray-100">Fakta</button>
                </div>
              </div>

              <!-- Q2 -->
              <div class="p-3 bg-gray-50 rounded-xl border flex items-center justify-between gap-3" id="qCard2">
                <span class="text-xs text-gray-800 font-medium">2. "Orang bertubuh kurus juga berisiko terkena diabetes tipe 2."</span>
                <div class="flex items-center space-x-1.5 flex-shrink-0">
                  <button type="button" onclick="kAns(2, 'mitos', false)" class="k-btn-2 px-3 py-1 bg-white border rounded-lg text-xs font-bold hover:bg-gray-100">Mitos</button>
                  <button type="button" onclick="kAns(2, 'fakta', true)" class="k-btn-2 px-3 py-1 bg-white border rounded-lg text-xs font-bold hover:bg-gray-100">Fakta</button>
                </div>
              </div>

              <!-- Q3 -->
              <div class="p-3 bg-gray-50 rounded-xl border flex items-center justify-between gap-3" id="qCard3">
                <span class="text-xs text-gray-800 font-medium">3. "Olahraga teratur membantu sel otot menyerap gula darah lebih efektif."</span>
                <div class="flex items-center space-x-1.5 flex-shrink-0">
                  <button type="button" onclick="kAns(3, 'mitos', false)" class="k-btn-3 px-3 py-1 bg-white border rounded-lg text-xs font-bold hover:bg-gray-100">Mitos</button>
                  <button type="button" onclick="kAns(3, 'fakta', true)" class="k-btn-3 px-3 py-1 bg-white border rounded-lg text-xs font-bold hover:bg-gray-100">Fakta</button>
                </div>
              </div>
            </div>
          </div>

          <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900 flex items-center justify-between">
            <span>⚠️ <strong>Penting:</strong> Jangan mengubah dosis obat atau terapi tanpa instruksi dokter penanggung jawab.</span>
            <a href="https://www.klikdokter.com" target="_blank" class="font-bold underline text-xs ml-2">Konsultasi Dokter &rarr;</a>
          </div>

        </div>

      </div>

      <!-- Edukasi Damai & Disclaimer Medis Pilar -->
      <div class="mt-4 p-4 bg-emerald-50/70 rounded-2xl border border-emerald-100 text-xs text-gray-600 leading-relaxed flex items-start space-x-3">
        <span class="text-emerald-700 text-base flex-shrink-0">💡</span>
        <p>
          <strong class="text-emerald-950 font-bold">Catatan Pencegahan:</strong> {{ \App\Models\SiteContent::get('pilar_disclaimer', 'Penerapan 5 pilar kebiasaan sehat di atas terbukti klinis dapat menurunkan risiko diabetes dan komplikasi metabolik secara signifikan. Langkah ini adalah ikhtiar pencegahan dan bukan jaminan mutlak 100% bebas diabetes, karena faktor keturunan dan biologis tetap memiliki peranan. Konsultasikan status kesehatan Anda secara teratur kepada tenaga medis.') }}
        </p>
      </div>

    </div>
  </section>

@endsection

@push('scripts')
<script>
  let activePillarIndex = 1;

  function activatePillar(idx) {
    activePillarIndex = idx;

    // Toggle Nav Buttons
    for (let i = 1; i <= 5; i++) {
      const btn = document.getElementById(`pilarNav${i}`);
      const view = document.getElementById(`viewPillar${i}`);

      if (btn) {
        if (i === idx) {
          btn.className = 'pilar-nav-btn active flex flex-col items-center justify-center p-3.5 sm:p-4 rounded-2xl bg-white border-2 border-kd-green shadow-sm transition-all text-center cursor-pointer';
          const sub = btn.querySelector('span:nth-child(3)');
          if (sub) sub.className = 'text-[10px] text-kd-green font-semibold mt-0.5';
        } else {
          btn.className = 'pilar-nav-btn flex flex-col items-center justify-center p-3.5 sm:p-4 rounded-2xl bg-white border-2 border-transparent hover:border-gray-200 shadow-sm transition-all text-center cursor-pointer';
          const sub = btn.querySelector('span:nth-child(3)');
          if (sub) sub.className = 'text-[10px] text-gray-400 font-semibold mt-0.5';
        }
      }

      if (view) {
        if (i === idx) {
          view.classList.remove('hidden');
        } else {
          view.classList.add('hidden');
        }
      }
    }
  }

  // Tool 1: Fast Diet Calculator
  function quickDietCalc() {
    const w = parseFloat(document.getElementById('quickWeight').value) || 65;
    const h = parseFloat(document.getElementById('quickHeight').value) || 170;
    const g = document.getElementById('quickGender').value;

    let bmr = (10 * w) + (6.25 * h) - (5 * 35);
    bmr += (g === 'm' ? 5 : -161);
    const tdee = Math.round(bmr * 1.375);

    document.getElementById('quickCalResult').innerText = `~${tdee.toLocaleString('id-ID')} kkal`;
  }

  // Tool 2: Exercise Tracker
  let exerciseMinutes = 0;
  function toggleExDay(btn, mins) {
    const isChecked = btn.classList.contains('bg-blue-600');
    if (isChecked) {
      btn.className = 'ex-day-btn p-3 rounded-2xl border-2 border-gray-200 bg-gray-50 font-bold text-xs text-gray-700 hover:border-blue-400 transition-all flex flex-col items-center';
      exerciseMinutes = Math.max(0, exerciseMinutes - mins);
    } else {
      btn.className = 'ex-day-btn p-3 rounded-2xl border-2 border-blue-600 bg-blue-600 font-bold text-xs text-white shadow-sm transition-all flex flex-col items-center';
      exerciseMinutes += mins;
    }

    const percent = Math.min(100, Math.round((exerciseMinutes / 150) * 100));
    document.getElementById('exDisplayMin').innerText = exerciseMinutes;
    document.getElementById('exBar').style.width = `${percent}%`;

    const txt = document.getElementById('exStatusTxt');
    if (percent >= 100) {
      txt.innerText = '🎉 Target 150 menit minggu ini tercapai sempurna!';
      txt.className = 'text-xs text-emerald-600 font-bold block mt-1.5';
    } else {
      txt.innerText = `Pencapaian: ${percent}% dari target rekomendasi klinis.`;
      txt.className = 'text-xs text-gray-500 font-medium block mt-1.5';
    }
  }

  // Tool 4: Fast Blood Sugar Evaluator
  let currentGluType = 'gdp';

  function setGluPreset(type) {
    currentGluType = type;
    ['btnGdp', 'btnGd2pp', 'btnGds'].forEach(id => {
      const btn = document.getElementById(id);
      if (btn) {
        btn.className = 'glu-type-btn py-2 text-xs font-bold rounded-xl border-2 border-gray-200 bg-white text-gray-700';
      }
    });

    if (type === 'gdp') {
      document.getElementById('btnGdp').className = 'glu-type-btn active py-2 text-xs font-bold rounded-xl border-2 border-kd-green bg-emerald-50 text-kd-green';
    } else if (type === 'gd2pp') {
      document.getElementById('btnGd2pp').className = 'glu-type-btn active py-2 text-xs font-bold rounded-xl border-2 border-kd-green bg-emerald-50 text-kd-green';
    } else {
      document.getElementById('btnGds').className = 'glu-type-btn active py-2 text-xs font-bold rounded-xl border-2 border-kd-green bg-emerald-50 text-kd-green';
    }

    evalQuickGlu();
  }

  function evalQuickGlu() {
    const val = parseFloat(document.getElementById('fastGluVal').value) || 0;
    const card = document.getElementById('fastGluCard');
    const badge = document.getElementById('fastGluStatus');
    const icon = document.getElementById('fastGluIcon');
    const range = document.getElementById('fastGluRange');

    let status = 'normal';
    let label = 'Normal';
    let desc = '';

    if (currentGluType === 'gdp') {
      if (val < 100) {
        status = 'normal';
        label = 'Normal';
        desc = 'Kadar puasa ideal (< 100 mg/dL). Tubuh memproses gula dengan sangat baik.';
      } else if (val <= 125) {
        status = 'pre';
        label = 'Pra-Diabetes';
        desc = 'Rentang 100 - 125 mg/dL. Tingkatkan olahraga & batasi gula.';
      } else {
        status = 'high';
        label = 'Tinggi / Diabetes';
        desc = 'Hasil ≥ 126 mg/dL tergolong tinggi. Disarankan evaluasi dokter.';
      }
    } else {
      if (val < 140) {
        status = 'normal';
        label = 'Normal';
        desc = 'Gula darah pasca makan normal (< 140 mg/dL).';
      } else if (val <= 199) {
        status = 'pre';
        label = 'Pra-Diabetes';
        desc = 'Rentang 140 - 199 mg/dL. Waspadai asupan karbohidrat tinggi.';
      } else {
        status = 'high';
        label = 'Tinggi / Diabetes';
        desc = 'Hasil ≥ 200 mg/dL mengindikasikan beban gula tinggi.';
      }
    }

    if (status === 'normal') {
      icon.innerText = '✅';
      badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 inline-block uppercase';
      card.className = 'w-full sm:w-64 p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-center transition-all';
    } else if (status === 'pre') {
      icon.innerText = '⚠️';
      badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 inline-block uppercase';
      card.className = 'w-full sm:w-64 p-4 rounded-2xl bg-amber-50 border border-amber-300 text-center transition-all';
    } else {
      icon.innerText = '🚨';
      badge.className = 'px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 inline-block uppercase';
      card.className = 'w-full sm:w-64 p-4 rounded-2xl bg-rose-50 border border-rose-300 text-center transition-all';
    }

    badge.innerText = label;
    range.innerText = desc;
  }

  // Tool 5: Quick Quiz
  let quizKCount = 0;
  const answeredK = {};

  function kAns(qNum, choice, isCorrect) {
    if (answeredK[qNum]) return;
    answeredK[qNum] = true;

    const card = document.getElementById(`qCard${qNum}`);
    const btns = document.querySelectorAll(`.k-btn-${qNum}`);
    btns.forEach(b => b.disabled = true);

    if (isCorrect) {
      quizKCount++;
      card.className = 'p-3.5 bg-emerald-50 rounded-2xl border border-emerald-300 flex items-center justify-between gap-3';
    } else {
      card.className = 'p-3.5 bg-rose-50 rounded-2xl border border-rose-300 flex items-center justify-between gap-3';
    }

    document.getElementById('kScore').innerText = `Skor: ${quizKCount}/3`;
  }

  // ==============================================================
  // HERO 3-ARTICLES AUTO-SLIDING HORIZONTAL CAROUSEL
  // ==============================================================
  let currentHeroIdx = 0;
  const totalHeroSlides = {{ count($topArticles ?? [$latestArticle]) }};
  let heroCarouselTimer = null;

  function updateHeroSlider(targetIdx) {
    const track = document.getElementById('heroNewsSliderTrack');
    if (track) {
      track.style.transform = `translateX(-${targetIdx * 100}%)`;
    }

    for (let i = 0; i < totalHeroSlides; i++) {
      const dot = document.getElementById(`heroDot${i}`);
      if (!dot) continue;
      if (i === targetIdx) {
        dot.className = 'h-1.5 rounded-full transition-all duration-300 cursor-pointer w-5 bg-cyan-400 shadow-xs';
      } else {
        dot.className = 'h-1.5 rounded-full transition-all duration-300 cursor-pointer w-1.5 bg-white/30 hover:bg-white/60';
      }
    }
    currentHeroIdx = targetIdx;
  }

  function nextHeroSlide(e) {
    if (e) e.stopPropagation();
    if (totalHeroSlides <= 1) return;
    const next = (currentHeroIdx + 1) % totalHeroSlides;
    updateHeroSlider(next);
  }

  function prevHeroSlide(e) {
    if (e) e.stopPropagation();
    if (totalHeroSlides <= 1) return;
    const prev = (currentHeroIdx - 1 + totalHeroSlides) % totalHeroSlides;
    updateHeroSlider(prev);
  }

  function goToHeroSlide(idx) {
    updateHeroSlider(idx);
    resetHeroTimer();
  }

  function startHeroTimer() {
    if (totalHeroSlides <= 1) return;
    if (heroCarouselTimer) clearInterval(heroCarouselTimer);
    heroCarouselTimer = setInterval(() => {
      const next = (currentHeroIdx + 1) % totalHeroSlides;
      updateHeroSlider(next);
    }, 3000);
  }

  function resetHeroTimer() {
    startHeroTimer();
  }

  function pauseHeroCarousel() {
    // Optional gentle pause or keep running
    if (heroCarouselTimer) clearInterval(heroCarouselTimer);
  }

  function resumeHeroCarousel() {
    startHeroTimer();
  }

  // Auto start immediately
  startHeroTimer();
  window.addEventListener('load', startHeroTimer);
</script>
@endpush

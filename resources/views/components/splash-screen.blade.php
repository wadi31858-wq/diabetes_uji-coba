<!-- ============================================================== -->
<!-- 2026 SPLASH SCREEN / WELCOME SCREEN OVERLAY COMPONENT           -->
<!-- Seamlessly reveals homepage upon reaching 100% completion     -->
<!-- ============================================================== -->

<div 
  id="appSplashScreenOverlay" 
  class="fixed inset-0 z-[999999] bg-[#dcf4fd] overflow-hidden flex flex-col justify-between items-center transition-all duration-700 ease-out select-none"
  style="display: none;"
>
  
  <!-- 1. BACKDROP: SUNLIT SKY, MOUNTAINS, AND HOSPITAL SILHOUETTE -->
  <div class="absolute inset-0 z-0 pointer-events-none">
    
    @if(file_exists(public_path('assets/splash_bg_clean.jpg')))
      <div 
        class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-[1.01] transform transition-transform duration-1000 filter brightness-105"
        style="background-image: url('{{ asset('assets/splash_bg_clean.jpg') }}');"
      ></div>
      <div class="absolute inset-0 bg-gradient-to-b from-white/10 via-transparent to-[#0077b6]/25"></div>
    @elseif(file_exists(public_path('assets/splash_reference.png')))
      <div 
        class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-[1.01] transform transition-transform duration-1000 filter brightness-105"
        style="background-image: url('{{ asset('assets/splash_reference.png') }}');"
      ></div>
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-[#0096c7]/25"></div>
    @else
      <div class="absolute inset-0 bg-gradient-to-b from-[#bfe7f9] via-[#e2f5fc] to-[#7ed0ea]"></div>
    @endif

    <!-- Sunrays Halo from Top Right (Diperkecil & Efek Buram Dikurangi) -->
    <div class="absolute -top-10 -right-10 w-72 h-72 bg-gradient-to-br from-yellow-100/30 via-white/15 to-transparent rounded-full blur-xl opacity-45 pointer-events-none sunray-halo"></div>
    <div class="absolute top-0 right-1/4 w-48 h-[45vh] bg-gradient-to-b from-white/20 via-transparent to-transparent rotate-[-25deg] blur-md opacity-35 pointer-events-none"></div>

    <!-- Distant Hospital Building Silhouette -->
    <div class="absolute bottom-36 sm:bottom-48 right-6 sm:right-20 w-28 sm:w-44 h-56 sm:h-72 opacity-25 pointer-events-none">
      <svg viewBox="0 0 160 280" fill="none" class="w-full h-full text-teal-800">
        <rect x="30" y="20" width="100" height="260" rx="8" fill="currentColor" fill-opacity="0.5"/>
        <rect x="50" y="5" width="60" height="25" rx="4" fill="currentColor" fill-opacity="0.6"/>
        <g fill="#ffffff" fill-opacity="0.8">
          <rect x="42" y="35" width="16" height="12" rx="2"/>
          <rect x="68" y="35" width="16" height="12" rx="2"/>
          <rect x="94" y="35" width="16" height="12" rx="2"/>
          <rect x="42" y="55" width="16" height="12" rx="2"/>
          <rect x="68" y="55" width="16" height="12" rx="2"/>
          <rect x="94" y="55" width="16" height="12" rx="2"/>
          <rect x="42" y="75" width="16" height="12" rx="2"/>
          <rect x="68" y="75" width="16" height="12" rx="2"/>
          <rect x="94" y="75" width="16" height="12" rx="2"/>
          <rect x="42" y="95" width="16" height="12" rx="2"/>
          <rect x="68" y="95" width="16" height="12" rx="2"/>
          <rect x="94" y="95" width="16" height="12" rx="2"/>
        </g>
        <circle cx="80" cy="18" r="7" fill="#ffffff" fill-opacity="0.9"/>
        <path d="M77 18h6M80 15v6" stroke="#00a896" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </div>

    <!-- Glowing Particles -->
    <div class="particle w-3 h-3 bg-white/70 shadow-sm shadow-white left-[15%] bottom-[20%]" style="animation-delay: 0s;"></div>
    <div class="particle w-2 h-2 bg-teal-200/80 shadow-sm shadow-teal-300 left-[35%] bottom-[25%]" style="animation-delay: 1.5s;"></div>
    <div class="particle w-4 h-4 bg-white/60 shadow-md shadow-white left-[55%] bottom-[18%]" style="animation-delay: 3s;"></div>
    <div class="particle w-2.5 h-2.5 bg-emerald-200/80 shadow-sm shadow-emerald-200 left-[75%] bottom-[22%]" style="animation-delay: 2.2s;"></div>
  </div>

  <!-- 1.5. LAYER ANIMASI BURUNG TERBANG DI LANGIT (POINTER-EVENTS-NONE) -->
  <div class="pointer-events-none absolute inset-0 overflow-hidden z-10 select-none opacity-75" aria-hidden="true">
    <!-- Burung 1: Terbang dari Kiri ke Kanan di Langit Atas -->
    <div class="splash-bird-1 absolute top-[7%] sm:top-[9%] left-0 w-7 h-4 sm:w-8 sm:h-5 text-[#0c3859]/60 filter drop-shadow-xs">
      <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
        <path class="bird-wing-l" style="animation-duration: 0.72s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
        <path class="bird-wing-r" style="animation-duration: 0.72s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
        <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
      </svg>
    </div>

    <!-- Burung 2: Terbang dari Kanan ke Kiri (Berlawanan Arah) -->
    <div class="splash-bird-2 absolute top-[13%] sm:top-[15%] left-0 w-8 h-4.5 sm:w-9 sm:h-5 text-[#0c3859]/65 filter drop-shadow-xs">
      <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
        <path class="bird-wing-l" style="animation-duration: 0.65s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
        <path class="bird-wing-r" style="animation-duration: 0.65s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
        <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
      </svg>
    </div>

    <!-- Burung 3: Burung Kecil di Langit Tinggi -->
    <div class="splash-bird-3 absolute top-[4%] sm:top-[5%] left-0 w-5 h-3 sm:w-5.5 sm:h-3 text-[#0c3859]/50 filter drop-shadow-xs">
      <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
        <path class="bird-wing-l" style="animation-duration: 0.85s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
        <path class="bird-wing-r" style="animation-duration: 0.85s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
        <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
      </svg>
    </div>

    <!-- Burung 4: Burung Pengikut Menengah (Kiri ke Kanan) -->
    <div class="splash-bird-4 absolute top-[11%] sm:top-[12%] left-0 w-6.5 h-3.5 sm:w-7.5 sm:h-4 text-[#0c3859]/55 filter drop-shadow-xs">
      <svg viewBox="0 0 32 18" class="w-full h-full fill-current">
        <path class="bird-wing-l" style="animation-duration: 0.76s;" d="M16 9 C11 3, 4 2, 1 6 C6 7, 12 10, 16 10 Z" />
        <path class="bird-wing-r" style="animation-duration: 0.76s;" d="M16 9 C21 3, 28 2, 31 6 C26 7, 20 10, 16 10 Z" />
        <ellipse cx="16" cy="10" rx="2.5" ry="1.2" />
      </svg>
    </div>
  </div>

  <!-- TOP BAR: BRAND PILL & SKIP BUTTON -->
  <header class="relative z-30 w-full max-w-7xl mx-auto px-4 sm:px-8 pt-5 sm:pt-6 flex items-center justify-between">
    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 bg-white/75 backdrop-blur-md rounded-full border border-white/80 shadow-sm">
      <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-[#0085d0] to-[#00c9a7] flex items-center justify-center text-white text-xs font-black shadow-xs">
        +
      </div>
      <span class="font-extrabold text-xs sm:text-sm text-[#0c3859] tracking-tight">
        Diabetes <span class="text-[#00b4d8]">Uji Coba</span>
      </span>
    </div>

    <button 
      type="button" 
      onclick="dismissSplashScreen()"
      class="inline-flex items-center space-x-1.5 px-4 py-2 bg-white/75 hover:bg-white text-[#0c3859] font-bold text-xs rounded-full border border-white/90 shadow-sm hover:shadow-md backdrop-blur-md transition-all active:scale-95 cursor-pointer group"
    >
      <span>Lewati</span>
      <span class="group-hover:translate-x-0.5 transition-transform text-[#00a896]">&rarr;</span>
    </button>
  </header>

  <!-- MAIN CENTER HERO CONTENT & FLOATING GLASS PILLS -->
  <main class="relative z-20 w-full max-w-6xl mx-auto px-4 sm:px-6 flex flex-col items-center justify-center text-center my-auto py-2">
    <div class="w-full relative flex flex-col items-center justify-center">

      <!-- LEFT FLOATING GLASS PILL CARDS (Desktop & Tablet) -->
      <div class="hidden md:flex flex-col space-y-3.5 absolute left-0 lg:left-4 top-1/2 -translate-y-1/2 text-left z-20 pointer-events-none">
        
        <div class="animate-badge-1 flex items-center space-x-3 px-4 py-2.5 bg-white/80 backdrop-blur-xl rounded-2xl border border-white/90 shadow-lg shadow-teal-900/5 min-w-[170px]">
          <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#00b4d8] to-[#00c9a7] flex items-center justify-center text-white text-base shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <div>
            <h4 class="font-black text-xs text-[#0c3859] leading-tight">Sehat</h4>
            <p class="text-[10px] text-gray-500 font-medium">Untuk Hari Ini</p>
          </div>
        </div>

        <div class="animate-badge-2 flex items-center space-x-3 px-4 py-2.5 bg-white/80 backdrop-blur-xl rounded-2xl border border-white/90 shadow-lg shadow-teal-900/5 min-w-[170px]">
          <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#00a896] to-[#02c39a] flex items-center justify-center text-white text-base shadow-sm">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
          </div>
          <div>
            <h4 class="font-black text-xs text-[#0c3859] leading-tight">Bahagia</h4>
            <p class="text-[10px] text-gray-500 font-medium">Untuk Nanti</p>
          </div>
        </div>

        <div class="animate-badge-3 flex items-center space-x-3 px-4 py-2.5 bg-white/80 backdrop-blur-xl rounded-2xl border border-white/90 shadow-lg shadow-teal-900/5 min-w-[170px]">
          <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0085d0] to-[#00b4d8] flex items-center justify-center text-white text-base shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div>
            <h4 class="font-black text-xs text-[#0c3859] leading-tight">Bersama</h4>
            <p class="text-[10px] text-gray-500 font-medium">Lebih Baik</p>
          </div>
        </div>

      </div>

      <!-- CENTER BRANDING & TYPOGRAPHY -->
      <div class="max-w-2xl mx-auto flex flex-col items-center">
        
        <div class="relative mb-3 flex items-center justify-center">
          <div class="w-12 sm:w-20 h-0.5 bg-gradient-to-r from-transparent via-[#00a896]/60 to-[#00c9a7] rounded-full mr-3 sm:mr-4"></div>

          <div class="relative w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center heart-pulse">
            <svg viewBox="0 0 100 90" fill="none" class="w-full h-full drop-shadow-md">
              <path 
                d="M50 82 L18 50 C8 40 8 24 20 12 C32 0 46 6 50 16 C54 6 68 0 80 12 C92 24 92 40 82 50 Z" 
                stroke="url(#heartGradComp)" 
                stroke-width="5" 
                stroke-linejoin="round"
                fill="#ffffff"
                fill-opacity="0.85"
              />
              <path 
                d="M26 46 L38 46 L43 32 L49 58 L54 38 L58 48 L64 46 L74 46" 
                stroke="#00a896" 
                stroke-width="4.5" 
                stroke-linecap="round" 
                stroke-linejoin="round"
                class="ecg-anim-line"
              />
              <defs>
                <linearGradient id="heartGradComp" x1="0%" y1="0%" x2="100%" y2="100%">
                  <stop offset="0%" stop-color="#0085d0"/>
                  <stop offset="50%" stop-color="#00a896"/>
                  <stop offset="100%" stop-color="#00c9a7"/>
                </linearGradient>
              </defs>
            </svg>
          </div>

          <div class="w-12 sm:w-20 h-0.5 bg-gradient-to-l from-transparent via-[#00a896]/60 to-[#00c9a7] rounded-full ml-3 sm:mr-4"></div>
        </div>

        <!-- Main Title: "Selamat Datang" -->
        <h1 class="font-extrabold text-4xl sm:text-6xl lg:text-7xl tracking-tight leading-none text-[#0c3859] drop-shadow-sm mb-2.5">
          Selamat <span class="bg-gradient-to-r from-[#0085d0] via-[#00a896] to-[#00c9a7] bg-clip-text text-transparent">Datang</span>
        </h1>

        <!-- Slogan: "Anda Sehat, Kami Senang" -->
        <div class="relative inline-block mb-3">
          <h2 class="text-xl sm:text-3xl font-extrabold text-[#0c3859] tracking-normal">
            Anda Sehat <span class="text-[#00a896]">Kami Senang</span>
          </h2>
          
          <svg class="w-48 sm:w-64 h-3.5 mx-auto mt-1" viewBox="0 0 240 14" fill="none">
            <path d="M4 10 C60 1 180 1 236 10" stroke="url(#swoopGradComp)" stroke-width="3" stroke-linecap="round"/>
            <defs>
              <linearGradient id="swoopGradComp" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#00b4d8" stop-opacity="0.3"/>
                <stop offset="50%" stop-color="#00c9a7"/>
                <stop offset="100%" stop-color="#00b4d8" stop-opacity="0.3"/>
              </linearGradient>
            </defs>
          </svg>
        </div>

        <p class="text-xs sm:text-sm md:text-base font-semibold text-slate-500 max-w-md mx-auto mb-6 sm:mb-8 leading-relaxed">
          Menuju Hidup yang Lebih Sehat dan Bahagia
        </p>

        <!-- PROGRESS BAR & PERCENTAGE PILL -->
        <div class="w-full max-w-xs sm:max-w-md mx-auto px-2 space-y-3.5">
          
          <div class="flex items-center space-x-3">
            <div class="flex-1 h-5 sm:h-6 bg-white/70 backdrop-blur-xl rounded-full p-1 border border-white shadow-inner shadow-slate-300/40 relative overflow-hidden">
              <div 
                id="splashOverlayProgressBar" 
                class="h-full rounded-full bg-gradient-to-r from-[#0085d0] via-[#00b4d8] to-[#00c9a7] transition-all duration-150 ease-out relative overflow-hidden shadow-sm"
                style="width: 8%;"
              >
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent bar-shine w-full"></div>
              </div>
            </div>

            <div class="flex-shrink-0">
              <span 
                id="splashOverlayPercentText" 
                class="inline-flex items-center justify-center min-w-[52px] px-2.5 py-1 rounded-full bg-[#00a896] text-white font-extrabold text-xs sm:text-sm shadow-md shadow-teal-500/30"
              >
                8%
              </span>
            </div>
          </div>

          <!-- Status Indicator -->
          <div class="flex items-center justify-center space-x-2 text-xs text-slate-600 font-semibold tracking-wide">
            <svg class="w-5 h-5 text-[#00a896] animate-pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h4l3-8 4 16 3-8h4"/>
            </svg>
            <span id="splashOverlayStatusText" class="tracking-wider uppercase text-[11px] sm:text-xs font-bold text-[#0c3859] transition-opacity duration-300">
              M E M U A T . . .
            </span>
          </div>

        </div>

      </div>

    </div>
  </main>

  <!-- NATURAL BLUE WATER OCEAN & WAVES AT BOTTOM DENGAN ANIMASI IKAN MELOMPAT -->
  <div class="relative w-full z-20 pointer-events-none mt-auto overflow-visible">
    
    <!-- SVG Defs untuk Gradien Sisik Ikan -->
    <svg width="0" height="0" class="absolute">
      <defs>
        <linearGradient id="fishGradComp" x1="0%" y1="50%" x2="100%" y2="50%">
          <stop offset="0%" stop-color="#94a3b8"/>
          <stop offset="30%" stop-color="#cbd5e1"/>
          <stop offset="65%" stop-color="#7dd3fc"/>
          <stop offset="100%" stop-color="#38bdf8"/>
        </linearGradient>
      </defs>
    </svg>

    <!-- Ikan 1: Danau Kiri Bawah (Melompat Melengkung Halus ke Kanan) -->
    <div class="absolute bottom-12 sm:bottom-20 left-[6%] sm:left-[12%] w-28 h-20 pointer-events-none z-25">
      <!-- Percikan Air saat Masuk Kembali (Splashdown) -->
      <div class="absolute left-[78px] bottom-1 w-6 h-6 flex items-center justify-center fish-splash-1">
        <span class="w-1.5 h-1.5 bg-white/90 rounded-full -translate-x-1 -translate-y-2"></span>
        <span class="w-2 h-2 bg-sky-200/90 rounded-full -translate-y-3"></span>
        <span class="w-1.5 h-1.5 bg-white/90 rounded-full translate-x-1.5 -translate-y-2"></span>
      </div>

      <!-- Riak Air Melingkar saat Ikan Masuk (Ripples) -->
      <div class="absolute left-[62px] bottom-0 w-12 h-3.5 border border-white/70 rounded-full fish-ripple-1"></div>
      <div class="absolute left-[58px] bottom-[-2px] w-14 h-4 border border-cyan-200/50 rounded-full fish-ripple-1" style="animation-delay: 1.35s;"></div>

      <!-- Karakter Ikan Melompat -->
      <div class="w-10 sm:w-11 h-4 sm:h-4.5 fish-anim-1">
        <svg viewBox="0 0 50 20" class="w-full h-full drop-shadow-sm overflow-visible">
          <path d="M44 10 C38 4, 24 3, 14 7 C10 8, 4 4, 1 2 C3 7, 3 13, 1 18 C4 16, 10 12, 14 13 C24 17, 38 16, 44 10 Z" fill="url(#fishGradComp)" />
          <path d="M22 5 C26 2, 32 3, 34 6 C28 5, 24 5, 22 5 Z" fill="#93c5fd" opacity="0.85" />
          <path d="M30 11 C26 13, 23 15, 25 16 C28 15, 30 13, 30 11 Z" fill="#bae6fd" opacity="0.9" />
          <circle cx="41" cy="9" r="1.1" fill="#0f172a" />
          <circle cx="41.3" cy="8.7" r="0.4" fill="#ffffff" />
          <path d="M16 10 C24 10, 32 9.5, 38 10" stroke="#ffffff" stroke-width="0.8" stroke-linecap="round" opacity="0.6" />
        </svg>
      </div>
    </div>

    <!-- Ikan 2: Danau Kanan Bawah (Melompat Melengkung Halus ke Kiri) -->
    <div class="absolute bottom-10 sm:bottom-18 right-[8%] sm:right-[15%] w-28 h-20 pointer-events-none z-25">
      <!-- Percikan Air saat Masuk Kembali (Splashdown) -->
      <div class="absolute right-[78px] bottom-1 w-6 h-6 flex items-center justify-center fish-splash-2">
        <span class="w-1.5 h-1.5 bg-white/90 rounded-full -translate-x-1.5 -translate-y-2"></span>
        <span class="w-2 h-2 bg-sky-200/90 rounded-full -translate-y-3"></span>
        <span class="w-1.5 h-1.5 bg-white/90 rounded-full translate-x-1 -translate-y-2"></span>
      </div>

      <!-- Riak Air Melingkar saat Ikan Masuk (Ripples) -->
      <div class="absolute right-[62px] bottom-0 w-12 h-3.5 border border-white/70 rounded-full fish-ripple-2"></div>
      <div class="absolute right-[58px] bottom-[-2px] w-14 h-4 border border-cyan-200/50 rounded-full fish-ripple-2" style="animation-delay: 5.45s;"></div>

      <!-- Karakter Ikan Melompat -->
      <div class="w-10 sm:w-11 h-4 sm:h-4.5 ml-auto fish-anim-2">
        <svg viewBox="0 0 50 20" class="w-full h-full drop-shadow-sm overflow-visible">
          <path d="M44 10 C38 4, 24 3, 14 7 C10 8, 4 4, 1 2 C3 7, 3 13, 1 18 C4 16, 10 12, 14 13 C24 17, 38 16, 44 10 Z" fill="url(#fishGradComp)" />
          <path d="M22 5 C26 2, 32 3, 34 6 C28 5, 24 5, 22 5 Z" fill="#93c5fd" opacity="0.85" />
          <path d="M30 11 C26 13, 23 15, 25 16 C28 15, 30 13, 30 11 Z" fill="#bae6fd" opacity="0.9" />
          <circle cx="41" cy="9" r="1.1" fill="#0f172a" />
          <circle cx="41.3" cy="8.7" r="0.4" fill="#ffffff" />
          <path d="M16 10 C24 10, 32 9.5, 38 10" stroke="#ffffff" stroke-width="0.8" stroke-linecap="round" opacity="0.6" />
        </svg>
      </div>
    </div>

    <!-- Ikan 3: Danau Kiri-Tengah Kejauhan (Lebih Kecil & Sesekali Melompat) -->
    <div class="hidden md:block absolute bottom-18 left-[24%] w-24 h-16 pointer-events-none z-20 opacity-85 scale-75">
      <div class="absolute left-[70px] bottom-0 w-10 h-3 border border-white/60 rounded-full fish-ripple-3"></div>
      <div class="w-9 h-3.5 fish-anim-3">
        <svg viewBox="0 0 50 20" class="w-full h-full drop-shadow-xs overflow-visible">
          <path d="M44 10 C38 4, 24 3, 14 7 C10 8, 4 4, 1 2 C3 7, 3 13, 1 18 C4 16, 10 12, 14 13 C24 17, 38 16, 44 10 Z" fill="url(#fishGradComp)" />
          <circle cx="41" cy="9" r="1.1" fill="#0f172a" />
        </svg>
      </div>
    </div>

    <div class="absolute bottom-12 left-[20%] w-3 h-3 bg-white/70 rounded-full droplet" style="animation-delay: 0.2s;"></div>
    <div class="absolute bottom-8 left-[45%] w-4 h-4 bg-white/60 rounded-full droplet" style="animation-delay: 1.1s;"></div>
    <div class="absolute bottom-14 left-[65%] w-2.5 h-2.5 bg-white/70 rounded-full droplet" style="animation-delay: 0.7s;"></div>
    <div class="absolute bottom-10 left-[82%] w-3.5 h-3.5 bg-white/60 rounded-full droplet" style="animation-delay: 1.8s;"></div>

    <div class="relative w-full h-24 sm:h-36 md:h-44 overflow-hidden">
      <svg class="wave-layer-3 absolute bottom-0 left-0 w-[200%] h-full opacity-40" viewBox="0 0 1440 180" preserveAspectRatio="none">
        <path fill="#0077b6" d="M0,64L60,80C120,96,240,128,360,133.3C480,139,600,117,720,101.3C840,85,960,75,1080,85.3C1200,96,1320,128,1380,144L1440,160L1440,180L1380,180C1320,180,1200,180,1080,180C960,180,840,180,720,180C600,180,480,180,360,180C240,180,120,180,60,180L0,180Z"></path>
      </svg>
      <svg class="wave-layer-2 absolute bottom-0 left-0 w-[200%] h-full opacity-65" viewBox="0 0 1440 180" preserveAspectRatio="none">
        <path fill="#0096c7" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,117.3C672,107,768,117,864,128C960,139,1056,149,1152,144C1248,139,1344,117,1392,106.7L1440,96L1440,180L1392,180C1344,180,1248,180,1152,180C1056,180,960,180,864,180C768,180,672,180,576,180C480,180,384,180,288,180C192,180,96,180,48,180L0,180Z"></path>
      </svg>
      <svg class="wave-layer-1 absolute bottom-0 left-0 w-[200%] h-full opacity-90" viewBox="0 0 1440 180" preserveAspectRatio="none">
        <path fill="url(#frontWaterGradComp)" d="M0,128L60,122.7C120,117,240,107,360,112C480,117,600,139,720,144C840,149,960,139,1080,122.7C1200,107,1320,85,1380,74.7L1440,64L1440,180L1380,180C1320,180,1200,180,1080,180C960,180,840,180,720,180C600,180,480,180,360,180C240,180,120,180,60,180L0,180Z"></path>
        <defs>
          <linearGradient id="frontWaterGradComp" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" stop-color="#48cae4"/>
            <stop offset="40%" stop-color="#0096c7"/>
            <stop offset="100%" stop-color="#0077b6"/>
          </linearGradient>
        </defs>
      </svg>
      <div class="absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-t from-[#005f73]/50 to-transparent"></div>
    </div>
  </div>

  <!-- FLOATING GREEN LEAVES ACCENTS -->
  <div class="leaf-1 absolute top-4 sm:top-8 right-4 sm:right-12 w-14 sm:w-20 pointer-events-none z-20 opacity-90">
    <svg viewBox="0 0 80 80" fill="none" class="w-full h-full drop-shadow-md">
      <path d="M10 70 C10 70, 20 20, 70 10 C70 10, 60 60, 10 70 Z" fill="url(#leafGradComp1)"/>
      <path d="M10 70 Q40 40 70 10" stroke="#a7f3d0" stroke-width="2"/>
      <defs>
        <linearGradient id="leafGradComp1" x1="0%" y1="100%" x2="100%" y2="0%">
          <stop offset="0%" stop-color="#059669"/>
          <stop offset="100%" stop-color="#34d399"/>
        </linearGradient>
      </defs>
    </svg>
  </div>

  <div class="leaf-2 absolute top-1/3 left-3 sm:left-10 w-10 sm:w-14 pointer-events-none z-20 opacity-85">
    <svg viewBox="0 0 80 80" fill="none" class="w-full h-full drop-shadow-sm">
      <path d="M70 70 C70 70, 60 20, 10 10 C10 10, 20 60, 70 70 Z" fill="url(#leafGradComp2)"/>
      <path d="M70 70 Q40 40 10 10" stroke="#a7f3d0" stroke-width="1.8"/>
      <defs>
        <linearGradient id="leafGradComp2" x1="100%" y1="100%" x2="0%" y2="0%">
          <stop offset="0%" stop-color="#10b981"/>
          <stop offset="100%" stop-color="#6ee7b7"/>
        </linearGradient>
      </defs>
    </svg>
  </div>

  <div class="leaf-3 absolute bottom-28 sm:bottom-40 left-4 sm:left-16 w-12 sm:w-16 pointer-events-none z-20 opacity-90">
    <svg viewBox="0 0 80 80" fill="none" class="w-full h-full drop-shadow-md">
      <path d="M15 65 C15 65, 30 15, 65 15 C65 15, 50 65, 15 65 Z" fill="url(#leafGradComp3)"/>
      <path d="M15 65 Q40 40 65 15" stroke="#a7f3d0" stroke-width="1.8"/>
      <defs>
        <linearGradient id="leafGradComp3" x1="0%" y1="100%" x2="100%" y2="0%">
          <stop offset="0%" stop-color="#047857"/>
          <stop offset="100%" stop-color="#10b981"/>
        </linearGradient>
      </defs>
    </svg>
  </div>

</div>

<!-- CSS Styles for Overlay Component -->
<style>
  @keyframes waveSlowComp {
    0% { transform: translateX(0) scaleY(1); }
    50% { transform: translateX(-25%) scaleY(1.08); }
    100% { transform: translateX(-50%) scaleY(1); }
  }
  @keyframes waveFastComp {
    0% { transform: translateX(0) scaleY(1); }
    50% { transform: translateX(-30%) scaleY(0.92); }
    100% { transform: translateX(-60%) scaleY(1); }
  }
  @keyframes waveDeepComp {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }
  .wave-layer-1 { animation: waveSlowComp 14s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite; }
  .wave-layer-2 { animation: waveFastComp 9s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite; }
  .wave-layer-3 { animation: waveDeepComp 18s linear infinite; }

  @keyframes floatBadge1Comp { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
  @keyframes floatBadge2Comp { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }
  @keyframes floatBadge3Comp { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
  .animate-badge-1 { animation: floatBadge1Comp 4.5s ease-in-out infinite; }
  .animate-badge-2 { animation: floatBadge2Comp 5.2s ease-in-out infinite 0.6s; }
  .animate-badge-3 { animation: floatBadge3Comp 4.8s ease-in-out infinite 1.2s; }

  @keyframes heartBeatingComp {
    0%, 100% { transform: scale(1); filter: drop-shadow(0 0 8px rgba(0,201,167,0.3)); }
    14% { transform: scale(1.15); filter: drop-shadow(0 0 16px rgba(0,201,167,0.6)); }
    28% { transform: scale(1); filter: drop-shadow(0 0 8px rgba(0,201,167,0.3)); }
    42% { transform: scale(1.1); filter: drop-shadow(0 0 12px rgba(0,201,167,0.5)); }
    70% { transform: scale(1); filter: drop-shadow(0 0 8px rgba(0,201,167,0.3)); }
  }
  .heart-pulse { animation: heartBeatingComp 1.8s ease-in-out infinite; }

  @keyframes ecgTravelComp {
    0% { stroke-dashoffset: 200; opacity: 0.3; }
    50% { opacity: 1; }
    100% { stroke-dashoffset: 0; opacity: 0.3; }
  }
  .ecg-anim-line { stroke-dasharray: 200; stroke-dashoffset: 200; animation: ecgTravelComp 2.4s cubic-bezier(0.4, 0, 0.2, 1) infinite; }

  @keyframes leafSway1Comp { 0%, 100% { transform: translate(0, 0) rotate(0deg); } 50% { transform: translate(-8px, -12px) rotate(-6deg); } }
  @keyframes leafSway2Comp { 0%, 100% { transform: translate(0, 0) rotate(0deg); } 50% { transform: translate(10px, -14px) rotate(8deg); } }
  @keyframes leafSway3Comp { 0%, 100% { transform: translate(0, 0) rotate(0deg); } 50% { transform: translate(-12px, 8px) rotate(-10deg); } }
  .leaf-1 { animation: leafSway1Comp 6s ease-in-out infinite; }
  .leaf-2 { animation: leafSway2Comp 7s ease-in-out infinite 0.8s; }
  .leaf-3 { animation: leafSway3Comp 5.5s ease-in-out infinite 1.5s; }

  @keyframes particleRiseComp {
    0% { transform: translateY(0) scale(0.8); opacity: 0; }
    20% { opacity: 0.8; }
    80% { opacity: 0.8; }
    100% { transform: translateY(-160px) scale(1.2); opacity: 0; }
  }
  .particle { position: absolute; border-radius: 9999px; pointer-events: none; animation: particleRiseComp 6s linear infinite; }

  @keyframes barShimmerComp { 0% { transform: translateX(-100%); } 100% { transform: translateX(200%); } }
  .bar-shine { animation: barShimmerComp 2.2s ease-in-out infinite; }

  @keyframes sunrayRotateComp { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
  .sunray-halo { animation: sunrayRotateComp 90s linear infinite; }

  @keyframes dropletFloatComp { 0%, 100% { transform: translateY(0) scale(1); opacity: 0.7; } 50% { transform: translateY(-8px) scale(1.1); opacity: 1; } }
  .droplet { animation: dropletFloatComp 3.5s ease-in-out infinite; }

  /* ============================================================== */
  /* ANIMASI BURUNG TERBANG DI LANGIT (SPLASH SCREEN)               */
  /* ============================================================== */
  @keyframes splashBirdFlyLTR {
    0% { transform: translate3d(-15vw, 2vh, 0) scale(0.65) rotate(4deg); opacity: 0; }
    8% { opacity: 0.75; }
    85% { opacity: 0.75; }
    100% { transform: translate3d(115vw, -4vh, 0) scale(0.65) rotate(4deg); opacity: 0; }
  }
  @keyframes splashBirdFlyRTL {
    0% { transform: translate3d(115vw, 4vh, 0) scale(-0.72, 0.72) rotate(-4deg); opacity: 0; }
    8% { opacity: 0.75; }
    85% { opacity: 0.75; }
    100% { transform: translate3d(-15vw, -3vh, 0) scale(-0.72, 0.72) rotate(-4deg); opacity: 0; }
  }
  @keyframes splashBirdFlyHigh {
    0% { transform: translate3d(-10vw, -1vh, 0) scale(0.45) rotate(2deg); opacity: 0; }
    6% { opacity: 0.6; }
    88% { opacity: 0.6; }
    100% { transform: translate3d(112vw, -6vh, 0) scale(0.45) rotate(2deg); opacity: 0; }
  }

  .splash-bird-1 { animation: splashBirdFlyLTR 26s linear infinite; animation-delay: -3s; }
  .splash-bird-2 { animation: splashBirdFlyRTL 23s linear infinite; animation-delay: -11s; }
  .splash-bird-3 { animation: splashBirdFlyHigh 34s linear infinite; animation-delay: -17s; }
  .splash-bird-4 { animation: splashBirdFlyLTR 30s linear infinite; animation-delay: -14s; }

  /* Sayap Burung Mengepak Halus */
  @keyframes wingLeftSplash {
    0%, 100% { transform: rotate(0deg); }
    50% { transform: rotate(-32deg) translateY(-2px); }
  }
  @keyframes wingRightSplash {
    0%, 100% { transform: rotate(0deg); }
    50% { transform: rotate(32deg) translateY(-2px); }
  }
  .bird-wing-l { transform-origin: 16px 9px; animation: wingLeftSplash 0.75s ease-in-out infinite; }
  .bird-wing-r { transform-origin: 16px 9px; animation: wingRightSplash 0.75s ease-in-out infinite; }

  /* ============================================================== */
  /* ANIMASI IKAN MELOMPAT, RIAK & PERCIKAN AIR DI DANAU            */
  /* ============================================================== */
  @keyframes fishLeapLeft {
    0%, 100% {
      transform: translate3d(0, 20px, 0) rotate(-60deg) scale(0.8);
      opacity: 0;
    }
    4% {
      opacity: 1;
      transform: translate3d(8px, -8px, 0) rotate(-40deg) scale(0.95);
    }
    15% {
      opacity: 1;
      transform: translate3d(42px, -48px, 0) rotate(5deg) scale(1);
    }
    26% {
      opacity: 1;
      transform: translate3d(76px, -12px, 0) rotate(50deg) scale(0.95);
    }
    30% {
      opacity: 0;
      transform: translate3d(88px, 16px, 0) rotate(68deg) scale(0.8);
    }
    31%, 99% {
      opacity: 0;
      transform: translate3d(0, 20px, 0) rotate(-60deg) scale(0.8);
    }
  }

  @keyframes fishLeapRight {
    0%, 100% {
      transform: translate3d(0, 20px, 0) rotate(60deg) scale(-0.8, 0.8);
      opacity: 0;
    }
    4% {
      opacity: 1;
      transform: translate3d(-8px, -8px, 0) rotate(40deg) scale(-0.95, 0.95);
    }
    15% {
      opacity: 1;
      transform: translate3d(-42px, -48px, 0) rotate(-5deg) scale(-1, 1);
    }
    26% {
      opacity: 1;
      transform: translate3d(-76px, -12px, 0) rotate(-50deg) scale(-0.95, 0.95);
    }
    30% {
      opacity: 0;
      transform: translate3d(-88px, 16px, 0) rotate(-68deg) scale(-0.8, 0.8);
    }
    31%, 99% {
      opacity: 0;
      transform: translate3d(0, 20px, 0) rotate(60deg) scale(-0.8, 0.8);
    }
  }

  /* Riak Air Melingkar Halus (Expanding Ripples) */
  @keyframes fishRippleExpand {
    0%, 25% {
      transform: scale(0.1);
      opacity: 0;
    }
    27% {
      opacity: 0.85;
      transform: scale(0.4);
    }
    36% {
      opacity: 0.5;
      transform: scale(1.6);
    }
    46% {
      opacity: 0;
      transform: scale(2.6);
    }
    47%, 100% {
      opacity: 0;
      transform: scale(0.1);
    }
  }

  /* Percikan Air Halus saat Re-entry */
  @keyframes fishSplashDrop {
    0%, 26% {
      transform: translateY(0) scale(0.3);
      opacity: 0;
    }
    28% {
      opacity: 0.95;
      transform: translateY(-16px) scale(1);
    }
    33% {
      opacity: 0;
      transform: translateY(4px) scale(0.5);
    }
    34%, 100% {
      opacity: 0;
      transform: translateY(0);
    }
  }

  .fish-anim-1 {
    animation: fishLeapLeft 8.5s cubic-bezier(0.25, 0.1, 0.25, 1) infinite;
    animation-delay: 1.2s;
    will-change: transform;
  }
  .fish-ripple-1 {
    animation: fishRippleExpand 8.5s ease-out infinite;
    animation-delay: 1.2s;
  }
  .fish-splash-1 {
    animation: fishSplashDrop 8.5s ease-out infinite;
    animation-delay: 1.2s;
  }

  .fish-anim-2 {
    animation: fishLeapRight 10.5s cubic-bezier(0.25, 0.1, 0.25, 1) infinite;
    animation-delay: 5.2s;
    will-change: transform;
  }
  .fish-ripple-2 {
    animation: fishRippleExpand 10.5s ease-out infinite;
    animation-delay: 5.2s;
  }
  .fish-splash-2 {
    animation: fishSplashDrop 10.5s ease-out infinite;
    animation-delay: 5.2s;
  }

  .fish-anim-3 {
    animation: fishLeapLeft 12s cubic-bezier(0.25, 0.1, 0.25, 1) infinite;
    animation-delay: 8.5s;
    will-change: transform;
  }
  .fish-ripple-3 {
    animation: fishRippleExpand 12s ease-out infinite;
    animation-delay: 8.5s;
  }

  /* Dukungan prefers-reduced-motion */
  @media (prefers-reduced-motion: reduce) {
    .splash-bird-1, .splash-bird-2, .splash-bird-3, .splash-bird-4,
    .fish-anim-1, .fish-anim-2, .fish-anim-3,
    .fish-ripple-1, .fish-ripple-2, .fish-ripple-3,
    .fish-splash-1, .fish-splash-2 {
      animation: none !important;
      display: none !important;
    }
  }
</style>

<!-- Script to Control Overlay on Homepage -->
<script>
  (function () {
    const overlay = document.getElementById('appSplashScreenOverlay');
    if (!overlay) return;

    // Check if user already saw the splash screen in this session, unless URL forces it with ?welcome=1
    const urlParams = new URLSearchParams(window.location.search);
    const forceShow = urlParams.has('welcome') || urlParams.has('splash');
    const alreadySeen = sessionStorage.getItem('diabetes_splash_seen');

    if (alreadySeen && !forceShow) {
      overlay.style.display = 'none';
      return;
    }

    // Display overlay
    overlay.style.display = 'flex';
    document.body.classList.add('overflow-hidden');

    let progress = 6;
    const bar = document.getElementById('splashOverlayProgressBar');
    const pct = document.getElementById('splashOverlayPercentText');
    const statusTxt = document.getElementById('splashOverlayStatusText');
    let completed = false;

    const stages = [
      { threshold: 25, text: "Memuat Indikator Kesehatan..." },
      { threshold: 55, text: "Menyiapkan Pengalaman Kesehatan Terbaik..." },
      { threshold: 85, text: "Menyelaraskan Parameter Klinis..." },
      { threshold: 99, text: "Memulai Layanan..." }
    ];

    function step() {
      if (completed) return;

      if (progress < 30) progress += Math.floor(Math.random() * 5) + 3;
      else if (progress < 70) progress += Math.floor(Math.random() * 6) + 4;
      else if (progress < 95) progress += Math.floor(Math.random() * 4) + 2;
      else progress += 2;

      if (progress >= 100) {
        progress = 100;
        updateUI(100);
        finish();
        return;
      }

      updateUI(progress);

      for (let i = 0; i < stages.length; i++) {
        if (progress <= stages[i].threshold) {
          if (statusTxt && statusTxt.textContent !== stages[i].text) {
            statusTxt.textContent = stages[i].text;
          }
          break;
        }
      }

      const delay = Math.floor(Math.random() * 45) + 40;
      setTimeout(step, delay);
    }

    function updateUI(val) {
      if (bar) bar.style.width = val + '%';
      if (pct) pct.textContent = val + '%';
    }

    function finish() {
      completed = true;
      updateUI(100);

      if (statusTxt) {
        statusTxt.textContent = "Selamat Datang di Layanan Kesehatan";
        statusTxt.classList.remove('text-[#0c3859]');
        statusTxt.classList.add('text-emerald-700', 'font-black');
      }

      setTimeout(function () {
        dismissSplashScreen();
      }, 650);
    }

    window.dismissSplashScreen = function () {
      try {
        sessionStorage.setItem('diabetes_splash_seen', 'true');
      } catch (e) {}

      overlay.style.opacity = '0';
      overlay.style.transform = 'scale(1.02)';
      overlay.style.filter = 'brightness(1.15)';
      overlay.style.pointerEvents = 'none';

      setTimeout(function () {
        overlay.style.display = 'none';
        document.body.classList.remove('overflow-hidden');
      }, 700);
    };

    setTimeout(step, 180);

    // Maximum failsafe
    setTimeout(function () {
      if (!completed) finish();
    }, 5000);
  })();
</script>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Selamat Datang - Diabetes Uji Coba</title>
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

  <!-- Google Fonts: Plus Jakarta Sans & Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Tailwind CSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            display: ['"Outfit"', 'sans-serif']
          },
          colors: {
            medNavy: '#0c3859',
            medBlue: '#0085d0',
            medTeal: '#00a896',
            medCyan: '#00b4d8',
            medGreen: '#00c9a7',
            medSoftGreen: '#52b788',
          }
        }
      }
    }
  </script>

  <style>
    /* ================================================================ */
    /* KEYFRAMES & 2026 VISUAL EFFECTS                                  */
    /* ================================================================ */

    /* Wave flow animations at the bottom */
    @keyframes waveSlow {
      0% { transform: translateX(0) translateZ(0) scaleY(1); }
      50% { transform: translateX(-25%) translateZ(0) scaleY(1.08); }
      100% { transform: translateX(-50%) translateZ(0) scaleY(1); }
    }
    @keyframes waveFast {
      0% { transform: translateX(0) translateZ(0) scaleY(1); }
      50% { transform: translateX(-30%) translateZ(0) scaleY(0.92); }
      100% { transform: translateX(-60%) translateZ(0) scaleY(1); }
    }
    @keyframes waveDeep {
      0% { transform: translateX(0) translateZ(0); }
      100% { transform: translateX(-50%) translateZ(0); }
    }

    .wave-layer-1 {
      animation: waveSlow 14s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
    }
    .wave-layer-2 {
      animation: waveFast 9s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
    }
    .wave-layer-3 {
      animation: waveDeep 18s linear infinite;
    }

    /* Floating Badges */
    @keyframes floatBadge1 {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-7px) rotate(-0.5deg); }
    }
    @keyframes floatBadge2 {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-9px) rotate(0.8deg); }
    }
    @keyframes floatBadge3 {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-6px) rotate(-0.6deg); }
    }
    .animate-badge-1 { animation: floatBadge1 4.5s ease-in-out infinite; }
    .animate-badge-2 { animation: floatBadge2 5.2s ease-in-out infinite 0.6s; }
    .animate-badge-3 { animation: floatBadge3 4.8s ease-in-out infinite 1.2s; }

    /* Heart pulse */
    @keyframes heartBeating {
      0%, 100% { transform: scale(1); filter: drop-shadow(0 0 8px rgba(0,201,167,0.3)); }
      14% { transform: scale(1.15); filter: drop-shadow(0 0 16px rgba(0,201,167,0.6)); }
      28% { transform: scale(1); filter: drop-shadow(0 0 8px rgba(0,201,167,0.3)); }
      42% { transform: scale(1.1); filter: drop-shadow(0 0 12px rgba(0,201,167,0.5)); }
      70% { transform: scale(1); filter: drop-shadow(0 0 8px rgba(0,201,167,0.3)); }
    }
    .heart-pulse {
      animation: heartBeating 1.8s ease-in-out infinite;
    }

    /* ECG Line draw animation */
    @keyframes ecgTravel {
      0% { stroke-dashoffset: 200; opacity: 0.3; }
      50% { opacity: 1; }
      100% { stroke-dashoffset: 0; opacity: 0.3; }
    }
    .ecg-anim-line {
      stroke-dasharray: 200;
      stroke-dashoffset: 200;
      animation: ecgTravel 2.4s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    }

    /* Leaves swaying & floating */
    @keyframes leafSway1 {
      0%, 100% { transform: translate(0, 0) rotate(0deg); }
      50% { transform: translate(-8px, -12px) rotate(-6deg); }
    }
    @keyframes leafSway2 {
      0%, 100% { transform: translate(0, 0) rotate(0deg); }
      50% { transform: translate(10px, -14px) rotate(8deg); }
    }
    @keyframes leafSway3 {
      0%, 100% { transform: translate(0, 0) rotate(0deg); }
      50% { transform: translate(-12px, 8px) rotate(-10deg); }
    }
    .leaf-1 { animation: leafSway1 6s ease-in-out infinite; }
    .leaf-2 { animation: leafSway2 7s ease-in-out infinite 0.8s; }
    .leaf-3 { animation: leafSway3 5.5s ease-in-out infinite 1.5s; }

    /* Floating light particles */
    @keyframes particleRise {
      0% { transform: translateY(0) scale(0.8); opacity: 0; }
      20% { opacity: 0.8; }
      80% { opacity: 0.8; }
      100% { transform: translateY(-160px) scale(1.2); opacity: 0; }
    }
    .particle {
      position: absolute;
      border-radius: 9999px;
      pointer-events: none;
      animation: particleRise 6s linear infinite;
    }

    /* Glossy Bar Shine */
    @keyframes barShimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(200%); }
    }
    .bar-shine {
      animation: barShimmer 2.2s ease-in-out infinite;
    }

    /* Ambient Sunray Rotation */
    @keyframes sunrayRotate {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    .sunray-halo {
      animation: sunrayRotate 90s linear infinite;
    }

    /* Water droplets ripple */
    @keyframes dropletFloat {
      0%, 100% { transform: translateY(0) scale(1); opacity: 0.7; }
      50% { transform: translateY(-8px) scale(1.1); opacity: 1; }
    }
    .droplet {
      animation: dropletFloat 3.5s ease-in-out infinite;
    }

    /* Reduced Motion respect */
    @media (prefers-reduced-motion: reduce) {
      *, ::before, ::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }
  </style>
</head>
<body class="bg-[#dcf4fd] font-sans antialiased text-gray-800 m-0 p-0 overflow-hidden select-none">

  <!-- ============================================================== -->
  <!-- FULL SCREEN SPLASH CONTAINER                                   -->
  <!-- ============================================================== -->
  <div 
    id="splashContainer" 
    class="relative w-screen h-screen overflow-hidden flex flex-col justify-between items-center transition-all duration-700 ease-out"
  >
    
    <!-- ============================================================ -->
    <!-- 1. BACKDROP: SUNLIT SKY, MOUNTAINS, AND HOSPITAL SILHOUETTE  -->
    <!-- ============================================================ -->
    <div class="absolute inset-0 z-0 pointer-events-none">
      
      <!-- Primary Rich Background Image -->
      @if(file_exists(public_path('assets/splash_bg_clean.jpg')))
        <div 
          class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-[1.01] transform transition-transform duration-1000 filter brightness-105"
          style="background-image: url('{{ asset('assets/splash_bg_clean.jpg') }}');"
        ></div>
        <!-- Luminous soft atmosphere overlay for enhanced text readability -->
        <div class="absolute inset-0 bg-gradient-to-b from-white/30 via-white/10 to-[#0077b6]/35"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-white/40 via-transparent to-white/30"></div>
      @elseif(file_exists(public_path('assets/splash_reference.png')))
        <div 
          class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-[1.01] transform transition-transform duration-1000 filter brightness-105"
          style="background-image: url('{{ asset('assets/splash_reference.png') }}');"
        ></div>
        <div class="absolute inset-0 bg-gradient-to-b from-[#e3f6fd]/30 via-transparent to-[#0096c7]/40 mix-blend-overlay"></div>
      @else
        <!-- Fallback High Definition Healthcare Landscape SVG/CSS -->
        <div class="absolute inset-0 bg-gradient-to-b from-[#bfe7f9] via-[#e2f5fc] to-[#7ed0ea]"></div>
      @endif

      <!-- Sunrays Halo from Top Right -->
      <div class="absolute -top-20 -right-20 w-[600px] h-[600px] bg-gradient-to-br from-yellow-200/40 via-white/30 to-transparent rounded-full blur-3xl pointer-events-none sunray-halo"></div>
      
      <!-- Ambient Sun Flare Beam -->
      <div class="absolute top-0 right-1/4 w-96 h-[80vh] bg-gradient-to-b from-white/30 via-white/10 to-transparent rotate-[-25deg] blur-2xl pointer-events-none"></div>

      <!-- Distant Modern Hospital Building Silhouette (Right Horizon) -->
      <div class="absolute bottom-36 sm:bottom-48 right-6 sm:right-20 w-28 sm:w-44 h-56 sm:h-72 opacity-25 pointer-events-none">
        <svg viewBox="0 0 160 280" fill="none" class="w-full h-full text-teal-800">
          <rect x="30" y="20" width="100" height="260" rx="8" fill="currentColor" fill-opacity="0.5"/>
          <rect x="50" y="5" width="60" height="25" rx="4" fill="currentColor" fill-opacity="0.6"/>
          <!-- Windows grid pattern -->
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
            <rect x="42" y="115" width="16" height="12" rx="2"/>
            <rect x="68" y="115" width="16" height="12" rx="2"/>
            <rect x="94" y="115" width="16" height="12" rx="2"/>
          </g>
          <!-- Modern Clinic Cross Symbol -->
          <circle cx="80" cy="18" r="7" fill="#ffffff" fill-opacity="0.9"/>
          <path d="M77 18h6M80 15v6" stroke="#00a896" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </div>

      <!-- Glowing Light Particles (Bokeh) -->
      <div class="particle w-3 h-3 bg-white/70 shadow-sm shadow-white left-[15%] bottom-[20%]" style="animation-delay: 0s;"></div>
      <div class="particle w-2 h-2 bg-teal-200/80 shadow-sm shadow-teal-300 left-[35%] bottom-[25%]" style="animation-delay: 1.5s;"></div>
      <div class="particle w-4 h-4 bg-white/60 shadow-md shadow-white left-[55%] bottom-[18%]" style="animation-delay: 3s;"></div>
      <div class="particle w-2.5 h-2.5 bg-emerald-200/80 shadow-sm shadow-emerald-200 left-[75%] bottom-[22%]" style="animation-delay: 2.2s;"></div>
      <div class="particle w-3 h-3 bg-cyan-100/70 shadow-sm shadow-cyan-200 left-[85%] bottom-[30%]" style="animation-delay: 4s;"></div>
    </div>

    <!-- ============================================================ -->
    <!-- TOP BAR: BRAND PILL & SKIP BUTTON                            -->
    <!-- ============================================================ -->
    <header class="relative z-30 w-full max-w-7xl mx-auto px-4 sm:px-8 pt-5 sm:pt-6 flex items-center justify-between">
      
      <!-- Brand Logo Mini Pill -->
      <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 bg-white/70 backdrop-blur-md rounded-full border border-white/80 shadow-sm">
        <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-[#0085d0] to-[#00c9a7] flex items-center justify-center text-white text-xs font-black shadow-xs">
          +
        </div>
        <span class="font-extrabold text-xs sm:text-sm text-medNavy tracking-tight">
          Diabetes <span class="text-medCyan">Uji Coba</span>
        </span>
      </div>

      <!-- Skip Button: Langsung ke Beranda -->
      <button 
        type="button" 
        id="btnSkipSplash" 
        onclick="finishAndRedirect()"
        class="inline-flex items-center space-x-1.5 px-4 py-2 bg-white/75 hover:bg-white text-medNavy font-bold text-xs rounded-full border border-white/90 shadow-sm hover:shadow-md backdrop-blur-md transition-all active:scale-95 cursor-pointer group"
      >
        <span>Lewati</span>
        <span class="group-hover:translate-x-0.5 transition-transform text-medTeal">&rarr;</span>
      </button>

    </header>

    <!-- ============================================================ -->
    <!-- 2. MAIN CENTER HERO CONTENT & FLOATING GLASS PILLS           -->
    <!-- ============================================================ -->
    <main class="relative z-20 w-full max-w-6xl mx-auto px-4 sm:px-6 flex flex-col items-center justify-center text-center my-auto py-2">
      
      <!-- Grid Wrapper: Left floating cards (desktop) + Center Typography -->
      <div class="w-full relative flex flex-col items-center justify-center">

        <!-- -------------------------------------------------------- -->
        <!-- LEFT FLOATING GLASS PILL CARDS (Desktop & Tablet)        -->
        <!-- -------------------------------------------------------- -->
        <div class="hidden md:flex flex-col space-y-3.5 absolute left-0 lg:left-4 top-1/2 -translate-y-1/2 text-left z-20 pointer-events-none">
          
          <!-- Card 1: Sehat -->
          <div class="animate-badge-1 flex items-center space-x-3 px-4 py-2.5 bg-white/80 backdrop-blur-xl rounded-2xl border border-white/90 shadow-lg shadow-teal-900/5 min-w-[170px]">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#00b4d8] to-[#00c9a7] flex items-center justify-center text-white text-base shadow-sm">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
            </div>
            <div>
              <h4 class="font-black text-xs text-medNavy leading-tight">Sehat</h4>
              <p class="text-[10px] text-gray-500 font-medium">Untuk Hari Ini</p>
            </div>
          </div>

          <!-- Card 2: Bahagia -->
          <div class="animate-badge-2 flex items-center space-x-3 px-4 py-2.5 bg-white/80 backdrop-blur-xl rounded-2xl border border-white/90 shadow-lg shadow-teal-900/5 min-w-[170px]">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#00a896] to-[#02c39a] flex items-center justify-center text-white text-base shadow-sm">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
              </svg>
            </div>
            <div>
              <h4 class="font-black text-xs text-medNavy leading-tight">Bahagia</h4>
              <p class="text-[10px] text-gray-500 font-medium">Untuk Nanti</p>
            </div>
          </div>

          <!-- Card 3: Bersama -->
          <div class="animate-badge-3 flex items-center space-x-3 px-4 py-2.5 bg-white/80 backdrop-blur-xl rounded-2xl border border-white/90 shadow-lg shadow-teal-900/5 min-w-[170px]">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#0085d0] to-[#00b4d8] flex items-center justify-center text-white text-base shadow-sm">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
            </div>
            <div>
              <h4 class="font-black text-xs text-medNavy leading-tight">Bersama</h4>
              <p class="text-[10px] text-gray-500 font-medium">Lebih Baik</p>
            </div>
          </div>

        </div>

        <!-- -------------------------------------------------------- -->
        <!-- CENTER BRANDING & TYPOGRAPHY                             -->
        <!-- -------------------------------------------------------- -->
        <div class="max-w-2xl mx-auto flex flex-col items-center">
          
          <!-- Heart with ECG Icon (Centerpiece) -->
          <div class="relative mb-3 flex items-center justify-center">
            
            <!-- Horizontal Left Accent Line -->
            <div class="w-12 sm:w-20 h-0.5 bg-gradient-to-r from-transparent via-[#00a896]/60 to-[#00c9a7] rounded-full mr-3 sm:mr-4"></div>

            <!-- Heart Contour with ECG Inside -->
            <div class="relative w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center heart-pulse">
              <svg viewBox="0 0 100 90" fill="none" class="w-full h-full drop-shadow-md">
                <!-- Heart Outline Gradient -->
                <path 
                  d="M50 82 L18 50 C8 40 8 24 20 12 C32 0 46 6 50 16 C54 6 68 0 80 12 C92 24 92 40 82 50 Z" 
                  stroke="url(#heartGrad)" 
                  stroke-width="5" 
                  stroke-linejoin="round"
                  fill="#ffffff"
                  fill-opacity="0.85"
                />
                <!-- Pulse Line inside Heart -->
                <path 
                  d="M26 46 L38 46 L43 32 L49 58 L54 38 L58 48 L64 46 L74 46" 
                  stroke="#00a896" 
                  stroke-width="4.5" 
                  stroke-linecap="round" 
                  stroke-linejoin="round"
                  class="ecg-anim-line"
                />
                <defs>
                  <linearGradient id="heartGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#0085d0"/>
                    <stop offset="50%" stop-color="#00a896"/>
                    <stop offset="100%" stop-color="#00c9a7"/>
                  </linearGradient>
                </defs>
              </svg>
            </div>

            <!-- Horizontal Right Accent Line -->
            <div class="w-12 sm:w-20 h-0.5 bg-gradient-to-l from-transparent via-[#00a896]/60 to-[#00c9a7] rounded-full ml-3 sm:mr-4"></div>

          </div>

          <!-- Main Title: "Selamat Datang" -->
          <h1 class="font-display font-extrabold text-4xl sm:text-6xl lg:text-7xl tracking-tight leading-none text-medNavy drop-shadow-sm mb-2.5">
            Selamat <span class="bg-gradient-to-r from-[#0085d0] via-[#00a896] to-[#00c9a7] bg-clip-text text-transparent">Datang</span>
          </h1>

          <!-- Slogan: "Anda Sehat, Kami Senang" -->
          <div class="relative inline-block mb-3">
            <h2 class="font-display text-xl sm:text-3xl font-extrabold text-medNavy tracking-normal">
              Anda Sehat <span class="text-[#00a896]">Kami Senang</span>
            </h2>
            
            <!-- Curved Cyan Swoop Underline SVG -->
            <svg class="w-48 sm:w-64 h-3.5 mx-auto mt-1" viewBox="0 0 240 14" fill="none">
              <path d="M4 10 C60 1 180 1 236 10" stroke="url(#swoopGrad)" stroke-width="3" stroke-linecap="round"/>
              <defs>
                <linearGradient id="swoopGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                  <stop offset="0%" stop-color="#00b4d8" stop-opacity="0.3"/>
                  <stop offset="50%" stop-color="#00c9a7"/>
                  <stop offset="100%" stop-color="#00b4d8" stop-opacity="0.3"/>
                </linearGradient>
              </defs>
            </svg>
          </div>

          <!-- Subtitle / Teks Pendukung -->
          <p class="text-xs sm:text-sm md:text-base font-semibold text-slate-500 max-w-md mx-auto mb-6 sm:mb-8 leading-relaxed">
            Menuju Hidup yang Lebih Sehat dan Bahagia
          </p>

          <!-- -------------------------------------------------------- -->
          <!-- 3. GLOSSY PROGRESS BAR & PERCENTAGE PILL                 -->
          <!-- -------------------------------------------------------- -->
          <div class="w-full max-w-xs sm:max-w-md mx-auto px-2 space-y-3.5">
            
            <div class="flex items-center space-x-3">
              
              <!-- Progress Track Pill with Glass Effect -->
              <div class="flex-1 h-5 sm:h-6 bg-white/70 backdrop-blur-xl rounded-full p-1 border border-white shadow-inner shadow-slate-300/40 relative overflow-hidden">
                
                <!-- Active Progress Fill with Glow -->
                <div 
                  id="splashProgressBar" 
                  class="h-full rounded-full bg-gradient-to-r from-[#0085d0] via-[#00b4d8] to-[#00c9a7] transition-all duration-150 ease-out relative overflow-hidden shadow-sm"
                  style="width: 8%;"
                >
                  <!-- Glossy Shine Highlight Layer -->
                  <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent bar-shine w-full"></div>
                </div>

              </div>

              <!-- Real-time Percentage Indicator Pill -->
              <div class="flex-shrink-0">
                <span 
                  id="splashPercentText" 
                  class="inline-flex items-center justify-center min-w-[52px] px-2.5 py-1 rounded-full bg-[#00a896] text-white font-extrabold text-xs sm:text-sm shadow-md shadow-teal-500/30 font-display"
                >
                  8%
                </span>
              </div>

            </div>

            <!-- Loading Status Indicator & Text -->
            <div class="flex items-center justify-center space-x-2 text-xs text-slate-600 font-semibold tracking-wide">
              <!-- Moving ECG Pulse Icon -->
              <svg class="w-5 h-5 text-medTeal animate-pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h4l3-8 4 16 3-8h4"/>
              </svg>
              <span id="splashStatusText" class="tracking-wider uppercase text-[11px] sm:text-xs font-bold text-medNavy transition-opacity duration-300">
                M E M U A T . . .
              </span>
            </div>

          </div>

        </div>

      </div>

    </main>

    <!-- ============================================================ -->
    <!-- 4. NATURAL BLUE WATER OCEAN & WAVES AT BOTTOM                -->
    <!-- ============================================================ -->
    <div class="relative w-full z-10 pointer-events-none mt-auto">
      
      <!-- Rising Water Bubbles -->
      <div class="absolute bottom-12 left-[20%] w-3 h-3 bg-white/70 rounded-full droplet" style="animation-delay: 0.2s;"></div>
      <div class="absolute bottom-8 left-[45%] w-4 h-4 bg-white/60 rounded-full droplet" style="animation-delay: 1.1s;"></div>
      <div class="absolute bottom-14 left-[65%] w-2.5 h-2.5 bg-white/70 rounded-full droplet" style="animation-delay: 0.7s;"></div>
      <div class="absolute bottom-10 left-[82%] w-3.5 h-3.5 bg-white/60 rounded-full droplet" style="animation-delay: 1.8s;"></div>

      <!-- Wave SVG Container -->
      <div class="relative w-full h-24 sm:h-36 md:h-44 overflow-hidden">
        
        <!-- Back Deep Wave (Dark Teal / Azure) -->
        <svg 
          class="wave-layer-3 absolute bottom-0 left-0 w-[200%] h-full opacity-40" 
          viewBox="0 0 1440 180" 
          preserveAspectRatio="none"
        >
          <path 
            fill="#0077b6" 
            d="M0,64L60,80C120,96,240,128,360,133.3C480,139,600,117,720,101.3C840,85,960,75,1080,85.3C1200,96,1320,128,1380,144L1440,160L1440,180L1380,180C1320,180,1200,180,1080,180C960,180,840,180,720,180C600,180,480,180,360,180C240,180,120,180,60,180L0,180Z"
          ></path>
        </svg>

        <!-- Middle Translucent Wave (Cyan / Blue) -->
        <svg 
          class="wave-layer-2 absolute bottom-0 left-0 w-[200%] h-full opacity-65" 
          viewBox="0 0 1440 180" 
          preserveAspectRatio="none"
        >
          <path 
            fill="#0096c7" 
            d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,117.3C672,107,768,117,864,128C960,139,1056,149,1152,144C1248,139,1344,117,1392,106.7L1440,96L1440,180L1392,180C1344,180,1248,180,1152,180C1056,180,960,180,864,180C768,180,672,180,576,180C480,180,384,180,288,180C192,180,96,180,48,180L0,180Z"
          ></path>
        </svg>

        <!-- Front Sparkling Wave with Sunlit Crest (Crystal Turquoise Blue) -->
        <svg 
          class="wave-layer-1 absolute bottom-0 left-0 w-[200%] h-full opacity-90" 
          viewBox="0 0 1440 180" 
          preserveAspectRatio="none"
        >
          <path 
            fill="url(#frontWaterGrad)" 
            d="M0,128L60,122.7C120,117,240,107,360,112C480,117,600,139,720,144C840,149,960,139,1080,122.7C1200,107,1320,85,1380,74.7L1440,64L1440,180L1380,180C1320,180,1200,180,1080,180C960,180,840,180,720,180C600,180,480,180,360,180C240,180,120,180,60,180L0,180Z"
          ></path>
          <defs>
            <linearGradient id="frontWaterGrad" x1="0%" y1="0%" x2="0%" y2="100%">
              <stop offset="0%" stop-color="#48cae4"/>
              <stop offset="40%" stop-color="#0096c7"/>
              <stop offset="100%" stop-color="#0077b6"/>
            </linearGradient>
          </defs>
        </svg>

        <!-- Wave Caustics Shimmer Line -->
        <div class="absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-t from-[#005f73]/50 to-transparent"></div>

      </div>

    </div>

    <!-- ============================================================ -->
    <!-- 5. FLOATING GREEN LEAVES ACCENTS (Wind Blown)                 -->
    <!-- ============================================================ -->
    <!-- Top Right Leaf -->
    <div class="leaf-1 absolute top-4 sm:top-8 right-4 sm:right-12 w-14 sm:w-20 pointer-events-none z-20 opacity-90">
      <svg viewBox="0 0 80 80" fill="none" class="w-full h-full drop-shadow-md">
        <path d="M10 70 C10 70, 20 20, 70 10 C70 10, 60 60, 10 70 Z" fill="url(#leafGrad1)"/>
        <path d="M10 70 Q40 40 70 10" stroke="#a7f3d0" stroke-width="2"/>
        <defs>
          <linearGradient id="leafGrad1" x1="0%" y1="100%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="#059669"/>
            <stop offset="100%" stop-color="#34d399"/>
          </linearGradient>
        </defs>
      </svg>
    </div>

    <!-- Center Left Leaf -->
    <div class="leaf-2 absolute top-1/3 left-3 sm:left-10 w-10 sm:w-14 pointer-events-none z-20 opacity-85">
      <svg viewBox="0 0 80 80" fill="none" class="w-full h-full drop-shadow-sm">
        <path d="M70 70 C70 70, 60 20, 10 10 C10 10, 20 60, 70 70 Z" fill="url(#leafGrad2)"/>
        <path d="M70 70 Q40 40 10 10" stroke="#a7f3d0" stroke-width="1.8"/>
        <defs>
          <linearGradient id="leafGrad2" x1="100%" y1="100%" x2="0%" y2="0%">
            <stop offset="0%" stop-color="#10b981"/>
            <stop offset="100%" stop-color="#6ee7b7"/>
          </linearGradient>
        </defs>
      </svg>
    </div>

    <!-- Bottom Left Leaf -->
    <div class="leaf-3 absolute bottom-28 sm:bottom-40 left-4 sm:left-16 w-12 sm:w-16 pointer-events-none z-20 opacity-90">
      <svg viewBox="0 0 80 80" fill="none" class="w-full h-full drop-shadow-md">
        <path d="M15 65 C15 65, 30 15, 65 15 C65 15, 50 65, 15 65 Z" fill="url(#leafGrad3)"/>
        <path d="M15 65 Q40 40 65 15" stroke="#a7f3d0" stroke-width="1.8"/>
        <defs>
          <linearGradient id="leafGrad3" x1="0%" y1="100%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="#047857"/>
            <stop offset="100%" stop-color="#10b981"/>
          </linearGradient>
        </defs>
      </svg>
    </div>

  </div>

  <!-- ============================================================== -->
  <!-- JAVASCRIPT CONTROLLER: PROGRESS BAR & AUTO REDIRECT            -->
  <!-- ============================================================== -->
  <script>
    (function () {
      let currentProgress = 5;
      const targetUrl = "{{ route('home') }}";
      const progressBar = document.getElementById('splashProgressBar');
      const percentText = document.getElementById('splashPercentText');
      const statusText = document.getElementById('splashStatusText');
      const splashContainer = document.getElementById('splashContainer');
      let isCompleted = false;

      // Check preview mode (?preview=1 or ?hold=1) to allow inspecting the design indefinitely
      const urlParams = new URLSearchParams(window.location.search);
      const isPreview = urlParams.has('preview') || urlParams.has('hold');

      // Realistic stages for smooth healthcare initialization
      const stages = [
        { threshold: 25, text: "Memuat Indikator Kesehatan..." },
        { threshold: 55, text: "Menyiapkan Pengalaman Kesehatan Terbaik..." },
        { threshold: 85, text: "Menyelaraskan Parameter Klinis..." },
        { threshold: 99, text: "Memulai Layanan..." }
      ];

      function updateProgress() {
        if (isCompleted) return;

        if (isPreview && currentProgress >= 70) {
          currentProgress = 70;
          renderProgress(70);
          if (statusText) statusText.textContent = "Menyiapkan Pengalaman Kesehatan Terbaik...";
          return; // Stay at 70% in preview mode until Lewati is clicked
        }

        // Dynamic increments for a natural organic feel
        if (currentProgress < 30) {
          currentProgress += Math.floor(Math.random() * 4) + 2;
        } else if (currentProgress < 70) {
          currentProgress += Math.floor(Math.random() * 5) + 3;
        } else if (currentProgress < 95) {
          currentProgress += Math.floor(Math.random() * 3) + 2;
        } else {
          currentProgress += 1;
        }

        if (currentProgress >= 100) {
          currentProgress = 100;
          renderProgress(currentProgress);
          triggerCompletion();
          return;
        }

        renderProgress(currentProgress);

        // Update status text based on threshold
        for (let i = 0; i < stages.length; i++) {
          if (currentProgress <= stages[i].threshold) {
            if (statusText && statusText.textContent !== stages[i].text) {
              statusText.textContent = stages[i].text;
            }
            break;
          }
        }

        // Variable interval for lively realism (~3.2s total duration)
        const delay = Math.floor(Math.random() * 50) + 70;
        setTimeout(updateProgress, delay);
      }

      function renderProgress(value) {
        if (progressBar) progressBar.style.width = value + '%';
        if (percentText) percentText.textContent = value + '%';
      }

      function triggerCompletion() {
        isCompleted = true;
        renderProgress(100);

        if (statusText) {
          statusText.textContent = "Selamat Datang di Layanan Kesehatan";
          statusText.classList.remove('text-medNavy');
          statusText.classList.add('text-emerald-700', 'font-black');
        }

        // Brief pleasant pause at 100% then fade out seamlessly
        setTimeout(function () {
          finishAndRedirect();
        }, 650);
      }

      // Transition to homepage
      window.finishAndRedirect = function () {
        if (splashContainer) {
          splashContainer.style.opacity = '0';
          splashContainer.style.transform = 'scale(1.02)';
          splashContainer.style.filter = 'brightness(1.15)';
        }

        // Mark in sessionStorage so regular back-navigation isn't looped
        try {
          sessionStorage.setItem('splash_completed', 'true');
        } catch (e) {}

        setTimeout(function () {
          window.location.href = targetUrl;
        }, 550);
      };

      // Start loading sequence when DOM is ready
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
          setTimeout(updateProgress, 200);
        });
      } else {
        setTimeout(updateProgress, 200);
      }

      // Maximum failsafe fallback (in case browser tab was suspended)
      setTimeout(function () {
        if (!isCompleted && !isPreview) {
          triggerCompletion();
        }
      }, 7000);
    })();
  </script>
</body>
</html>

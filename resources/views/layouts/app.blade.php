<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Diabetes Uji Coba | Deteksi Dini Risiko Diabetes Berbasis AI & Machine Learning')</title>
  
  <!-- Google Fonts: Plus Jakarta Sans & Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            kd: {
              green: '#00BFA6',
              greenDark: '#009d88',
              greenLight: '#EAF7FF',
              blue: '#168FE5',
              blueSecondary: '#43B4F2',
              navy: '#123B5D',
              text: '#123B5D',
              muted: '#5C7893'
            }
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <style>
    html, body {
      overflow-x: hidden;
    }
    html { scroll-behavior: smooth; }
    .kd-bullet {
      width: 18px;
      height: 18px;
      background-color: #00BFA6;
      border-radius: 4px;
      flex-shrink: 0;
      margin-top: 3px;
    }
    .hero-wave {
      position: absolute;
      bottom: -1px;
      left: 0;
      width: 100%;
      overflow: hidden;
      line-height: 0;
    }
    /* Custom sleek scrollbar for sticky sidebars */
    .custom-scrollbar::-webkit-scrollbar {
      width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }

    /* ============================================================== */
    /* 1. SMOOTH HOSPITAL BACKGROUND MOTION                          */
    /* ============================================================== */
    @keyframes hospitalBgMotion {
      0% {
        transform: scale(1.02) translate3d(0, 0, 0);
      }
      50% {
        transform: scale(1.06) translate3d(-10px, -6px, 0);
      }
      100% {
        transform: scale(1.02) translate3d(0, 0, 0);
      }
    }
    .hospital-bg-animated {
      animation: hospitalBgMotion 32s ease-in-out infinite alternate;
      will-change: transform;
    }

    /* ============================================================== */
    /* 2. ELEGANT FLYING BIRDS KEYFRAMES (DUAL OPPOSITE FLIGHT PATHS) */
    /* ============================================================== */
    
    /* LEFT-TO-RIGHT FLIGHT PATHS (Terbang dari Kiri ke Kanan) */
    @keyframes birdFlyLTR1 {
      0% { transform: translate3d(-14vw, 2vh, 0) scale(0.65) rotate(4deg); opacity: 0; }
      8% { opacity: 0.85; }
      85% { opacity: 0.85; }
      100% { transform: translate3d(114vw, -5vh, 0) scale(0.65) rotate(4deg); opacity: 0; }
    }

    @keyframes birdFlyLTR2 {
      0% { transform: translate3d(-10vw, -2vh, 0) scale(0.48) rotate(3deg); opacity: 0; }
      6% { opacity: 0.75; }
      85% { opacity: 0.75; }
      100% { transform: translate3d(112vw, -8vh, 0) scale(0.48) rotate(3deg); opacity: 0; }
    }

    @keyframes birdFlyLTR3 {
      0% { transform: translate3d(-12vw, 1vh, 0) scale(0.55) rotate(5deg); opacity: 0; }
      8% { opacity: 0.8; }
      88% { opacity: 0.8; }
      100% { transform: translate3d(112vw, -6vh, 0) scale(0.55) rotate(5deg); opacity: 0; }
    }

    @keyframes birdFlyLTR4 {
      0% { transform: translate3d(-10vw, -3vh, 0) scale(0.42) rotate(2deg); opacity: 0; }
      6% { opacity: 0.7; }
      85% { opacity: 0.7; }
      100% { transform: translate3d(110vw, -7vh, 0) scale(0.42) rotate(2deg); opacity: 0; }
    }

    @keyframes birdFlyLTR5 {
      0% { transform: translate3d(-15vw, 4vh, 0) scale(0.62) rotate(4deg); opacity: 0; }
      8% { opacity: 0.85; }
      85% { opacity: 0.85; }
      100% { transform: translate3d(113vw, -3vh, 0) scale(0.62) rotate(4deg); opacity: 0; }
    }

    /* RIGHT-TO-LEFT FLIGHT PATHS (Terbang dari Kanan ke Kiri) */
    @keyframes birdFlyRTL1 {
      0% { transform: translate3d(115vw, 6vh, 0) scale(-0.85, 0.85) rotate(-5deg); opacity: 0; }
      10% { opacity: 0.9; }
      88% { opacity: 0.9; }
      100% { transform: translate3d(-15vw, -3vh, 0) scale(-0.85, 0.85) rotate(-5deg); opacity: 0; }
    }

    @keyframes birdFlyRTL2 {
      0% { transform: translate3d(114vw, 3vh, 0) scale(-0.70, 0.70) rotate(-4deg); opacity: 0; }
      8% { opacity: 0.85; }
      86% { opacity: 0.85; }
      100% { transform: translate3d(-14vw, -5vh, 0) scale(-0.70, 0.70) rotate(-4deg); opacity: 0; }
    }

    @keyframes birdFlyRTL3 {
      0% { transform: translate3d(116vw, 5vh, 0) scale(-0.78, 0.78) rotate(-4deg); opacity: 0; }
      10% { opacity: 0.9; }
      86% { opacity: 0.9; }
      100% { transform: translate3d(-16vw, 0vh, 0) scale(-0.78, 0.78) rotate(-4deg); opacity: 0; }
    }

    @keyframes birdFlyRTL4 {
      0% { transform: translate3d(112vw, -1vh, 0) scale(-0.52, 0.52) rotate(-3deg); opacity: 0; }
      7% { opacity: 0.75; }
      85% { opacity: 0.75; }
      100% { transform: translate3d(-12vw, -7vh, 0) scale(-0.52, 0.52) rotate(-3deg); opacity: 0; }
    }

    @keyframes birdFlyRTL5 {
      0% { transform: translate3d(115vw, 7vh, 0) scale(-0.82, 0.82) rotate(-5deg); opacity: 0; }
      10% { opacity: 0.9; }
      88% { opacity: 0.9; }
      100% { transform: translate3d(-15vw, 1vh, 0) scale(-0.82, 0.82) rotate(-5deg); opacity: 0; }
    }

    /* Realistic wing flapping */
    @keyframes wingLeft {
      0%, 100% { transform: rotate(0deg); }
      50% { transform: rotate(-34deg) translateY(-2px); }
    }
    @keyframes wingRight {
      0%, 100% { transform: rotate(0deg); }
      50% { transform: rotate(34deg) translateY(-2px); }
    }

    .bird-wing-l {
      transform-origin: 16px 9px;
      animation: wingLeft 0.75s ease-in-out infinite;
    }
    .bird-wing-r {
      transform-origin: 16px 9px;
      animation: wingRight 0.75s ease-in-out infinite;
    }

    /* BURUNG DARI KIRI KE KANAN (LTR) */
    .bird-1 { animation: birdFlyLTR1 27s linear infinite; animation-delay: -5s; }
    .bird-3 { animation: birdFlyLTR2 35s linear infinite; animation-delay: -18s; }
    .bird-5 { animation: birdFlyLTR3 29s linear infinite; animation-delay: -10s; }
    .bird-7 { animation: birdFlyLTR4 38s linear infinite; animation-delay: -24s; }
    .bird-9 { animation: birdFlyLTR5 31s linear infinite; animation-delay: -14s; }

    /* BURUNG DARI KANAN KE KIRI (RTL) */
    .bird-2 { animation: birdFlyRTL1 25s linear infinite; animation-delay: -12s; }
    .bird-4 { animation: birdFlyRTL2 28s linear infinite; animation-delay: -4s; }
    .bird-6 { animation: birdFlyRTL3 23s linear infinite; animation-delay: -16s; }
    .bird-8 { animation: birdFlyRTL4 34s linear infinite; animation-delay: -8s; }
    .bird-10 { animation: birdFlyRTL5 26s linear infinite; animation-delay: -20s; }

    /* Respect reduced-motion preferences */
    @media (prefers-reduced-motion: reduce) {
      .hospital-bg-animated {
        animation: none !important;
      }
      .bird-layer {
        display: none !important;
      }
    }
  </style>
  @stack('styles')
</head>
<body class="bg-white text-kd-text font-sans antialiased min-h-screen flex flex-col justify-between overflow-x-hidden">

  <!-- ============================================================== -->
  <!-- 1. NAVBAR HEADER                                               -->
  <!-- ============================================================== -->
  <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
      
      <!-- Left: Logo & Brand Name -->
      <div class="flex items-center space-x-4 sm:space-x-6">
        <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
          <x-site-logo size="md" />
        </a>
      </div>

      <!-- Center: Navigation Links -->
      <nav class="hidden md:flex items-center space-x-6 text-sm font-semibold text-gray-700">
        @if(request()->is('admin*') || request()->routeIs('history'))
          <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-[#00BFA6] font-bold' : 'hover:text-[#00BFA6]' }} flex items-center gap-1.5 py-1">
            <span>Dashboard</span>
            <span class="px-1.5 py-0.5 bg-[#EAF7FF] text-[#168FE5] text-[10px] font-extrabold rounded-md uppercase border border-[#43B4F2]/30">Admin</span>
          </a>
          <a href="{{ route('admin.articles.index') }}" class="{{ request()->routeIs('admin.articles.*') ? 'text-[#00BFA6] font-bold' : 'hover:text-[#00BFA6]' }} py-1">
            Kelola Berita
          </a>
          <a href="{{ route('admin.content.index') }}" class="{{ request()->routeIs('admin.content.*') ? 'text-[#00BFA6] font-bold' : 'hover:text-[#00BFA6]' }} py-1">
            Kelola Tampilan
          </a>
          <a href="{{ route('history') }}" class="{{ request()->routeIs('history') ? 'text-[#00BFA6] font-bold' : 'hover:text-[#00BFA6]' }} py-1">
            Riwayat Skrining
          </a>
          <span class="text-gray-300">|</span>
          <a href="{{ route('home') }}" target="_blank" class="text-xs font-semibold text-gray-400 hover:text-[#00BFA6] flex items-center gap-1 transition-colors">
            <span>Lihat Website Publik</span>
            <span>&rarr;</span>
          </a>
        @else
          <!-- TAMPILAN PUBLIK / PENGUNJUNG -->
          <div class="relative py-1 flex flex-col items-center">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#00BFA6] font-bold' : 'hover:text-[#00BFA6]' }} transition-colors">Beranda</a>
            @if(request()->routeIs('home'))
              <span class="w-6 h-0.5 bg-[#00BFA6] rounded-full mt-1"></span>
            @endif
          </div>

          <!-- Navbar Dropdown Menu: Tentang Kami (+) / (-) -->
          <div class="relative py-1" id="navDropdownContainer">
            <button 
              type="button" 
              id="navDropdownBtn" 
              aria-haspopup="true"
              aria-expanded="false" 
              class="flex items-center gap-1.5 {{ request()->routeIs('about') ? 'text-[#00BFA6] font-bold' : 'text-[#123B5D] hover:text-[#00BFA6] font-semibold' }} transition-colors focus:outline-none cursor-pointer"
            >
              <span>Tentang Kami</span>
              <span id="navDropdownIcon" class="w-4 h-4 rounded-full bg-[#EAF7FF] text-[#00BFA6] border border-[#00BFA6]/30 flex items-center justify-center text-xs font-bold leading-none select-none transition-transform duration-200">+</span>
            </button>
            
            <div 
              id="navDropdownMenu" 
              class="absolute left-0 mt-3 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 py-2.5 opacity-0 invisible translate-y-2 transition-all duration-200 ease-out z-50 pointer-events-none"
            >
              <!-- 1. Tentang Kami -->
              <a href="{{ route('about') }}#tentang-kami" class="desktop-sub-link flex items-start gap-2.5 px-4 py-2.5 text-xs text-gray-700 hover:bg-[#EAF7FF] hover:text-[#168FE5] transition-colors font-medium">
                <span class="text-sm mt-0.5 text-[#168FE5]">ℹ️</span>
                <div>
                  <span class="font-bold block text-gray-900">Tentang Kami</span>
                  <span class="text-[11px] text-[#5C7893]">Profil &amp; tujuan platform skrining</span>
                </div>
              </a>
              <!-- 2. Orang yang Analisis -->
              <a href="{{ route('about') }}#analis" class="desktop-sub-link flex items-start gap-2.5 px-4 py-2.5 text-xs text-gray-700 hover:bg-[#EAF7FF] hover:text-[#168FE5] transition-colors font-medium">
                <span class="text-sm mt-0.5 text-[#168FE5]">🔬</span>
                <div>
                  <span class="font-bold block text-gray-900">Orang yang Analisis</span>
                  <span class="text-[11px] text-[#5C7893]">Analisis model &amp; data klinis</span>
                </div>
              </a>
              <!-- 3. Biodata -->
              <a href="{{ route('about') }}#biodata" class="desktop-sub-link flex items-start gap-2.5 px-4 py-2.5 text-xs text-gray-700 hover:bg-[#EAF7FF] hover:text-[#168FE5] transition-colors font-medium">
                <span class="text-sm mt-0.5 text-[#168FE5]">👤</span>
                <div>
                  <span class="font-bold block text-gray-900">Biodata</span>
                  <span class="text-[11px] text-[#5C7893]">Biodata profil pengembang &amp; tim</span>
                </div>
              </a>
            </div>
          </div>

          <a href="{{ route('quiz') }}" class="{{ request()->routeIs('quiz') ? 'text-[#00BFA6] font-bold' : 'hover:text-[#00BFA6]' }} flex items-center gap-1.5 transition-colors">
            <span>Cek Risiko</span>
            <span class="px-1.5 py-0.5 bg-[#EAF7FF] text-[#00BFA6] text-[10px] font-extrabold rounded-md uppercase border border-[#00BFA6]/20">AI</span>
          </a>
          <a href="{{ route('articles') }}" class="{{ request()->routeIs('articles*') ? 'text-[#00BFA6] font-bold' : 'hover:text-[#00BFA6]' }} transition-colors">Berita &amp; Artikel</a>

          @auth
            <span class="text-gray-300">|</span>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-[#168FE5] bg-[#EAF7FF] hover:bg-[#d9effe] border border-[#43B4F2]/30 px-3 py-1 rounded-full flex items-center gap-1 transition-all">
              <span>Ke Panel Admin</span>
              <span>&rarr;</span>
            </a>
          @endauth
        @endif
      </nav>

      <!-- Right: Menu Masuk / Profil Admin + Mobile Hamburger Toggle -->
      <div class="flex items-center space-x-2 sm:space-x-3">
        @auth
          <!-- User Profile Dropdown / Logout -->
          <div class="relative group">
            <button class="flex items-center space-x-2 px-3 sm:px-4 py-2 bg-[#EAF7FF] hover:bg-[#d9effe] border border-[#43B4F2]/30 text-[#123B5D] font-bold text-xs sm:text-sm rounded-full transition-all cursor-pointer">
              <span class="w-2 h-2 rounded-full bg-[#00BFA6]"></span>
              <span class="max-w-[100px] sm:max-w-none truncate">{{ auth()->user()->name }}</span>
              <svg class="w-3.5 h-3.5 text-[#5C7893]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </button>
            <div class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 hidden group-hover:block z-50">
              <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">
                Masuk sebagai <strong class="text-gray-900 block truncate">{{ auth()->user()->email }}</strong>
                <span class="inline-block mt-1 px-1.5 py-0.5 bg-[#EAF7FF] text-[#168FE5] text-[9px] font-extrabold rounded uppercase">Admin</span>
              </div>
              <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-[#EAF7FF] hover:text-[#168FE5] font-semibold">
                📊 Dashboard Admin
              </a>
              <a href="{{ route('admin.articles.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-[#EAF7FF] hover:text-[#168FE5] font-semibold">
                📰 Kelola Berita / Artikel
              </a>
              <a href="{{ route('admin.content.index') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-[#EAF7FF] hover:text-[#168FE5] font-semibold">
                ✏️ Kelola Konten Beranda
              </a>
              <a href="{{ route('history') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-[#EAF7FF] hover:text-[#168FE5] font-semibold">
                📋 Riwayat Pemeriksaan
              </a>
              <div class="border-t border-gray-100 my-1"></div>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-semibold cursor-pointer">
                  🚪 Keluar (Logout)
                </button>
              </form>
            </div>
          </div>
        @else
          <a href="{{ route('login') }}" class="px-3.5 sm:px-6 py-2 sm:py-2.5 bg-[#168FE5] hover:bg-[#127ac4] text-white font-bold text-xs sm:text-sm rounded-full shadow-sm transition-all hover:scale-[1.02] flex items-center space-x-1.5 sm:space-x-2">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            <span>Masuk Admin</span>
          </a>
        @endauth

        <!-- Hamburger Menu Button (Mobile & Tablet: md:hidden) -->
        <button 
          type="button" 
          id="hamburgerBtn" 
          aria-label="Menu Utama" 
          aria-expanded="false" 
          aria-controls="mobileSidebar"
          class="md:hidden flex items-center justify-center w-11 h-11 rounded-xl text-gray-700 hover:text-kd-green hover:bg-emerald-50 border border-gray-200/80 transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-kd-green/30"
        >
          <!-- Ikon Hamburger ☰ -->
          <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" id="hamburgerIcon">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>

    </div>
  </header>

  <!-- ============================================================== -->
  <!-- MOBILE SIDEBAR NAVIGATION & BACKDROP                           -->
  <!-- ============================================================== -->
  <!-- Backdrop Overlay -->
  <div 
    id="mobileSidebarBackdrop" 
    class="fixed inset-0 bg-gray-900/50 backdrop-blur-xs z-[90] opacity-0 pointer-events-none transition-opacity duration-300 ease-in-out"
    aria-hidden="true"
  ></div>

  <!-- Slide-in Sidebar Panel -->
  <aside 
    id="mobileSidebar" 
    aria-label="Navigasi Menu Mobile"
    class="fixed top-0 left-0 bottom-0 w-80 max-w-[85vw] bg-white z-[100] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out flex flex-col justify-between overflow-y-auto"
  >
    <!-- Top Part: Brand & Navigation Links -->
    <div>
      <div class="h-20 px-5 border-b border-gray-100 flex items-center justify-between">
        <!-- Brand Logo -->
        <a href="{{ route('home') }}" class="mobile-nav-link flex items-center space-x-2">
          <x-site-logo size="sm" />
        </a>

        <!-- Close Button (×) -->
        <button 
          type="button" 
          id="closeSidebarBtn" 
          aria-label="Tutup Menu" 
          class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 border border-transparent hover:border-gray-200 transition-all cursor-pointer focus:outline-none"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Navigation Links -->
      <nav class="px-4 py-5 space-y-1.5 text-sm font-semibold text-gray-700">
        @if(request()->is('admin*') || request()->routeIs('history'))
          <div class="px-3 pb-2 text-[11px] font-extrabold text-emerald-700 uppercase tracking-wider flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Menu Panel Admin</span>
          </div>
          <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-kd-green font-bold' : 'hover:bg-gray-50 hover:text-kd-green' }}">
            <span>📊</span>
            <span>Dashboard</span>
            <span class="ml-auto px-1.5 py-0.5 bg-emerald-100 text-emerald-800 text-[9px] font-extrabold rounded uppercase">Admin</span>
          </a>
          <a href="{{ route('admin.articles.index') }}" class="mobile-nav-link flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.articles.*') ? 'bg-emerald-50 text-kd-green font-bold' : 'hover:bg-gray-50 hover:text-kd-green' }}">
            <span>📰</span>
            <span>Kelola Berita</span>
          </a>
          <a href="{{ route('admin.content.index') }}" class="mobile-nav-link flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('admin.content.*') ? 'bg-emerald-50 text-kd-green font-bold' : 'hover:bg-gray-50 hover:text-kd-green' }}">
            <span>✏️</span>
            <span>Kelola Tampilan</span>
          </a>
          <a href="{{ route('history') }}" class="mobile-nav-link flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('history') ? 'bg-emerald-50 text-kd-green font-bold' : 'hover:bg-gray-50 hover:text-kd-green' }}">
            <span>📋</span>
            <span>Riwayat Skrining</span>
          </a>
          <div class="border-t border-gray-100 my-2"></div>
          <a href="{{ route('home') }}" class="mobile-nav-link flex items-center gap-2.5 px-3.5 py-2.5 text-xs text-gray-500 hover:text-kd-green hover:bg-emerald-50/50 rounded-xl transition-colors">
            <span>🌐</span>
            <span>Lihat Website Publik &rarr;</span>
          </a>
        @else
          <!-- Menu Publik -->
          <a href="{{ route('home') }}" class="mobile-nav-link flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('home') ? 'bg-emerald-50 text-kd-green font-bold' : 'hover:bg-gray-50 hover:text-kd-green' }}">
            <span>🏠</span>
            <span>Beranda</span>
          </a>

          <!-- Mobile Dropdown Accordion: Tentang Kami (+) / (-) -->
          <div class="pt-1">
            <button 
              type="button" 
              id="mobileTentangKamiBtn" 
              aria-expanded="false" 
              class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-colors hover:bg-gray-50 hover:text-kd-green text-gray-700 cursor-pointer focus:outline-none"
            >
              <div class="flex items-center gap-2.5">
                <span>ℹ️</span>
                <span>Tentang Kami</span>
              </div>
              <span id="mobileTentangKamiIcon" class="w-5 h-5 rounded-full bg-emerald-50 text-kd-green border border-emerald-200 flex items-center justify-center text-xs font-bold leading-none select-none transition-transform duration-200">+</span>
            </button>
            
            <!-- Mobile Accordion Menu -->
            <div id="mobileTentangKamiMenu" class="hidden pl-6 pr-2 pt-1 pb-1 space-y-1">
              <a href="{{ route('about') }}#tentang-kami" class="mobile-nav-link block py-2 px-3 text-xs font-medium text-gray-600 hover:text-kd-green hover:bg-emerald-50/60 rounded-lg transition-colors">
                • Tentang Kami
              </a>
              <a href="{{ route('about') }}#analis" class="mobile-nav-link block py-2 px-3 text-xs font-medium text-gray-600 hover:text-kd-green hover:bg-emerald-50/60 rounded-lg transition-colors">
                • Orang yang Analisis
              </a>
              <a href="{{ route('about') }}#biodata" class="mobile-nav-link block py-2 px-3 text-xs font-medium text-gray-600 hover:text-kd-green hover:bg-emerald-50/60 rounded-lg transition-colors">
                • Biodata
              </a>
            </div>
          </div>
          
          <a href="{{ route('quiz') }}" class="mobile-nav-link flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('quiz') ? 'bg-emerald-50 text-kd-green font-bold' : 'hover:bg-gray-50 hover:text-kd-green' }}">
            <div class="flex items-center gap-2.5">
              <span>🩺</span>
              <span>Cek Risiko Mandiri</span>
            </div>
            <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold rounded-md uppercase">AI</span>
          </a>

          <a href="{{ route('articles') }}" class="mobile-nav-link flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl transition-colors {{ request()->routeIs('articles*') ? 'bg-emerald-50 text-kd-green font-bold' : 'hover:bg-gray-50 hover:text-kd-green' }}">
            <span>📚</span>
            <span>Berita &amp; Artikel</span>
          </a>

          @auth
            <div class="border-t border-gray-100 my-2"></div>
            <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link flex items-center justify-between px-3.5 py-2.5 text-xs font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition-all">
              <span>Ke Dashboard Admin</span>
              <span>&rarr;</span>
            </a>
          @endauth
        @endif
      </nav>
    </div>

    <!-- Sidebar Bottom Footer Area -->
    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
      @auth
        <div class="flex items-center justify-between mb-3 px-1">
          <div class="truncate text-xs">
            <span class="text-gray-400 block text-[10px]">Login sebagai:</span>
            <span class="font-bold text-gray-800 block truncate">{{ auth()->user()->name }}</span>
          </div>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-xs text-rose-600 hover:text-rose-700 font-bold px-2 py-1 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer">
              Keluar
            </button>
          </form>
        </div>
      @else
        <a href="{{ route('login') }}" class="w-full mb-3 py-2.5 px-4 bg-kd-green hover:bg-kd-greenDark text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center space-x-2">
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
          </svg>
          <span>Masuk Admin</span>
        </a>
      @endauth

      <div class="text-[11px] text-gray-400 text-center leading-tight">
        <span>&copy; {{ date('Y') }} {{ \App\Models\SiteContent::get('site_name', 'Diabetes Uji Coba') }}</span>
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-grow">
    @yield('content')
  </main>

  <!-- ============================================================== -->
  <!-- FOOTER                                                         -->
  <!-- ============================================================== -->
  <footer class="bg-gray-900 text-gray-400 py-12 text-sm mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8 border-b border-gray-800">
        
        <!-- Col 1: Brand & Description -->
        <div class="space-y-3">
          <div class="flex items-center space-x-2.5">
            <x-site-logo size="h-8 w-auto max-h-8" />
            <span class="text-xl font-extrabold text-white">
              {{ \App\Models\SiteContent::get('site_name', 'Diabetes Uji Coba') }}
            </span>
          </div>
          <p class="text-xs text-gray-400 leading-relaxed">
            {{ \App\Models\SiteContent::get('footer_desc', 'Platform edukasi dan skrining dini diabetes mandiri berbasis algoritma Machine Learning XGBoost.') }}
          </p>
        </div>

        <!-- Col 2: Program & Layanan -->
        <div>
          <h4 class="text-white font-bold text-sm mb-3">Menu Utama</h4>
          <ul class="space-y-2 text-xs">
            @if(request()->is('admin*') || request()->routeIs('history'))
              <li><a href="{{ route('admin.dashboard') }}" class="hover:text-kd-green transition-colors">📊 Dashboard Admin</a></li>
              <li><a href="{{ route('admin.articles.index') }}" class="hover:text-kd-green transition-colors">📰 Kelola Berita</a></li>
              <li><a href="{{ route('admin.content.index') }}" class="hover:text-kd-green transition-colors">✏️ Kelola Tampilan</a></li>
              <li><a href="{{ route('history') }}" class="hover:text-kd-green transition-colors">📋 Riwayat Skrining</a></li>
            @else
              <li><a href="{{ route('home') }}" class="hover:text-kd-green transition-colors">Beranda</a></li>
              <li><a href="{{ route('about') }}" class="hover:text-kd-green transition-colors">Tentang Website</a></li>
              <li><a href="{{ route('quiz') }}" class="hover:text-kd-green transition-colors">Kuesioner Cek Risiko</a></li>
              <li><a href="{{ route('articles') }}" class="hover:text-kd-green transition-colors">Berita &amp; Artikel Edukasi</a></li>
            @endif
          </ul>
        </div>

        <!-- Col 3: Layanan Bantuan & Kontak -->
        <div>
          <h4 class="text-white font-bold text-sm mb-3">Layanan &amp; Bantuan</h4>
          <p class="text-xs text-gray-400 mb-2">Pusat informasi dan bantuan layanan pengguna:</p>
          <ul class="space-y-2 text-xs text-gray-300">
            <li class="flex items-center space-x-2">
              <span>✉️</span>
              <span>{{ \App\Models\SiteContent::get('contact_email', 'bantuan@diabetesujicoba.com') }}</span>
            </li>
            <li class="flex items-center space-x-2">
              <span>📱</span>
              <span>WhatsApp: {{ \App\Models\SiteContent::get('contact_phone', '0812-3456-7890') }}</span>
            </li>
            <li class="flex items-center space-x-2 text-emerald-400">
              <span>⏱️</span>
              <span>Layanan Mandiri AI Aktif 24 Jam</span>
            </li>
          </ul>
        </div>

      </div>

      <div class="pt-6 text-center text-xs text-gray-500">
        <p>&copy; {{ date('Y') }} {{ \App\Models\SiteContent::get('footer_copyright', 'Diabetes Uji Coba • Sistem Skrining Dini Diabetes Berbasis Machine Learning XGBoost.') }}</p>
      </div>

    </div>
  </footer>

  <!-- Floating HILDA AI Chat Widget (Hanya tampil di halaman publik, tidak mengganggu panel admin) -->
  @unless(request()->is('admin*') || request()->routeIs('history'))
    @include('components.hilda-chat')
  @endunless

  <!-- Navigation Interactive Controller (Vanilla JS, Zero Dependency) -->
  <script>
    (function () {
      function initNavigation() {
        // Elements
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileSidebarBackdrop = document.getElementById('mobileSidebarBackdrop');
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

        // Desktop Dropdown Elements
        const navDropdownBtn = document.getElementById('navDropdownBtn');
        const navDropdownMenu = document.getElementById('navDropdownMenu');
        const navDropdownIcon = document.getElementById('navDropdownIcon');
        const navDropdownContainer = document.getElementById('navDropdownContainer');
        const desktopSubLinks = document.querySelectorAll('.desktop-sub-link');

        // Mobile Dropdown Accordion Elements
        const mobileFiturKamiBtn = document.getElementById('mobileFiturKamiBtn') || document.getElementById('mobileTentangKamiBtn');
        const mobileFiturKamiMenu = document.getElementById('mobileFiturKamiMenu') || document.getElementById('mobileTentangKamiMenu');
        const mobileFiturKamiIcon = document.getElementById('mobileFiturKamiIcon') || document.getElementById('mobileTentangKamiIcon');

        // Open Mobile Sidebar
        function openMobileSidebar() {
          if (!mobileSidebar || !mobileSidebarBackdrop) return;
          mobileSidebar.classList.remove('-translate-x-full');
          mobileSidebar.classList.add('translate-x-0');
          mobileSidebarBackdrop.classList.remove('opacity-0', 'pointer-events-none');
          mobileSidebarBackdrop.classList.add('opacity-100', 'pointer-events-auto');
          document.body.classList.add('overflow-hidden');
          if (hamburgerBtn) hamburgerBtn.setAttribute('aria-expanded', 'true');
        }

        // Close Mobile Sidebar
        function closeMobileSidebar() {
          if (!mobileSidebar || !mobileSidebarBackdrop) return;
          mobileSidebar.classList.remove('translate-x-0');
          mobileSidebar.classList.add('-translate-x-full');
          mobileSidebarBackdrop.classList.remove('opacity-100', 'pointer-events-auto');
          mobileSidebarBackdrop.classList.add('opacity-0', 'pointer-events-none');
          document.body.classList.remove('overflow-hidden');
          if (hamburgerBtn) hamburgerBtn.setAttribute('aria-expanded', 'false');
        }

        // Toggle Mobile Sidebar on Hamburger Click
        if (hamburgerBtn) {
          hamburgerBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = mobileSidebar && mobileSidebar.classList.contains('translate-x-0');
            if (isOpen) {
              closeMobileSidebar();
            } else {
              openMobileSidebar();
            }
          });
        }

        // Close Button Click
        if (closeSidebarBtn) {
          closeSidebarBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            closeMobileSidebar();
          });
        }

        // Backdrop Overlay Click
        if (mobileSidebarBackdrop) {
          mobileSidebarBackdrop.addEventListener('click', function () {
            closeMobileSidebar();
          });
        }

        // Close Sidebar on Clicking Navigation Links
        if (mobileNavLinks) {
          mobileNavLinks.forEach(function (link) {
            link.addEventListener('click', function () {
              closeMobileSidebar();
            });
          });
        }

        // Desktop Dropdown Open / Close
        function setDesktopDropdown(open) {
          if (!navDropdownMenu || !navDropdownIcon || !navDropdownBtn) return;
          if (open) {
            navDropdownMenu.classList.remove('opacity-0', 'invisible', 'translate-y-2', 'pointer-events-none');
            navDropdownMenu.classList.add('opacity-100', 'visible', 'translate-y-0', 'pointer-events-auto');
            navDropdownIcon.textContent = '−';
            navDropdownBtn.setAttribute('aria-expanded', 'true');
          } else {
            navDropdownMenu.classList.add('opacity-0', 'invisible', 'translate-y-2', 'pointer-events-none');
            navDropdownMenu.classList.remove('opacity-100', 'visible', 'translate-y-0', 'pointer-events-auto');
            navDropdownIcon.textContent = '+';
            navDropdownBtn.setAttribute('aria-expanded', 'false');
          }
        }

        if (navDropdownBtn) {
          navDropdownBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isClosed = navDropdownMenu && navDropdownMenu.classList.contains('invisible');
            setDesktopDropdown(isClosed);
          });
        }

        // Close Desktop Dropdown when clicking outside
        document.addEventListener('click', function (e) {
          if (navDropdownContainer && !navDropdownContainer.contains(e.target)) {
            setDesktopDropdown(false);
          }
        });

        // Close Desktop Dropdown when clicking any submenu link
        if (desktopSubLinks) {
          desktopSubLinks.forEach(function (link) {
            link.addEventListener('click', function () {
              setDesktopDropdown(false);
            });
          });
        }

        // Mobile Dropdown Accordion Toggle
        if (mobileFiturKamiBtn && mobileFiturKamiMenu && mobileFiturKamiIcon) {
          mobileFiturKamiBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isHidden = mobileFiturKamiMenu.classList.contains('hidden');
            if (isHidden) {
              mobileFiturKamiMenu.classList.remove('hidden');
              mobileFiturKamiIcon.textContent = '−';
              mobileFiturKamiBtn.setAttribute('aria-expanded', 'true');
            } else {
              mobileFiturKamiMenu.classList.add('hidden');
              mobileFiturKamiIcon.textContent = '+';
              mobileFiturKamiBtn.setAttribute('aria-expanded', 'false');
            }
          });
        }

        // Keyboard Navigation: Escape key closes menus
        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape') {
            closeMobileSidebar();
            setDesktopDropdown(false);
          }
        });

        // Viewport resize handling
        window.addEventListener('resize', function () {
          if (window.innerWidth >= 768) {
            closeMobileSidebar();
          }
        });
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavigation);
      } else {
        initNavigation();
      }
    })();
  </script>

  @stack('scripts')
</body>
</html>

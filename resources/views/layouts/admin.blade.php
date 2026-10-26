<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin Dashboard | ' . \App\Models\SiteContent::get('site_name', 'Diabetes Uji Coba'))</title>
  
  <!-- Inter & Plus Jakarta Sans Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
          },
          colors: {
            'adm-navy': '#123B5D',
            'adm-cyan': '#168FE5',
            'adm-teal': '#00BFA6',
            'adm-teal-dark': '#00a870',
            'adm-dark': '#072b36',
          }
        }
      }
    }
  </script>

  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    /* Custom Scrollbar for Admin */
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #f1f5f9;
    }
    ::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 9999px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #94a3b8;
    }
  </style>

  @stack('styles')
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased flex flex-col md:flex-row min-h-screen">

  <!-- ============================================================== -->
  <!-- 1. MOBILE BACKDROP (DRAWER OVERLAY UNTUK ANDROID / SMARTPHONE) -->
  <!-- ============================================================== -->
  <div 
    id="adminMobileBackdrop" 
    class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300 hidden opacity-0 md:hidden"
    onclick="toggleAdminSidebar()"
  ></div>

  <!-- ============================================================== -->
  <!-- 2. SIDEBAR ADMIN (RESPONSIF DESKTOP & ANDROID DRAWER)          -->
  <!-- ============================================================== -->
  <aside 
    id="adminSidebar" 
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200/80 shadow-xl md:shadow-none flex flex-col justify-between transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out md:static md:z-auto shrink-0"
  >
    <!-- Top: Logo & Main Navigation -->
    <div class="flex-1 overflow-y-auto py-5 px-4">
      
      <!-- Logo Header Area -->
      <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 group">
          <x-site-logo size="sm" />
        </a>
        <button 
          type="button" 
          onclick="toggleAdminSidebar()" 
          class="md:hidden w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100"
          title="Tutup Menu"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Administrator Profile Badge -->
      <div class="p-3 bg-gradient-to-r from-slate-50 to-emerald-50/50 rounded-2xl border border-slate-100 flex items-center space-x-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#123B5D] to-[#00BFA6] flex items-center justify-center text-white font-bold text-sm shadow-xs shrink-0">
          {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        </div>
        <div class="overflow-hidden">
          <h4 class="text-xs font-black text-[#123B5D] truncate">{{ auth()->user()->name ?? 'Administrator' }}</h4>
          <span class="inline-flex items-center space-x-1 text-[10px] font-bold text-emerald-700">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Admin Berwenang</span>
          </span>
        </div>
      </div>

      <!-- Navigation Section Label -->
      <div class="px-3 mb-2">
        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Navigasi Utama</span>
      </div>

      <!-- Navigation Menu Links -->
      <nav class="space-y-1.5 text-xs font-bold">
        
        <!-- 1. Dashboard -->
        <a 
          href="{{ route('admin.dashboard') }}" 
          class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#123B5D] text-white shadow-md shadow-[#123B5D]/15' : 'text-slate-600 hover:bg-slate-100/80 hover:text-[#123B5D]' }}"
        >
          <span class="text-base">📊</span>
          <span class="flex-1">Dashboard</span>
          @if(request()->routeIs('admin.dashboard'))
            <span class="w-1.5 h-1.5 rounded-full bg-[#00BFA6]"></span>
          @endif
        </a>

        <!-- 2. Pengaturan Logo Website -->
        <a 
          href="{{ route('admin.logo.index') }}" 
          class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.logo.*') ? 'bg-[#123B5D] text-white shadow-md shadow-[#123B5D]/15' : 'text-slate-600 hover:bg-slate-100/80 hover:text-[#123B5D]' }}"
        >
          <span class="text-base">🏷️</span>
          <span class="flex-1">Pengaturan Logo</span>
          @if(request()->routeIs('admin.logo.*'))
            <span class="w-1.5 h-1.5 rounded-full bg-[#00BFA6]"></span>
          @endif
        </a>

        <!-- 3. Pengaturan Hero & Tampilan (CMS) -->
        <a 
          href="{{ route('admin.content.index') }}" 
          class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.content.*') ? 'bg-[#123B5D] text-white shadow-md shadow-[#123B5D]/15' : 'text-slate-600 hover:bg-slate-100/80 hover:text-[#123B5D]' }}"
        >
          <span class="text-base">🌟</span>
          <span class="flex-1">Pengaturan Hero &amp; CMS</span>
          @if(request()->routeIs('admin.content.*'))
            <span class="w-1.5 h-1.5 rounded-full bg-[#00BFA6]"></span>
          @endif
        </a>

        <!-- 4. Kelola Berita / Artikel -->
        <a 
          href="{{ route('admin.articles.index') }}" 
          class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.articles.*') ? 'bg-[#123B5D] text-white shadow-md shadow-[#123B5D]/15' : 'text-slate-600 hover:bg-slate-100/80 hover:text-[#123B5D]' }}"
        >
          <span class="text-base">📰</span>
          <span class="flex-1">Kelola Berita</span>
          @if(request()->routeIs('admin.articles.*'))
            <span class="w-1.5 h-1.5 rounded-full bg-[#00BFA6]"></span>
          @endif
        </a>

        <!-- 5. Riwayat Skrining AI -->
        <a 
          href="{{ route('history') }}" 
          class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('history') ? 'bg-[#123B5D] text-white shadow-md shadow-[#123B5D]/15' : 'text-slate-600 hover:bg-slate-100/80 hover:text-[#123B5D]' }}"
        >
          <span class="text-base">📋</span>
          <span class="flex-1">Riwayat Skrining</span>
          @if(request()->routeIs('history'))
            <span class="w-1.5 h-1.5 rounded-full bg-[#00BFA6]"></span>
          @endif
        </a>

      </nav>

      <!-- Section: Tautan Luar / Beranda -->
      <div class="px-3 mt-6 mb-2">
        <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Pratinjau Publik</span>
      </div>

      <div class="space-y-1.5 text-xs font-semibold">
        <a 
          href="{{ route('home') }}" 
          target="_blank" 
          class="flex items-center space-x-3 px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-[#168FE5] transition-all group"
        >
          <span>🌐</span>
          <span class="flex-1">Buka Beranda</span>
          <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform text-[11px]">&rarr;</span>
        </a>
        <a 
          href="{{ route('quiz') }}" 
          target="_blank" 
          class="flex items-center space-x-3 px-3.5 py-2 rounded-xl text-slate-600 hover:bg-slate-100 hover:text-[#00BFA6] transition-all group"
        >
          <span>🩺</span>
          <span class="flex-1">Buka Kuesioner</span>
          <span class="text-slate-400 group-hover:translate-x-0.5 transition-transform text-[11px]">&rarr;</span>
        </a>
      </div>

    </div>

    <!-- Bottom: Logout Action -->
    <div class="p-4 border-t border-slate-100 bg-slate-50/60">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button 
          type="submit" 
          class="w-full py-2.5 px-3 bg-white hover:bg-rose-50 text-rose-600 font-bold text-xs rounded-xl border border-rose-200 shadow-2xs hover:border-rose-300 transition-all flex items-center justify-center space-x-2 cursor-pointer"
        >
          <span>🚪</span>
          <span>Keluar Administrator</span>
        </button>
      </form>
    </div>
  </aside>

  <!-- ============================================================== -->
  <!-- 3. MAIN CONTENT CONTAINER (NAVBAR & CONTENT BODY)              -->
  <!-- ============================================================== -->
  <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
    
    <!-- Top Header Navbar -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4 shadow-2xs">
      
      <!-- Left: Mobile Toggle & Page Title Indicator -->
      <div class="flex items-center space-x-3">
        <button 
          type="button" 
          onclick="toggleAdminSidebar()" 
          class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 border border-slate-200"
          aria-label="Buka Menu Sidebar"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500">
          <span class="hidden sm:inline">Panel Admin</span>
          <span class="hidden sm:inline">/</span>
          <span class="font-extrabold text-[#123B5D]">@yield('header_title', 'Dashboard')</span>
        </div>
      </div>

      <!-- Right: Direct Quick Actions -->
      <div class="flex items-center space-x-2.5">
        <a 
          href="{{ route('home') }}" 
          target="_blank" 
          class="hidden sm:inline-flex items-center space-x-1.5 px-3.5 py-1.5 bg-slate-100 hover:bg-[#123B5D] text-slate-700 hover:text-white rounded-xl text-xs font-bold transition-all border border-slate-200"
          title="Pratinjau Hasil Perubahan di Beranda"
        >
          <span>👁️ Pratinjau Beranda</span>
          <span class="text-xs">&rarr;</span>
        </a>

        <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>

        <div class="flex items-center space-x-2 text-xs">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          <span class="font-bold text-slate-700 hidden sm:inline">{{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
      </div>
    </header>

    <!-- Content Body -->
    <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-slate-50">
      
      <!-- Global Flash Notifications -->
      @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs sm:text-sm text-emerald-900 font-semibold flex items-center justify-between shadow-xs">
          <div class="flex items-center space-x-2.5">
            <span class="text-lg">✅</span>
            <span>{{ session('success') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold p-1">&times;</button>
        </div>
      @endif

      @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs sm:text-sm text-rose-900 font-semibold flex items-center justify-between shadow-xs">
          <div class="flex items-center space-x-2.5">
            <span class="text-lg">⚠️</span>
            <span>{{ session('error') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold p-1">&times;</button>
        </div>
      @endif

      @if(isset($errors) && $errors->any())
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs sm:text-sm text-rose-900 font-semibold space-y-1 shadow-xs">
          <div class="font-bold mb-1 flex items-center space-x-1.5">
            <span>⚠️</span>
            <span>Terdapat beberapa kesalahan validasi:</span>
          </div>
          <ul class="list-disc list-inside space-y-1 text-xs text-rose-800">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')
    </main>

    <!-- Admin Footer -->
    <footer class="py-4 px-6 border-t border-slate-200/80 bg-white text-center text-xs text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2">
      <div>
        &copy; {{ date('Y') }} <strong class="text-[#123B5D]">{{ \App\Models\SiteContent::get('site_name', 'Diabetes Uji Coba') }}</strong> &bull; Panel Kendali Administrator
      </div>
      <div class="text-[11px] text-slate-400">
        Didukung Algoritma Machine Learning XGBoost &bull; Deteksi Dini Klinis
      </div>
    </footer>
  </div>

  <!-- Mobile Drawer Toggle Script -->
  <script>
    function toggleAdminSidebar() {
      const sidebar = document.getElementById('adminSidebar');
      const backdrop = document.getElementById('adminMobileBackdrop');
      if (!sidebar || !backdrop) return;

      const isHidden = sidebar.classList.contains('-translate-x-full');
      if (isHidden) {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
        setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
      } else {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('opacity-0');
        setTimeout(() => backdrop.classList.add('hidden'), 300);
      }
    }
  </script>

  @stack('scripts')
</body>
</html>

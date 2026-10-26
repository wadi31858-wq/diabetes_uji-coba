@extends('layouts.admin')

@section('title', 'Dashboard Administrator | Diabetes Uji Coba')
@section('header_title', 'Dashboard Utama')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">

  <!-- Top Breadcrumb & Welcome Banner -->
  <div class="bg-gradient-to-r from-[#123B5D] via-[#0f4469] to-[#0a4e5c] rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
    <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-[#00BFA6]/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
      <div>
        <div class="inline-flex items-center space-x-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-bold text-cyan-200 mb-3 border border-white/15">
          <span class="w-2 h-2 rounded-full bg-[#00BFA6] animate-pulse"></span>
          <span>Panel Kendali Administrator</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
          Selamat Datang, {{ auth()->user()->name ?? 'Administrator' }}!
        </h1>
        <p class="text-xs sm:text-sm text-cyan-100/80 mt-1 max-w-2xl leading-relaxed">
          Kelola seluruh konten yang tampil di website Diabetes Uji Coba: perbarui logo resmi, sesuaikan banner hero beranda, tambah artikel edukasi, serta pantau riwayat pemeriksaan risiko diabetes.
        </p>
      </div>

      <!-- Quick Action Buttons -->
      <div class="flex flex-wrap items-center gap-2.5">
        <a 
          href="{{ route('admin.logo.index') }}" 
          class="px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white font-bold text-xs sm:text-sm rounded-xl border border-white/20 transition-all flex items-center space-x-1.5 shadow-xs"
        >
          <span>🏷️ Atur Logo</span>
        </a>
        <a 
          href="{{ route('admin.content.index') }}" 
          class="px-4 py-2.5 bg-white/15 hover:bg-white/25 text-white font-bold text-xs sm:text-sm rounded-xl border border-white/20 transition-all flex items-center space-x-1.5 shadow-xs"
        >
          <span>🌟 Edit Hero Beranda</span>
        </a>
        <a 
          href="{{ route('admin.articles.create') }}" 
          class="px-4 py-2.5 bg-[#00BFA6] hover:bg-[#00a870] text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md shadow-[#00BFA6]/30 transition-all flex items-center space-x-1.5"
        >
          <span>+ Tambah Berita Baru</span>
        </a>
      </div>
    </div>
  </div>

  <!-- 4 Main Navigation & Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
    
    <!-- Card 1: Kelola Berita -->
    <a href="{{ route('admin.articles.index') }}" class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs hover:border-[#00BFA6] hover:shadow-md transition-all group block">
      <div class="flex items-center justify-between mb-3">
        <span class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#00BFA6] flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-2xs">
          📰
        </span>
        <span class="text-xs font-bold text-[#00BFA6] group-hover:underline">Buka &rarr;</span>
      </div>
      <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kelola Artikel</span>
      <h3 class="text-2xl font-black text-[#123B5D] mt-1">{{ $stats['total_articles'] ?? 0 }}</h3>
      <p class="text-[11px] text-slate-500 mt-1">Tambah, edit, atau hapus berita edukasi</p>
    </a>

    <!-- Card 2: Riwayat Pemeriksaan -->
    <a href="{{ route('history') }}" class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs hover:border-[#168FE5] hover:shadow-md transition-all group block">
      <div class="flex items-center justify-between mb-3">
        <span class="w-12 h-12 rounded-2xl bg-sky-50 text-[#168FE5] flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-2xs">
          📋
        </span>
        <span class="text-xs font-bold text-[#168FE5] group-hover:underline">Buka &rarr;</span>
      </div>
      <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Riwayat Skrining</span>
      <h3 class="text-2xl font-black text-[#123B5D] mt-1">{{ $stats['total_assessments'] ?? 0 }}</h3>
      <p class="text-[11px] text-slate-500 mt-1">Audit data &amp; ekspor laporan medis</p>
    </a>

    <!-- Card 3: Edit Hero & Tampilan -->
    <a href="{{ route('admin.content.index') }}" class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs hover:border-cyan-400 hover:shadow-md transition-all group block">
      <div class="flex items-center justify-between mb-3">
        <span class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-2xs">
          🌟
        </span>
        <span class="text-xs font-bold text-cyan-600 group-hover:underline">Buka &rarr;</span>
      </div>
      <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengaturan Hero &amp; CMS</span>
      <h3 class="text-2xl font-black text-[#123B5D] mt-1">CMS Aktif</h3>
      <p class="text-[11px] text-slate-500 mt-1">Ubah hero, pemicu, pilar, &amp; logo</p>
    </a>

    <!-- Card 4: Distribusi Risiko -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
      <div class="flex items-center justify-between mb-2">
        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sebaran Risiko Pasien</span>
        <span class="text-xs font-bold text-slate-400">AI XGBoost</span>
      </div>
      <div class="grid grid-cols-3 gap-2 text-center">
        <div class="p-2 bg-emerald-50 rounded-xl border border-emerald-100">
          <span class="text-[10px] font-extrabold text-emerald-800 block">Rendah</span>
          <span class="text-base font-black text-emerald-600">{{ $stats['risk_low'] ?? 0 }}</span>
        </div>
        <div class="p-2 bg-amber-50 rounded-xl border border-amber-100">
          <span class="text-[10px] font-extrabold text-amber-800 block">Sedang</span>
          <span class="text-base font-black text-amber-600">{{ $stats['risk_medium'] ?? 0 }}</span>
        </div>
        <div class="p-2 bg-rose-50 rounded-xl border border-rose-100">
          <span class="text-[10px] font-extrabold text-rose-800 block">Tinggi</span>
          <span class="text-base font-black text-rose-600">{{ $stats['risk_high'] ?? 0 }}</span>
        </div>
      </div>
      <span class="text-[10px] text-slate-400 block mt-2 text-center">Hasil kalkulasi model Machine Learning</span>
    </div>

  </div>

  <!-- 2 Columns: Artikel Terbaru & Riwayat Terakhir -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Column 1: Daftar Berita / Artikel -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs space-y-4">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div>
          <h2 class="font-extrabold text-[#123B5D] text-base">Berita &amp; Artikel Terkini</h2>
          <p class="text-xs text-slate-400">Artikel yang saat ini tayang di beranda dan halaman berita</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="text-xs font-bold text-[#00BFA6] hover:underline">
          Semua ({{ $stats['total_articles'] ?? 0 }}) &rarr;
        </a>
      </div>

      <div class="space-y-3">
        @forelse($recentArticles as $art)
          <div class="p-3 bg-slate-50/70 hover:bg-slate-100/70 rounded-2xl border border-slate-100 flex items-center justify-between gap-3 transition-colors">
            <div class="flex items-center space-x-3 overflow-hidden">
              <img src="{{ $art->image ?? 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=200&auto=format&fit=crop&q=80' }}" alt="{{ $art->title }}" class="w-12 h-12 rounded-xl object-cover shrink-0 shadow-2xs">
              <div class="truncate">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">{{ $art->category }}</span>
                <h4 class="text-xs font-bold text-[#123B5D] truncate mt-0.5">{{ $art->title }}</h4>
                <span class="text-[10px] text-slate-400">{{ $art->date ?? $art->created_at->format('d/m/Y') }}</span>
              </div>
            </div>
            <div class="flex items-center space-x-1.5 shrink-0">
              <a href="{{ route('admin.articles.edit', $art->id) }}" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-xs font-bold transition-colors" title="Edit">
                ✏️
              </a>
              <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg text-xs font-bold transition-colors cursor-pointer" title="Hapus">
                  🗑️
                </button>
              </form>
            </div>
          </div>
        @empty
          <div class="py-8 text-center text-xs text-slate-400">Belum ada artikel. Klik tombol Tambah Berita Baru.</div>
        @endforelse
      </div>
    </div>

    <!-- Column 2: Riwayat Pemeriksaan Terakhir -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-2xs space-y-4">
      <div class="flex items-center justify-between pb-3 border-b border-slate-100">
        <div>
          <h2 class="font-extrabold text-[#123B5D] text-base">Pemeriksaan Pasien Terakhir</h2>
          <p class="text-xs text-slate-400">Aktivitas cek risiko mandiri yang terekam sistem</p>
        </div>
        <a href="{{ route('history') }}" class="text-xs font-bold text-[#00BFA6] hover:underline">
          Semua ({{ $stats['total_assessments'] ?? 0 }}) &rarr;
        </a>
      </div>

      <div class="space-y-3">
        @forelse($recentAssessments as $asm)
          <div class="p-3 bg-slate-50/70 rounded-2xl border border-slate-100 flex items-center justify-between gap-3">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-xs text-white shadow-2xs" style="background-color: {{ $asm->color ?? '#00a870' }}">
                {{ $asm->score ?? '?' }}
              </div>
              <div>
                <div class="flex items-center space-x-2">
                  <span class="text-xs font-bold text-[#123B5D]">{{ $asm->gender }}, {{ $asm->age }} Thn</span>
                  <span class="px-2 py-0.5 rounded text-[10px] font-bold" style="background-color: {{ $asm->color }}20; color: {{ $asm->color }};">
                    {{ $asm->category }}
                  </span>
                </div>
                <span class="text-[10px] text-slate-400">BMI: {{ $asm->bmi }} &bull; {{ $asm->created_at->diffForHumans() }}</span>
              </div>
            </div>
            <div class="flex items-center space-x-1.5">
              <a href="{{ route('agent.export_report', $asm->id) }}" target="_blank" class="px-2.5 py-1.5 bg-white hover:bg-slate-100 text-slate-700 rounded-lg text-xs font-bold border border-slate-200 transition-colors shadow-2xs">
                📄 PDF
              </a>
            </div>
          </div>
        @empty
          <div class="py-8 text-center text-xs text-slate-400">Belum ada data pemeriksaan.</div>
        @endforelse
      </div>
    </div>

  </div>

</div>
@endsection

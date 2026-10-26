@extends('layouts.admin')

@section('title', 'Kelola Berita & Artikel | Panel Admin Diabetes Uji Coba')
@section('header_title', 'Kelola Berita')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    <!-- Header & Action Row -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
          <a href="{{ route('admin.dashboard') }}" class="hover:underline">Admin</a>
          <span>/</span>
          <span class="text-gray-900 font-semibold">Kelola Berita</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
          Kelola Berita &amp; Artikel
        </h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
          Tambah artikel baru, perbarui informasi, atau hapus konten yang ditampilkan kepada pengunjung.
        </p>
      </div>

      <div class="flex items-center space-x-2.5">
        <a 
          href="{{ route('admin.articles.create') }}" 
          class="px-5 py-3 bg-[#00a870] hover:bg-[#008f5d] text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-md transition-all flex items-center space-x-2 cursor-pointer"
        >
          <span class="text-base font-bold">+</span>
          <span>Tambah Berita Baru</span>
        </a>
      </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
      <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs sm:text-sm text-emerald-800 font-semibold flex items-center justify-between shadow-xs">
        <div class="flex items-center space-x-2">
          <span>✅</span>
          <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold">&times;</button>
      </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm">
      <form action="{{ route('admin.articles.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
        
        <div class="relative flex-1 w-full">
          <input 
            type="text" 
            name="q" 
            value="{{ request('q') }}" 
            placeholder="Cari berdasarkan judul, kategori, atau ringkasan..."
            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm font-medium outline-none focus:bg-white focus:border-[#00a870]"
          >
          <span class="absolute left-3.5 top-3 text-gray-400 text-xs pointer-events-none">🔍</span>
        </div>

        <select 
          name="category" 
          class="w-full sm:w-48 py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold outline-none focus:bg-white focus:border-[#00a870]"
        >
          <option value="">Semua Kategori</option>
          @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
          @endforeach
        </select>

        <button 
          type="submit" 
          class="w-full sm:w-auto px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold text-xs rounded-xl transition-colors cursor-pointer"
        >
          Filter
        </button>

        @if(request()->hasAny(['q', 'category']))
          <a 
            href="{{ route('admin.articles.index') }}" 
            class="px-4 py-2.5 text-xs font-bold text-gray-500 hover:text-gray-800 transition-colors"
          >
            Reset
          </a>
        @endif

      </form>
    </div>

    <!-- Table of Articles -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] font-extrabold uppercase tracking-wider text-gray-500">
              <th class="py-3.5 px-5">Gambar &amp; Judul Berita</th>
              <th class="py-3.5 px-4">Kategori</th>
              <th class="py-3.5 px-4">Tanggal Tayang</th>
              <th class="py-3.5 px-4">Penulis</th>
              <th class="py-3.5 px-4 text-center">Status</th>
              <th class="py-3.5 px-5 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-xs font-medium text-gray-700">
            @forelse($articles as $item)
              <tr class="hover:bg-gray-50/60 transition-colors">
                
                <!-- Gambar & Judul -->
                <td class="py-4 px-5">
                  <div class="flex items-center space-x-3.5 max-w-md">
                    <img 
                      src="{{ $item->image ?? 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=200&auto=format&fit=crop&q=80' }}" 
                      alt="{{ $item->title }}" 
                      class="w-14 h-14 rounded-2xl object-cover flex-shrink-0 shadow-xs border border-gray-100"
                    >
                    <div class="overflow-hidden">
                      <a href="{{ route('articles.detail', $item->slug) }}" target="_blank" class="font-bold text-gray-900 hover:text-[#00a870] transition-colors leading-snug line-clamp-2 block">
                        {{ $item->title }}
                      </a>
                      <span class="text-[11px] text-gray-400 mt-0.5 line-clamp-1">Slug: {{ $item->slug }}</span>
                    </div>
                  </div>
                </td>

                <!-- Kategori -->
                <td class="py-4 px-4 whitespace-nowrap">
                  <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800">
                    {{ $item->category }}
                  </span>
                </td>

                <!-- Tanggal -->
                <td class="py-4 px-4 whitespace-nowrap text-gray-500 text-[11px]">
                  {{ $item->date ?? $item->created_at->format('d/m/Y') }}
                </td>

                <!-- Penulis -->
                <td class="py-4 px-4 whitespace-nowrap text-gray-500 text-[11px]">
                  {{ $item->author ?? 'Tim Medis' }}
                </td>

                <!-- Status -->
                <td class="py-4 px-4 text-center whitespace-nowrap">
                  @if($item->is_published)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span> Tayang
                    </span>
                  @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200">
                      Draft
                    </span>
                  @endif
                </td>

                <!-- Aksi (Lihat, Edit, Hapus) -->
                <td class="py-4 px-5 text-right whitespace-nowrap">
                  <div class="inline-flex items-center space-x-1.5">
                    
                    <!-- Tombol Preview Publik -->
                    <a 
                      href="{{ route('articles.detail', $item->slug) }}" 
                      target="_blank" 
                      class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition-colors text-xs" 
                      title="Lihat di Website"
                    >
                      👁️
                    </a>

                    <!-- Tombol Edit -->
                    <a 
                      href="{{ route('admin.articles.edit', $item->id) }}" 
                      class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-xl transition-colors text-xs font-bold" 
                      title="Edit Artikel"
                    >
                      ✏️ Edit
                    </a>

                    <!-- Tombol Hapus -->
                    <form 
                      action="{{ route('admin.articles.destroy', $item->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.')"
                      class="inline"
                    >
                      @csrf
                      @method('DELETE')
                      <button 
                        type="submit" 
                        class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition-colors text-xs font-bold cursor-pointer" 
                        title="Hapus Artikel"
                      >
                        🗑️ Hapus
                      </button>
                    </form>

                  </div>
                </td>

              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-12 text-center text-gray-400 text-xs">
                  Tidak ada artikel yang cocok dengan pencarian Anda.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($articles->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
          {{ $articles->links() }}
        </div>
      @endif
    </div>

  </div>
@endsection

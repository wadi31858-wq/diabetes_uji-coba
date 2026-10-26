@extends('layouts.admin')

@section('title', 'Riwayat Pemeriksaan - Diabetes Uji Coba')
@section('header_title', 'Riwayat Pemeriksaan')

@section('content')
<div class="space-y-6">

  <!-- Header Section -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-xs">
    <div>
      <div class="inline-flex items-center space-x-2 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold mb-2 border border-emerald-100">
        <span>🛡️ Panel Khusus Administrator Terautentikasi</span>
      </div>
      <h1 class="text-xl sm:text-2xl font-black text-[#123B5D] tracking-tight">
        Riwayat Pemeriksaan Risiko Diabetes
      </h1>
      <p class="text-xs sm:text-sm text-slate-500 mt-1">
        Daftar seluruh asesmen risiko diabetes pengguna yang telah diproses oleh Laravel &amp; Model Machine Learning XGBoost.
      </p>
    </div>

    <div class="flex items-center space-x-3 self-start sm:self-auto">
      <a 
        href="{{ route('quiz') }}" 
        class="px-5 py-2.5 bg-[#00BFA6] hover:bg-[#00a870] text-white font-bold text-xs sm:text-sm rounded-xl shadow-sm transition-all flex items-center space-x-1.5"
      >
        <span>+ Uji Skrining Baru</span>
      </a>
    </div>
  </div>

    @if(session('success'))
      <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs sm:text-sm text-emerald-800 font-semibold flex items-center justify-between shadow-xs">
        <div class="flex items-center space-x-2">
          <span>✅</span>
          <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold">&times;</button>
      </div>
    @endif

    <!-- Quick Stats Cards for Admin -->
    @php
      $totalCount = \App\Models\Assessment::count();
      $rendahCount = \App\Models\Assessment::where('category', 'Rendah')->count();
      $sedangCount = \App\Models\Assessment::where('category', 'Sedang')->count();
      $tinggiCount = \App\Models\Assessment::where('category', 'Tinggi')->count();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
      <div class="p-4 bg-white rounded-2xl border border-gray-100 shadow-xs">
        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Pemeriksaan</span>
        <span class="text-2xl font-black text-gray-900 mt-1 block">{{ $totalCount }}</span>
      </div>
      <div class="p-4 bg-white rounded-2xl border border-emerald-100 shadow-xs">
        <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider block">Risiko Rendah</span>
        <span class="text-2xl font-black text-[#6ebd20] mt-1 block">{{ $rendahCount }}</span>
      </div>
      <div class="p-4 bg-white rounded-2xl border border-amber-100 shadow-xs">
        <span class="text-[11px] font-bold text-amber-600 uppercase tracking-wider block">Risiko Sedang</span>
        <span class="text-2xl font-black text-[#f37920] mt-1 block">{{ $sedangCount }}</span>
      </div>
      <div class="p-4 bg-white rounded-2xl border border-rose-100 shadow-xs">
        <span class="text-[11px] font-bold text-rose-600 uppercase tracking-wider block">Risiko Tinggi</span>
        <span class="text-2xl font-black text-[#ff4252] mt-1 block">{{ $tinggiCount }}</span>
      </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs">
      <form action="{{ route('history') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
        
        <!-- Search Input -->
        <div class="sm:col-span-5 relative">
          <input 
            type="text" 
            name="q" 
            value="{{ request('q') }}" 
            placeholder="Cari gender, usia, kategori, atau IP..."
            class="w-full pl-9 pr-3 py-2 text-xs font-medium border border-gray-200 rounded-xl focus:border-kd-green focus:ring-1 focus:ring-kd-green outline-none"
          >
          <span class="absolute left-3 top-2.5 text-gray-400 text-xs">🔍</span>
        </div>

        <!-- Filter Kategori -->
        <div class="sm:col-span-3">
          <select name="category" class="w-full py-2 px-3 text-xs font-semibold border border-gray-200 rounded-xl focus:border-kd-green outline-none bg-white">
            <option value="">Semua Kategori Risiko</option>
            <option value="Rendah" {{ request('category') === 'Rendah' ? 'selected' : '' }}>Risiko Rendah</option>
            <option value="Sedang" {{ request('category') === 'Sedang' ? 'selected' : '' }}>Risiko Sedang</option>
            <option value="Tinggi" {{ request('category') === 'Tinggi' ? 'selected' : '' }}>Risiko Tinggi</option>
          </select>
        </div>

        <!-- Filter Gender -->
        <div class="sm:col-span-2">
          <select name="gender" class="w-full py-2 px-3 text-xs font-semibold border border-gray-200 rounded-xl focus:border-kd-green outline-none bg-white">
            <option value="">Semua Gender</option>
            <option value="Laki-laki" {{ request('gender') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ request('gender') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
          </select>
        </div>

        <!-- Filter & Reset Buttons -->
        <div class="sm:col-span-2 flex items-center space-x-2">
          <button type="submit" class="flex-1 py-2 px-3 bg-kd-green hover:bg-kd-greenDark text-white text-xs font-bold rounded-xl transition-all cursor-pointer">
            Filter
          </button>
          @if(request()->hasAny(['q', 'category', 'gender']))
            <a href="{{ route('history') }}" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold rounded-xl transition-all">
              Reset
            </a>
          @endif
        </div>

      </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs sm:text-sm">
          <thead class="bg-gray-50/80 text-gray-500 uppercase text-[11px] font-bold border-b border-gray-100">
            <tr>
              <th class="py-3.5 px-4 sm:px-6">Waktu</th>
              <th class="py-3.5 px-4">Gender &amp; Usia</th>
              <th class="py-3.5 px-4">Indeks Tubuh</th>
              <th class="py-3.5 px-4">Faktor Klinis</th>
              <th class="py-3.5 px-4">Skor Klinis</th>
              <th class="py-3.5 px-4">Prediksi XGBoost</th>
              <th class="py-3.5 px-4 sm:px-6">Kategori Risiko</th>
              <th class="py-3.5 px-4 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-gray-700">
            @forelse($assessments as $item)
              <tr class="hover:bg-gray-50/60 transition-colors">
                <td class="py-3.5 px-4 sm:px-6 text-gray-500 whitespace-nowrap">
                  <div class="font-medium text-gray-900">{{ $item->created_at->format('d M Y') }}</div>
                  <div class="text-[11px] text-gray-400">{{ $item->created_at->format('H:i') }} WIB</div>
                </td>
                <td class="py-3.5 px-4 whitespace-nowrap">
                  <span class="font-semibold text-gray-900">{{ $item->gender }}</span>,
                  <span class="text-gray-600">{{ $item->age }} thn</span>
                </td>
                <td class="py-3.5 px-4 whitespace-nowrap">
                  <div class="font-bold text-gray-900">BMI: {{ $item->bmi }}</div>
                  <div class="text-[11px] text-gray-400">{{ $item->height }} cm / {{ $item->weight }} kg</div>
                </td>
                <td class="py-3.5 px-4">
                  <div class="flex flex-wrap gap-1 max-w-[200px]">
                    @if($item->high_bp)
                      <span class="px-1.5 py-0.2 bg-rose-50 text-rose-700 text-[10px] font-bold rounded">Hipertensi</span>
                    @endif
                    @if($item->high_chol)
                      <span class="px-1.5 py-0.2 bg-amber-50 text-amber-700 text-[10px] font-bold rounded">Kolesterol</span>
                    @endif
                    @if($item->family_history)
                      <span class="px-1.5 py-0.2 bg-purple-50 text-purple-700 text-[10px] font-bold rounded">Genetik</span>
                    @endif
                    @if($item->smoker)
                      <span class="px-1.5 py-0.2 bg-gray-100 text-gray-700 text-[10px] font-bold rounded">Merokok</span>
                    @endif
                    @if(!$item->phys_activity)
                      <span class="px-1.5 py-0.2 bg-orange-50 text-orange-700 text-[10px] font-bold rounded">Jarang Gerak</span>
                    @endif
                    @if(!$item->high_bp && !$item->high_chol && !$item->family_history && !$item->smoker)
                      <span class="text-[11px] text-gray-400">Parameter Normal</span>
                    @endif
                  </div>
                </td>
                <td class="py-3.5 px-4 font-mono font-bold text-gray-900">
                  {{ $item->score }} / 25
                </td>
                <td class="py-3.5 px-4 whitespace-nowrap">
                  @if($item->svm_prediction == 1)
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-200">Kelas 1 (Positif)</span>
                  @else
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Kelas 0 (Negatif)</span>
                  @endif
                </td>
                <td class="py-3.5 px-4 sm:px-6 whitespace-nowrap">
                  <span 
                    class="px-3 py-1 rounded-full text-xs font-bold text-white shadow-xs inline-block"
                    style="background-color: {{ $item->color }};"
                  >
                    {{ $item->category }}
                  </span>
                </td>
                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                  <div class="inline-flex items-center space-x-1.5">
                    <a 
                      href="{{ route('agent.export_report', $item->id) }}" 
                      target="_blank"
                      class="px-2.5 py-1 bg-gray-100 hover:bg-emerald-50 text-gray-700 hover:text-kd-green rounded-lg text-xs font-semibold border border-gray-200 transition-colors inline-flex items-center space-x-1"
                      title="Lihat Dokumen Laporan Medis"
                    >
                      <span>📄</span>
                      <span>PDF</span>
                    </a>

                    <form 
                      action="{{ route('admin.assessment.destroy', $item->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Hapus rekam pemeriksaan ID #{{ $item->id }} ini? Tindakan tidak dapat dibatalkan.')"
                      class="inline"
                    >
                      @csrf
                      @method('DELETE')
                      <button 
                        type="submit" 
                        class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-bold transition-colors cursor-pointer" 
                        title="Hapus Data Ini"
                      >
                        🗑️
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="py-12 text-center text-gray-400">
                  Tidak ada data riwayat yang cocok dengan kriteria pencarian.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if(method_exists($assessments, 'links'))
        <div class="p-4 border-t border-gray-100 bg-gray-50/40">
          {{ $assessments->links() }}
        </div>
      @endif
    </div>

</div>
@endsection

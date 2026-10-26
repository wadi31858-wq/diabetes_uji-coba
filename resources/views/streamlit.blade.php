@extends('layouts.app')

@section('title', 'Streamlit AI Hub | Diabetes Uji Coba')

@section('content')
<div class="py-10 bg-gray-50 min-h-[88vh]">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Header Section -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
      <div>
        <div class="flex items-center space-x-2.5 mb-2">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-ping"></span>
            Machine Learning XGBoost Engine
          </span>
          <span class="text-xs text-gray-400 font-semibold">Port: 8501</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
          Pusat Analitik & Dashboard Streamlit
        </h1>
        <p class="text-sm text-gray-600 mt-1 max-w-2xl">
          Aplikasi web Laravel ini terhubung langsung dengan mesin komputasi <strong>Python Streamlit</strong> untuk eksplorasi dataset CDC BRFSS, pelatihan ulang model, evaluasi metrik, dan pengujian 21 parameter klinis.
        </p>
      </div>

@php
  $streamlitUrl = env('STREAMLIT_URL', 'http://localhost:8501');
@endphp

      <div class="flex flex-wrap items-center gap-3">
        <a 
          href="{{ $streamlitUrl }}" 
          target="_blank" 
          class="px-5 py-3 bg-[#00a870] hover:bg-[#008f5d] text-white font-bold text-sm rounded-xl shadow-md transition-all flex items-center space-x-2"
        >
          <span>Buka di Tab Baru</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
          </svg>
        </a>

        <button 
          onclick="reloadIframe()" 
          class="px-4 py-3 border border-gray-200 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 transition-all"
        >
          🔄 Refresh
        </button>
      </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="text-xs font-bold uppercase text-gray-400">Total Skrining Laravel</div>
        <div class="text-2xl font-black text-gray-900 mt-1">{{ $totalAssessments ?? 0 }}</div>
        <p class="text-xs text-emerald-600 font-medium mt-1">Tersimpan di SQLite</p>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="text-xs font-bold uppercase text-gray-400">Model Aktif</div>
        <div class="text-xl font-bold text-gray-900 mt-1">XGBoost Classifier</div>
        <p class="text-xs text-gray-500 font-medium mt-1">Extreme Gradient Boosting</p>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="text-xs font-bold uppercase text-gray-400">Dataset Training</div>
        <div class="text-xl font-bold text-gray-900 mt-1">253.680 Sampel</div>
        <p class="text-xs text-gray-500 font-medium mt-1">CDC BRFSS Survey</p>
      </div>

      <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="text-xs font-bold uppercase text-gray-400">Status Server Streamlit</div>
        <div id="streamlitStatusText" class="text-xl font-bold text-emerald-600 mt-1">Aktif (Port 8501)</div>
        <p class="text-xs text-gray-500 font-medium mt-1">Background Task Running</p>
      </div>
    </div>

    <!-- Streamlit Embedded Frame -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-lg overflow-hidden">
      <div class="bg-gray-900 px-6 py-3 flex items-center justify-between text-white text-xs font-mono">
        <div class="flex items-center space-x-2">
          <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
          <span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span>
          <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>
          <span class="ml-3 text-gray-300">Streamlit Interactive Dashboard ({{ $streamlitUrl }})</span>
        </div>
        <span class="text-emerald-400">Connected</span>
      </div>

      <!-- Iframe -->
      <div class="relative w-full h-[750px] bg-gray-50">
        <iframe 
          id="streamlitFrame"
          src="{{ $streamlitUrl }}" 
          class="w-full h-full border-0"
          title="Streamlit Diabetes AI Analyzer"
          onerror="handleFrameError()"
        ></iframe>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
  function reloadIframe() {
    const iframe = document.getElementById('streamlitFrame');
    if (iframe) {
      iframe.src = iframe.src;
    }
  }

  // Cek ketersediaan server Streamlit
  const stEndpoint = "{{ $streamlitUrl }}";
  fetch(stEndpoint, { mode: 'no-cors' })
    .then(() => {
      document.getElementById('streamlitStatusText').innerText = 'Aktif (Connected)';
      document.getElementById('streamlitStatusText').className = 'text-xl font-bold text-emerald-600 mt-1';
    })
    .catch(() => {
      // Streamlit background service is starting or CORS blocked simple fetch
    });
</script>
@endpush

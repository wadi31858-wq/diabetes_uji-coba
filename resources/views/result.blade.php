@extends('layouts.app')

@section('title', 'Hasil Pemeriksaan Risiko - Diabetes Uji Coba')

@section('content')
<div class="py-8 sm:py-12 bg-white min-h-[85vh]">
  <div class="max-w-2xl mx-auto px-4 sm:px-6 space-y-6">

    <!-- Header: Resiko Diabetes Anda, Sedang -->
    <div class="text-center pt-2">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-[#222] tracking-tight">
        Resiko Diabetes Anda, <span style="color: {{ $assessment['color'] ?? '#f37920' }};">{{ $assessment['category'] ?? 'Sedang' }}</span>
      </h1>
    </div>

    <!-- Banner Image (Blood Sugar Levels Infographic) -->
    <div class="overflow-hidden rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 bg-white p-0 flex justify-center">
      <img src="{{ asset('assets/glucometer_banner.png') }}?v={{ file_exists(public_path('assets/glucometer_banner.png')) ? filemtime(public_path('assets/glucometer_banner.png')) : time() }}" alt="Blood Sugar Levels Chart" class="w-full h-auto block rounded-2xl sm:rounded-3xl object-contain">
    </div>

    @php
      $userCat = strtolower($assessment['category'] ?? 'sedang');
    @endphp

    <!-- Risk Category Tabs & Indicator Bar (Aligned with Illustration Above) -->
    <div class="max-w-md mx-auto pt-1 px-2">
      
      <!-- 3 Colored Tabs: Rendah, Sedang, Tinggi -->
      <div class="flex items-center gap-1.5 sm:gap-2 mb-2">
        <div class="flex-1 py-2 sm:py-2.5 rounded-l-2xl sm:rounded-l-full bg-[#6ebd20] text-white font-bold text-center text-sm sm:text-base tracking-wide shadow-sm transition-all duration-300 {{ $userCat === 'rendah' ? 'ring-4 ring-emerald-300/80 shadow-md font-black scale-105 z-10' : 'opacity-85' }}">
          Rendah
        </div>
        <div class="flex-1 py-2 sm:py-2.5 bg-[#f37920] text-white font-bold text-center text-sm sm:text-base tracking-wide shadow-sm transition-all duration-300 {{ $userCat === 'sedang' ? 'ring-4 ring-amber-300/80 shadow-md font-black scale-105 z-10' : 'opacity-85' }}">
          Sedang
        </div>
        <div class="flex-1 py-2 sm:py-2.5 rounded-r-2xl sm:rounded-r-full bg-[#ff4252] text-white font-bold text-center text-sm sm:text-base tracking-wide shadow-sm transition-all duration-300 {{ $userCat === 'tinggi' ? 'ring-4 ring-rose-300/80 shadow-md font-black scale-105 z-10' : 'opacity-85' }}">
          Tinggi
        </div>
      </div>

      <!-- Continuous 3-color line beneath tabs -->
      <div class="relative w-full">
        <div class="w-full h-1.5 rounded-full" style="background: linear-gradient(to right, #6ebd20 0%, #6ebd20 33.33%, #f37920 33.33%, #f37920 66.66%, #ff4252 66.66%, #ff4252 100%);"></div>
        
        <!-- Upward Pointer Triangle with dynamic risk color -->
        <div class="relative w-full h-5">
          <div 
            class="absolute -top-0.5 transform -translate-x-1/2 text-sm select-none font-bold transition-all duration-300" 
            style="left: {{ $assessment['pointer_left'] ?? '50%' }}; color: {{ $assessment['color'] ?? '#f37920' }};"
          >
            ▲
          </div>
        </div>
      </div>

    </div>

    <!-- Result Recommendation Box (Green-bordered white box) -->
    <div class="bg-white rounded-xl sm:rounded-2xl border border-[#73d4b6] p-5 sm:p-6 shadow-sm">
      <p class="text-gray-800 text-sm sm:text-base mb-3.5">
        Resiko kamu terkena penyakit diabetes, <strong class="underline font-bold text-gray-900">{{ $assessment['category'] ?? 'Sedang' }}</strong>
      </p>

      <div class="ml-4 sm:ml-6 space-y-1.5 text-gray-700 text-xs sm:text-sm leading-relaxed">
        @if(!empty($assessment['recommendations']) && is_array($assessment['recommendations']))
          @foreach($assessment['recommendations'] as $rec)
            <p>{{ $rec }}</p>
          @endforeach
        @else
          <p>Lakukan pemeriksaan gula darah dan tekanan darah secara berkala.</p>
          <p>Batasi konsumsi makanan dengan kadar gula tinggi dan juga makanan / minuman kemasan.</p>
          <p>Konsumsi makan makanan bergizi, tinggi protein dan kaya serat. Istirahat yang cukup dan olahraga teratur.</p>
          <p>Konsultasi dengan dokter spesialis penyakit dalam.</p>
        @endif
      </div>

      <div class="mt-4 pt-3 border-t border-[#73d4b6]/40 text-[11px] text-gray-500 italic leading-relaxed">
        ⚖️ {{ \App\Models\SiteContent::get('result_disclaimer', 'Hasil analisis ini merupakan estimasi skrining berbasis data statistik probabilitas XGBoost, bukan diagnosis medis final. Disarankan untuk berkonsultasi langsung dengan dokter spesialis atau fasilitas kesehatan terdekat.') }}
      </div>
    </div>

    <!-- ============================================================== -->
    <!-- AGENTIC AI: HEALTH CO-PILOT (ASISTEN KLINIS CERDAS)           -->
    <!-- ============================================================== -->
    <div class="bg-gradient-to-br from-emerald-50/70 via-white to-blue-50/50 rounded-2xl sm:rounded-3xl border border-emerald-200/80 p-5 sm:p-7 shadow-sm transition-all relative overflow-hidden" id="agent-section">
      <!-- Glow background accent -->
      <div class="absolute -top-12 -right-12 w-44 h-44 bg-emerald-300/20 rounded-full blur-2xl pointer-events-none"></div>

      <!-- Agent Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-100">
        <div class="flex items-center space-x-3">
          <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#00a870] to-[#0085d0] text-white flex items-center justify-center shadow-md shadow-emerald-500/20 text-xl font-bold flex-shrink-0">
            🤖
          </div>
          <div>
            <div class="flex items-center space-x-2">
              <h2 class="text-base sm:text-lg font-bold text-gray-900 tracking-tight">AI PINTER</h2>
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse mr-1"></span>
                Agentic Online
              </span>
            </div>
            <p class="text-xs text-gray-500">Asisten Klinis Terpersonalisasi Berbasis Penalaran Medis & Data Skrining Anda</p>
          </div>
        </div>

        <!-- Biometric Summary Badges -->
        <div class="flex flex-wrap items-center gap-1.5 text-[11px] font-semibold text-gray-600 bg-white/80 p-1.5 rounded-xl border border-gray-200/70">
          <span class="px-2 py-0.5 rounded-lg bg-gray-100">{{ $assessment['gender'] ?? 'Laki-laki' }}, {{ $assessment['age'] ?? 30 }} Thn</span>
          <span class="px-2 py-0.5 rounded-lg bg-gray-100">BMI: {{ $assessment['bmi'] ?? 24.5 }}</span>
          <span class="px-2 py-0.5 rounded-lg text-white font-bold" style="background-color: {{ $assessment['color'] ?? '#f37920' }};">
            Risiko {{ $assessment['category'] ?? 'Sedang' }}
          </span>
        </div>
      </div>


      <!-- Chat Messages Container -->
      <div id="chat-messages" class="mt-4 space-y-3.5 max-h-[460px] overflow-y-auto pr-1 text-sm scroll-smooth">
        
        <!-- Welcome Message from Agent -->
        <div class="flex items-start space-x-2.5">
          <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm">
            AI
          </div>
          <div class="flex-1 bg-white rounded-2xl rounded-tl-sm p-4 border border-gray-100 shadow-sm text-gray-800 leading-relaxed space-y-2">
            <p>
              Halo! Saya adalah <strong>AI PINTER</strong>. Saya telah mengkaji profil biometrik Anda: 
              <span class="inline-block px-1.5 py-0.5 rounded bg-gray-100 font-semibold text-xs">{{ $assessment['gender'] ?? 'Laki-laki' }}, {{ $assessment['age'] ?? 30 }} thn, BMI {{ $assessment['bmi'] ?? 24.5 }}</span>, 
              dengan hasil evaluasi risiko diabetes XGBoost berada pada level <strong style="color: {{ $assessment['color'] ?? '#f37920' }}">{{ $assessment['category'] ?? 'Sedang' }}</strong>.
            </p>
            <p class="text-xs text-gray-600">
              Bagaimana saya bisa membantu Anda hari ini? Anda dapat menanyakan tentang <em>rekomendasi makanan nusantara rendah gula, takaran kalori harian, atau tes lab yang diperlukan</em> melalui kolom chat di bawah.
            </p>
          </div>
        </div>

      </div>

      <!-- Typing Indicator (Hidden by default) -->
      <div id="typing-indicator" class="hidden mt-3 flex items-center space-x-2 text-xs text-gray-500 font-medium pl-10">
        <div class="flex space-x-1 items-center bg-white px-3 py-2 rounded-xl border border-gray-100 shadow-sm">
          <span class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce"></span>
          <span class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 0.15s"></span>
          <span class="w-2 h-2 bg-emerald-500 rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
          <span class="ml-2 text-emerald-700 font-semibold" id="typing-status-text">AI PINTER sedang bernalar...</span>
        </div>
      </div>

      <!-- File Attachment Preview Area (Hidden by default) -->
      <div id="file-preview-card" class="hidden mt-3 p-3 bg-emerald-50/80 border border-emerald-200 rounded-xl flex items-center justify-between text-xs">
        <div class="flex items-center space-x-2 truncate">
          <span class="text-base">📎</span>
          <div>
            <p id="file-preview-name" class="font-bold text-emerald-900 truncate max-w-xs">berkas.pdf</p>
            <p id="file-preview-size" class="text-[10px] text-emerald-600">0 KB</p>
          </div>
        </div>
        <button type="button" onclick="clearSelectedFile()" class="p-1 hover:bg-emerald-200/50 rounded-lg text-emerald-800 font-bold text-sm">
          ✕
        </button>
      </div>

      <!-- Chat Input Form -->
      <form id="chat-form" onsubmit="handleChatSubmit(event)" class="mt-4 pt-3 border-t border-gray-100">
        <input 
          type="file" 
          id="file-upload-input" 
          accept=".pdf,.png,.jpg,.jpeg,.webp,.txt,.csv" 
          class="hidden" 
          onchange="handleFileSelected(event)"
        >

        <div class="flex items-center gap-2">
          <!-- File Upload Trigger Button -->
          <button 
            type="button" 
            onclick="document.getElementById('file-upload-input').click()" 
            class="px-3 py-2.5 bg-gray-100 hover:bg-emerald-50 border border-gray-200 hover:border-emerald-300 rounded-xl text-gray-700 hover:text-emerald-700 text-xs font-semibold transition flex items-center gap-1.5 flex-shrink-0"
            title="Unggah foto hasil lab, PDF rekam medis, atau file teks gula darah"
          >
            <span>📎</span>
            <span class="hidden sm:inline">Upload Lab / File</span>
          </button>

          <input 
            type="text" 
            id="chat-input" 
            placeholder="Tanyakan obat, makanan, tes lab, atau lampirkan berkas hasil lab..." 
            class="flex-1 bg-white border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 outline-none transition"
            autocomplete="off"
          >
          <button 
            type="submit" 
            id="send-btn" 
            class="px-5 py-2.5 bg-gradient-to-r from-[#00a870] to-[#008f5d] hover:from-[#008f5d] hover:to-[#007b4e] text-white font-bold rounded-xl text-sm transition shadow-sm hover:shadow flex items-center gap-1.5 active:scale-95 disabled:opacity-50"
          >
            <span>Kirim</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
          </button>
        </div>
        <p class="text-[10px] text-gray-400 mt-2 text-center">
          ⚡ Mendukung format berkas PDF, Foto Lab (JPG/PNG), serta file Teks. AI PINTER mengekstrak parameter klinis secara otomatis.
        </p>
      </form>

    </div>

    <!-- Action Buttons: Download PDF & Hitung Ulang -->
    <div class="flex flex-col items-center pt-2 space-y-3">
      <div class="flex flex-col sm:flex-row items-center gap-3 w-full max-w-md justify-center">
        <!-- Download Medical Report Button -->
        <a 
          href="{{ route('agent.export_report') }}" 
          target="_blank"
          class="w-full sm:w-auto flex-1 py-3 px-6 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm sm:text-base rounded-xl transition-all shadow hover:shadow-md active:scale-[0.99] flex items-center justify-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <span>Unduh Laporan Medis (PDF)</span>
        </a>

        <!-- Hitung Ulang Button -->
        <a 
          href="{{ route('quiz') }}" 
          class="w-full sm:w-auto flex-1 py-3 px-6 bg-[#00a870] hover:bg-[#008f5d] text-white font-bold text-sm sm:text-base rounded-xl transition-all shadow hover:shadow-md active:scale-[0.99] text-center"
        >
          Hitung Ulang
        </a>
      </div>

      <div class="flex items-center space-x-4 pt-1">
        <a href="{{ route('home') }}" class="text-xs font-semibold text-[#00a870] hover:underline">
          &larr; Kembali ke Beranda
        </a>
        <span class="text-gray-300">•</span>
        <a href="{{ route('articles') }}" class="text-xs font-semibold text-[#0085d0] hover:underline">
          Baca Berita &amp; Edukasi Terkait &rarr;
        </a>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
  // Inisialisasi Data Asesmen
  const currentAssessment = @json($assessment);
  let chatHistory = [];
  let selectedMedicalFile = null;

  // Konfigurasi Marked
  if (typeof marked !== 'undefined') {
    marked.setOptions({
      breaks: true,
      gfm: true
    });
  }

  function handleFileSelected(event) {
    const file = event.target.files[0];
    if (!file) return;

    selectedMedicalFile = file;
    const previewCard = document.getElementById('file-preview-card');
    const nameEl = document.getElementById('file-preview-name');
    const sizeEl = document.getElementById('file-preview-size');

    nameEl.innerText = file.name;
    sizeEl.innerText = (file.size / 1024).toFixed(1) + ' KB (' + (file.type || 'Berkas Dokumen') + ')';
    previewCard.classList.remove('hidden');

    const input = document.getElementById('chat-input');
    if (!input.value) {
      input.placeholder = 'Ketik catatan tambahan untuk berkas ini (opsional) lalu klik Kirim...';
    }
  }

  function clearSelectedFile() {
    selectedMedicalFile = null;
    document.getElementById('file-upload-input').value = '';
    document.getElementById('file-preview-card').classList.add('hidden');
    document.getElementById('chat-input').placeholder = 'Tanyakan pola makan, obat, target kalori, atau olahraga...';
  }

  function appendUserMessage(text, attachedFile = null) {
    const container = document.getElementById('chat-messages');
    const msgDiv = document.createElement('div');
    msgDiv.className = 'flex items-start justify-end space-x-2.5';

    let fileSnippet = '';
    if (attachedFile) {
      fileSnippet = `
        <div class="mb-2 p-2 bg-emerald-700/60 rounded-xl border border-emerald-400/40 text-xs flex items-center gap-1.5 font-semibold">
          <span>📎 Berkas:</span>
          <span class="truncate max-w-[200px]">${escapeHtml(attachedFile.name)}</span>
        </div>
      `;
    }

    msgDiv.innerHTML = `
      <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white rounded-2xl rounded-tr-sm p-3.5 shadow-sm text-sm max-w-[85%] leading-relaxed">
        ${fileSnippet}
        <p>${escapeHtml(text)}</p>
      </div>
      <div class="w-8 h-8 rounded-xl bg-gray-800 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
        Anda
      </div>
    `;
    container.appendChild(msgDiv);
    container.scrollTop = container.scrollHeight;
  }

  function appendAgentMessage(markdownContent, reasoningSteps = []) {
    const container = document.getElementById('chat-messages');
    const msgDiv = document.createElement('div');
    msgDiv.className = 'flex items-start space-x-2.5';

    let reasoningHtml = '';
    if (reasoningSteps && reasoningSteps.length > 0) {
      reasoningHtml = `
        <details class="mb-2 text-xs bg-emerald-50/70 border border-emerald-200/60 rounded-xl p-2.5 cursor-pointer">
          <summary class="font-semibold text-emerald-800 flex items-center gap-1 select-none">
            <span>⚡ Log Penalaran Agen (${reasoningSteps.length} Langkah)</span>
          </summary>
          <ul class="mt-2 space-y-1 text-gray-600 pl-4 list-disc border-t border-emerald-100 pt-2 font-mono text-[11px]">
            ${reasoningSteps.map(step => `<li>${escapeHtml(step)}</li>`).join('')}
          </ul>
        </details>
      `;
    }

    const htmlContent = typeof marked !== 'undefined' ? marked.parse(markdownContent) : markdownContent.replace(/\n/g, '<br>');

    msgDiv.innerHTML = `
      <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm mt-1">
        AI
      </div>
      <div class="flex-1 bg-white rounded-2xl rounded-tl-sm p-4 border border-gray-100 shadow-sm text-gray-800 leading-relaxed text-sm max-w-[90%] overflow-x-auto">
        ${reasoningHtml}
        <div class="prose prose-sm max-w-none text-gray-800 prose-headings:text-gray-900 prose-headings:font-bold prose-headings:mb-2 prose-p:mb-2 prose-ul:my-2 prose-li:my-0.5 prose-table:text-xs">
          ${htmlContent}
        </div>
      </div>
    `;

    container.appendChild(msgDiv);
    container.scrollTop = container.scrollHeight;
  }

  function showTyping(show, statusText = 'AI PINTER sedang bernalar...') {
    const indicator = document.getElementById('typing-indicator');
    const status = document.getElementById('typing-status-text');
    if (status) status.innerText = statusText;
    if (show) {
      indicator.classList.remove('hidden');
      const container = document.getElementById('chat-messages');
      container.scrollTop = container.scrollHeight;
    } else {
      indicator.classList.add('hidden');
    }
  }

  async function handleChatSubmit(e) {
    if (e) e.preventDefault();
    const input = document.getElementById('chat-input');
    const query = input.value.trim();

    // Jika ada file yang dipilih untuk diunggah
    if (selectedMedicalFile) {
      const fileToSend = selectedMedicalFile;
      clearSelectedFile();
      appendUserMessage(query || 'Mohon periksa dan analisis berkas laboratorium ini.', fileToSend);
      showTyping(true, 'Mengekstraksi parameter klinis & mengaudit berkas medis...');

      const sendBtn = document.getElementById('send-btn');
      sendBtn.disabled = true;

      const formData = new FormData();
      formData.append('medical_file', fileToSend);
      formData.append('note', query);
      formData.append('assessment', JSON.stringify(currentAssessment));

      try {
        const response = await fetch("{{ route('agent.upload_file') }}", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: formData
        });

        const data = await response.json();
        showTyping(false);

        if (data && data.reply) {
          appendAgentMessage(data.reply, data.reasoning_steps || []);
          chatHistory.push({ sender: 'user', text: 'Mengunggah berkas: ' + fileToSend.name });
          chatHistory.push({ sender: 'model', text: data.reply });
        } else {
          appendAgentMessage('Gagal menganalisis berkas medis. Pastikan format berkas sesuai.');
        }
      } catch (err) {
        showTyping(false);
        appendAgentMessage('Terjadi gangguan saat mengunggah berkas.');
        console.error(err);
      } finally {
        sendBtn.disabled = false;
        input.value = '';
      }
      return;
    }

    // Jika pesan teks biasa
    if (!query) return;

    input.value = '';
    appendUserMessage(query);
    showTyping(true, 'AI PINTER menganalisis data klinis & menyusun jawaban...');

    const sendBtn = document.getElementById('send-btn');
    sendBtn.disabled = true;

    try {
      const response = await fetch("{{ route('agent.chat') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          message: query,
          assessment: currentAssessment,
          history: chatHistory
        })
      });

      const data = await response.json();
      showTyping(false);

      if (data && data.reply) {
        appendAgentMessage(data.reply, data.reasoning_steps || []);
        chatHistory.push({ sender: 'user', text: query });
        chatHistory.push({ sender: 'model', text: data.reply });
      } else {
        appendAgentMessage('Maaf, terjadi kendala saat memproses jawaban. Silakan coba kembali.');
      }
    } catch (err) {
      showTyping(false);
      appendAgentMessage('Koneksi terganggu. Pastikan jaringan internet aktif dan coba lagi.');
      console.error(err);
    } finally {
      sendBtn.disabled = false;
    }
  }

  async function triggerQuickAction(actionType) {
    const actionLabels = {
      'meal_plan': '🥗 Minta Rekomendasi Menu Makan 7 Hari Rendah Glikemik',
      'calorie_target': '⚡ Hitung Target Kalori & Batas Gula Harian',
      'lab_tests': '🩺 Rekomendasi Pemeriksaan Laboratorium Lanjutan',
      'exercise': '🏃 Panduan Latihan Fisik Adaptif',
      'risk_explanation': '🔍 Analisis Faktor Risiko Berdasarkan XGBoost'
    };

    const label = actionLabels[actionType] || 'Konsultasi';
    appendUserMessage(label);
    showTyping(true, 'Menjalankan tool klinis: ' + actionType + '...');

    const sendBtn = document.getElementById('send-btn');
    if (sendBtn) sendBtn.disabled = true;

    try {
      const response = await fetch("{{ route('agent.quick_action') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          action: actionType,
          assessment: currentAssessment
        })
      });

      const data = await response.json();
      showTyping(false);

      if (data && data.reply) {
        appendAgentMessage(data.reply, data.reasoning_steps || []);
        chatHistory.push({ sender: 'user', text: label });
        chatHistory.push({ sender: 'model', text: data.reply });
      } else {
        appendAgentMessage('Tidak dapat memproses permintaan aksi saat ini.');
      }
    } catch (err) {
      showTyping(false);
      appendAgentMessage('Terjadi kesalahan jaringan.');
      console.error(err);
    } finally {
      if (sendBtn) sendBtn.disabled = false;
    }
  }

  function escapeHtml(string) {
    const entityMap = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;',
      '/': '&#x2F;'
    };
    return String(string).replace(/[&<>"'\/]/g, function (s) {
      return entityMap[s];
    });
  }
</script>
@endpush

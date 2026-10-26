<!-- ============================================================== -->
<!-- AGENTIC AI - FLOATING CHAT WIDGET & INTERACTIVE PANEL          -->
<!-- Tombol kecil berbentuk lingkaran hanya bertuliskan "AI" di kanan bawah -->
<!-- ============================================================== -->

<div id="agenticAiWidgetRoot" class="relative z-50">

  <!-- 1. FLOATING BUTTON: LINGKARAN KECIL DENGAN HANYA TULISAN "AI" -->
  <button 
    type="button" 
    id="agenticAiFloatingBtn" 
    onclick="toggleAgenticAiChat(event)"
    class="fixed bottom-5 sm:bottom-6 right-4 sm:right-6 z-[99999] w-14 h-14 sm:w-15 sm:h-15 rounded-full bg-gradient-to-tr from-[#00BFA6] via-[#00c9af] to-[#168FE5] text-white shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 flex items-center justify-center cursor-pointer select-none ring-4 ring-[#00BFA6]/25 group"
    title="Buka Agentic AI"
    aria-label="Agentic AI"
  >
    <!-- Ping Notification Badge -->
    <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5 pointer-events-none">
      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00BFA6] opacity-75"></span>
      <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-[#00BFA6] border-2 border-white"></span>
    </span>

    <!-- HANYA TULISAN "AI" (Sesuai Permintaan Spesifikasi 7.A) -->
    <span class="font-extrabold text-base sm:text-lg tracking-wider text-white drop-shadow-sm group-hover:scale-110 transition-transform">
      AI
    </span>
  </button>

  <!-- 2. EXPANDED AGENTIC AI PANEL PERCAKAPAN -->
  <div 
    id="agenticAiChatWindow" 
    class="fixed bottom-3 sm:bottom-24 right-2 sm:right-6 left-2 sm:left-auto w-[calc(100vw-1rem)] sm:w-[440px] max-h-[90vh] h-[min(620px,90vh)] bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-sky-100 flex flex-col overflow-hidden z-[99999] transition-all duration-300 transform scale-95 opacity-0 pointer-events-none origin-bottom-right"
  >
    
    <!-- Header: Judul Agentic AI -->
    <div class="bg-gradient-to-r from-[#00BFA6] via-[#00a896] to-[#168FE5] px-4 py-3.5 text-white flex items-center justify-between shadow-md flex-shrink-0">
      <div class="flex items-center space-x-3">
        <div class="w-9 h-9 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30 flex-shrink-0 font-extrabold text-xs">
          AI
        </div>
        <div>
          <div class="flex items-center space-x-1.5">
            <h3 class="font-black text-sm sm:text-base tracking-wide leading-tight">Agentic AI</h3>
            <span class="px-1.5 py-0.5 bg-white/20 text-[9px] font-extrabold rounded-md uppercase tracking-wider">Diabetes Uji Coba</span>
          </div>
          <p class="text-[10px] text-emerald-100 flex items-center space-x-1 mt-0.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
            <span>Online • Asisten Klinis &amp; Skrining Risiko</span>
          </p>
        </div>
      </div>

      <div class="flex items-center space-x-1">
        <!-- Clear chat -->
        <button 
          type="button" 
          onclick="clearAgenticAiChat()" 
          class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-xs transition-colors cursor-pointer" 
          title="Bersihkan Percakapan"
        >
          🔄
        </button>
        <!-- Close Window -->
        <button 
          type="button" 
          onclick="toggleAgenticAiChat(event)" 
          class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer" 
          title="Tutup Panel AI"
        >
          ✕
        </button>
      </div>
    </div>

    <!-- Quick Action Chips (Bantuan Cepat) -->
    <div class="px-3.5 py-2 bg-gray-50/90 border-b border-gray-100 flex items-center space-x-1.5 overflow-x-auto custom-scrollbar flex-shrink-0 text-xs">
      <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider flex-shrink-0">Topik:</span>
      <button type="button" onclick="askAgenticQuick('Apa itu diabetes dan apa perbedaan tipe 1 dengan tipe 2?')" class="px-2.5 py-1 bg-white hover:bg-emerald-50 text-emerald-800 border border-gray-200 hover:border-emerald-300 rounded-full text-[11px] font-medium whitespace-nowrap transition-colors cursor-pointer">
        Apa itu Diabetes?
      </button>
      <button type="button" onclick="askAgenticQuick('Bagaimana cara menggunakan kuesioner Cek Risiko dan apa arti hasil prediksi XGBoost?')" class="px-2.5 py-1 bg-white hover:bg-emerald-50 text-emerald-800 border border-gray-200 hover:border-emerald-300 rounded-full text-[11px] font-medium whitespace-nowrap transition-colors cursor-pointer">
        Cara Cek Risiko
      </button>
      <button type="button" onclick="askAgenticQuick('Apa saja faktor pemicu risiko diabetes dan bagaimana cara mencegahnya?')" class="px-2.5 py-1 bg-white hover:bg-emerald-50 text-emerald-800 border border-gray-200 hover:border-emerald-300 rounded-full text-[11px] font-medium whitespace-nowrap transition-colors cursor-pointer">
        Pemicu &amp; Pencegahan
      </button>
      <button type="button" onclick="askAgenticQuick('Bagaimana aturan pola makan sehat dan batas gula harian?')" class="px-2.5 py-1 bg-white hover:bg-emerald-50 text-emerald-800 border border-gray-200 hover:border-emerald-300 rounded-full text-[11px] font-medium whitespace-nowrap transition-colors cursor-pointer">
        Pola Makan Sehat
      </button>
    </div>

    <!-- Area Percakapan (Scrollable Message List) -->
    <div id="agenticAiMessagesContainer" class="flex-1 p-4 overflow-y-auto space-y-3.5 bg-gradient-to-b from-slate-50/60 to-white text-xs sm:text-[13px]">
      
      <!-- Welcome Message -->
      <div class="flex items-start space-x-2.5">
        <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-[#00a870] to-[#0085d0] flex items-center justify-center text-white text-[10px] font-black flex-shrink-0 shadow-xs mt-0.5">
          AI
        </div>
        <div class="bg-white border border-gray-200 text-gray-800 rounded-2xl rounded-tl-xs p-3.5 shadow-xs leading-relaxed max-w-[88%] space-y-2">
          <p class="font-bold text-gray-900">
            Halo! Saya Agentic AI dari Diabetes Uji Coba 👋
          </p>
          <p>
            Saya siap membantu menjawab pertanyaan Anda seputar faktor risiko diabetes, pola makan rendah gula, aturan obat, interpretasi hasil tes darah, maupun panduan penggunaan fitur website ini.
          </p>
          <p class="text-[11px] text-gray-500 pt-1 border-t border-gray-100">
            Ketik pertanyaan Anda di bawah atau pilih tombol topik cepat di atas untuk memulai!
          </p>
        </div>
      </div>

    </div>

    <!-- Error & Retry Box (Muncul hanya jika terjadi kegagalan) -->
    <div id="agenticAiErrorBox" class="hidden px-4 py-2.5 bg-rose-50 border-t border-rose-200 text-rose-700 text-xs flex items-center justify-between flex-shrink-0">
      <div class="flex items-center space-x-2">
        <span>⚠️</span>
        <span id="agenticAiErrorMsg">Gagal memproses pertanyaan.</span>
      </div>
      <button 
        type="button" 
        onclick="retryLastAgenticMessage()" 
        class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-[11px] transition-colors cursor-pointer"
      >
        Coba Lagi ↺
      </button>
    </div>

    <!-- Indikator Bahwa AI Sedang Memproses -->
    <div id="agenticAiTypingIndicator" class="hidden px-4 py-2 bg-emerald-50/70 border-t border-emerald-100 text-xs text-emerald-800 items-center space-x-2 flex-shrink-0">
      <div class="flex space-x-1">
        <div class="w-1.5 h-1.5 bg-[#00a870] rounded-full animate-bounce"></div>
        <div class="w-1.5 h-1.5 bg-[#059669] rounded-full animate-bounce" style="animation-delay: 0.15s"></div>
        <div class="w-1.5 h-1.5 bg-[#0085d0] rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
      </div>
      <span class="font-medium text-gray-600 text-[11px]">Agentic AI sedang menganalisis pertanyaan klinis Anda...</span>
    </div>

    <!-- Kolom Input Pertanyaan & Tombol Kirim -->
    <div class="p-3 bg-white border-t border-gray-100 flex-shrink-0">
      <form id="agenticAiForm" onsubmit="sendAgenticAiMessage(event)" class="flex items-center space-x-2">
        <input 
          type="text" 
          id="agenticAiInputText" 
          placeholder="Tanyakan obat, gejala, atau cara cek risiko..." 
          class="flex-1 px-3.5 py-2.5 text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:border-[#00a870] focus:ring-2 focus:ring-[#00a870]/20 outline-none transition-all text-gray-800"
          autocomplete="off"
        >
        <button 
          type="submit" 
          id="agenticAiSendBtn"
          class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#00a870] via-[#059669] to-[#0085d0] text-white flex items-center justify-center hover:scale-105 active:scale-95 shadow-md shadow-emerald-500/25 transition-all flex-shrink-0 cursor-pointer disabled:opacity-50"
          title="Kirim Pertanyaan"
        >
          <svg class="w-4 h-4 transform rotate-90" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
          </svg>
        </button>
      </form>
      <p class="text-[9px] text-gray-400 text-center mt-1.5">
        🛡️ Berbasis Konsensus PERKENI, ADA Standards of Care, &amp; WHO. Bukan pengganti diagnosis medis dokter.
      </p>
    </div>

  </div>

</div>

<!-- JAVASCRIPT CONTROLLER AGENTIC AI -->
<script>
  let agenticChatHistory = [];
  let isAgenticAiOpen = false;
  let lastFailedQuery = null;

  function toggleAgenticAiChat(e) {
    if (e && e.stopPropagation) e.stopPropagation();
    const win = document.getElementById('agenticAiChatWindow');
    const floatBtn = document.getElementById('agenticAiFloatingBtn');
    if (!win) return;
    
    isAgenticAiOpen = !isAgenticAiOpen;
    if (isAgenticAiOpen) {
      win.classList.remove('scale-95', 'opacity-0', 'pointer-events-none');
      win.classList.add('scale-100', 'opacity-100', 'pointer-events-auto');
      if (floatBtn && window.innerWidth < 640) {
        floatBtn.classList.add('opacity-0', 'pointer-events-none');
      }
      setTimeout(() => {
        const inp = document.getElementById('agenticAiInputText');
        if (inp) inp.focus();
      }, 150);
    } else {
      win.classList.remove('scale-100', 'opacity-100', 'pointer-events-auto');
      win.classList.add('scale-95', 'opacity-0', 'pointer-events-none');
      if (floatBtn) {
        floatBtn.classList.remove('opacity-0', 'pointer-events-none');
      }
    }
  }

  function clearAgenticAiChat() {
    agenticChatHistory = [];
    const container = document.getElementById('agenticAiMessagesContainer');
    if (!container) return;
    container.innerHTML = `
      <div class="flex items-start space-x-2.5">
        <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-[#00a870] to-[#0085d0] flex items-center justify-center text-white text-[10px] font-black flex-shrink-0 shadow-xs mt-0.5">
          AI
        </div>
        <div class="bg-white border border-gray-200 text-gray-800 rounded-2xl rounded-tl-xs p-3.5 shadow-xs leading-relaxed max-w-[88%] space-y-2">
          <p class="font-bold text-gray-900">
            Percakapan telah dibersihkan.
          </p>
          <p>
            Silakan ajukan pertanyaan baru mengenai faktor risiko diabetes, pola makan, obat, atau penggunaan fitur Diabetes Uji Coba.
          </p>
        </div>
      </div>
    `;
    const errBox = document.getElementById('agenticAiErrorBox');
    if (errBox) errBox.classList.add('hidden');
  }

  function askAgenticQuick(text) {
    const input = document.getElementById('agenticAiInputText');
    if (input) {
      input.value = text;
      sendAgenticAiMessage();
    }
  }

  function appendUserBubble(text) {
    const container = document.getElementById('agenticAiMessagesContainer');
    if (!container) return;
    const div = document.createElement('div');
    div.className = 'flex justify-end';
    div.innerHTML = `
      <div class="bg-gradient-to-r from-[#00a870] to-[#008f5d] text-white rounded-2xl rounded-tr-xs p-3 shadow-xs max-w-[85%] text-xs sm:text-[13px] leading-relaxed break-words">
        ${escapeAgenticHtml(text)}
      </div>
    `;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
  }

  function appendBotBubble(markdownText) {
    const container = document.getElementById('agenticAiMessagesContainer');
    if (!container) return;
    const div = document.createElement('div');
    div.className = 'flex items-start space-x-2.5';
    div.innerHTML = `
      <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-[#00a870] to-[#0085d0] flex items-center justify-center text-white text-[10px] font-black flex-shrink-0 shadow-xs mt-0.5">
        AI
      </div>
      <div class="bg-white border border-gray-200 text-gray-800 rounded-2xl rounded-tl-xs p-3.5 shadow-xs text-xs sm:text-[13px] leading-relaxed max-w-[88%] space-y-2 break-words">
        ${formatAgenticMarkdown(markdownText)}
      </div>
    `;
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
  }

  function formatAgenticMarkdown(text) {
    if (!text) return '';
    let html = escapeAgenticHtml(text);

    // Headers
    html = html.replace(/^### (.*$)/gim, '<h4 class="font-extrabold text-gray-900 text-xs sm:text-sm mt-2 mb-1">$1</h4>');
    html = html.replace(/^## (.*$)/gim, '<h3 class="font-extrabold text-emerald-800 text-sm mt-2.5 mb-1">$1</h3>');

    // Bold & Italic
    html = html.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-gray-900">$1</strong>');
    html = html.replace(/\*(.*?)\*/g, '<em class="italic text-gray-700">$1</em>');

    // Bullet points
    html = html.replace(/^\* (.*$)/gim, '<div class="flex items-start space-x-1.5 ml-1"><span class="text-emerald-600 font-bold">•</span><span>$1</span></div>');
    html = html.replace(/^• (.*$)/gim, '<div class="flex items-start space-x-1.5 ml-1"><span class="text-emerald-600 font-bold">•</span><span>$1</span></div>');

    // Blockquotes
    html = html.replace(/^> (.*$)/gim, '<div class="p-2.5 bg-amber-50/80 border-l-3 border-amber-400 text-amber-900 text-[11px] rounded-r-lg my-1.5">$1</div>');

    // Horizontal rule
    html = html.replace(/---/g, '<hr class="my-2 border-gray-200">');

    // Tables
    if (html.includes('|')) {
      const lines = html.split('\n');
      let inTable = false;
      let newLines = [];
      for (let line of lines) {
        if (line.trim().startsWith('|') && line.trim().endsWith('|')) {
          if (!inTable) {
            inTable = true;
            newLines.push('<div class="overflow-x-auto my-2"><table class="w-full text-[11px] border border-gray-200 rounded-lg">');
          }
          if (line.includes('---')) continue;
          const cells = line.split('|').filter((c, i, a) => i > 0 && i < a.length - 1);
          newLines.push('<tr class="border-b border-gray-100">');
          for (let cell of cells) {
            newLines.push(`<td class="p-1.5 border-r border-gray-100">${cell.trim()}</td>`);
          }
          newLines.push('</tr>');
        } else {
          if (inTable) {
            inTable = false;
            newLines.push('</table></div>');
          }
          newLines.push(line);
        }
      }
      if (inTable) newLines.push('</table></div>');
      html = newLines.join('\n');
    }

    // Newlines to <br>
    html = html.replace(/\n{2,}/g, '<br><br>').replace(/\n/g, '<br>');

    return html;
  }

  function escapeAgenticHtml(str) {
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  async function sendAgenticAiMessage(e) {
    if (e) e.preventDefault();
    const input = document.getElementById('agenticAiInputText');
    const sendBtn = document.getElementById('agenticAiSendBtn');
    const typing = document.getElementById('agenticAiTypingIndicator');
    const errBox = document.getElementById('agenticAiErrorBox');
    
    if (!input) return;
    const query = input.value.trim();
    if (!query) return;

    input.value = '';
    appendUserBubble(query);
    lastFailedQuery = query;

    if (errBox) errBox.classList.add('hidden');
    if (typing) {
      typing.classList.remove('hidden');
      typing.classList.add('flex');
    }
    if (sendBtn) sendBtn.disabled = true;

    try {
      const csrfEl = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = csrfEl ? csrfEl.getAttribute('content') : '';

      const response = await fetch("{{ route('agent.chat') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
          message: query,
          history: agenticChatHistory
        })
      });

      const data = await response.json();

      if (data && data.reply) {
        appendBotBubble(data.reply);
        agenticChatHistory.push({ sender: 'user', text: query });
        agenticChatHistory.push({ sender: 'model', text: data.reply });
        lastFailedQuery = null;
      } else {
        showAgenticError('Maaf, respons tidak dapat dihasilkan. Silakan coba lagi.');
      }
    } catch (err) {
      console.error('Agentic AI error:', err);
      showAgenticError('Koneksi terganggu. Periksa jaringan Anda dan coba lagi.');
    } finally {
      if (typing) {
        typing.classList.remove('flex');
        typing.classList.add('hidden');
      }
      if (sendBtn) sendBtn.disabled = false;
      input.focus();
    }
  }

  function showAgenticError(msg) {
    const errBox = document.getElementById('agenticAiErrorBox');
    const errMsg = document.getElementById('agenticAiErrorMsg');
    if (errBox) errBox.classList.remove('hidden');
    if (errMsg) errMsg.textContent = msg;
  }

  function retryLastAgenticMessage() {
    if (!lastFailedQuery) return;
    const input = document.getElementById('agenticAiInputText');
    if (input) {
      input.value = lastFailedQuery;
      sendAgenticAiMessage();
    }
  }

  // Backward compatibility aliases
  window.toggleAgenticAiChat = toggleAgenticAiChat;
  window.toggleWIJAYAChat = toggleAgenticAiChat;
  window.toggleHildaChat = toggleAgenticAiChat;
  window.toggleAiPinter = toggleAgenticAiChat;
  window.clearAgenticAiChat = clearAgenticAiChat;
  window.clearWIJAYAChat = clearAgenticAiChat;
  window.askAgenticQuick = askAgenticQuick;
  window.askWIJAYAQuick = askAgenticQuick;
  window.sendAgenticAiMessage = sendAgenticAiMessage;
  window.sendWIJAYAMessage = sendAgenticAiMessage;
  window.retryLastAgenticMessage = retryLastAgenticMessage;
</script>

@extends('layouts.app')

@section('title', 'Masuk Admin - Diabetes Uji Coba')

@section('content')
<section class="min-h-[calc(100vh-160px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-emerald-50/40 via-white to-gray-50">
  <div class="max-w-md w-full space-y-6">
    
    <!-- Top Brand & Header -->
    <div class="text-center">
      <div class="flex justify-center mb-4">
        <a href="{{ route('home') }}" class="hover:opacity-90 transition-opacity">
          <x-site-logo size="lg" />
        </a>
      </div>
      <div class="inline-flex items-center space-x-2 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-200 mb-3">
        <span>🔐 Portal Autentikasi Administrator</span>
      </div>
      <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
        Masuk Admin Diabetes Uji Coba
      </h1>
      <p class="text-xs sm:text-sm text-gray-500 mt-1.5">
        Akses khusus admin untuk riwayat skrining AI, manajemen data pemeriksaan, dan audit klinis.
      </p>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xl shadow-emerald-950/5">
      
      <!-- Tab Switcher: Password vs WhatsApp -->
      <div class="flex items-center p-1 bg-gray-100/80 rounded-2xl mb-6">
        <button 
          type="button" 
          id="tabPasswordBtn" 
          onclick="switchAuthTab('password')" 
          class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition-all bg-white text-gray-900 shadow-sm cursor-pointer"
        >
          🔑 Kata Sandi
        </button>
        <button 
          type="button" 
          id="tabWaBtn" 
          onclick="switchAuthTab('wa')" 
          class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition-all text-gray-500 hover:text-gray-900 cursor-pointer"
        >
          📱 Kode WhatsApp
        </button>
      </div>

      <!-- Flash Messages -->
      @if(session('success'))
        <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center space-x-2">
          <span>✅</span>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      @if($errors->any())
        <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs space-y-1">
          @foreach($errors->all() as $error)
            <div class="flex items-center space-x-1.5">
              <span>⚠️</span>
              <span>{{ $error }}</span>
            </div>
          @endforeach
        </div>
      @endif

      <!-- Dynamic Alert Container for WhatsApp AJAX -->
      <div id="waAlertBox" class="hidden mb-5 p-3.5 rounded-2xl text-xs font-medium space-y-1"></div>

      <!-- ============================================================ -->
      <!-- TAB 1: FORM LOGIN EMAIL & KATA SANDI                         -->
      <!-- ============================================================ -->
      <div id="panePassword">
        <form action="{{ route('login.post') }}" method="POST" id="passwordLoginForm" onsubmit="handlePasswordSubmit(event)" class="space-y-4">
          @csrf

          <!-- Input Email / Username -->
          <div>
            <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
              Email atau Username Admin
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                </svg>
              </div>
              <input 
                type="text" 
                name="email" 
                id="email" 
                value="{{ old('email', 'admin@klikdokter.com') }}" 
                required 
                placeholder="admin@klikdokter.com"
                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-kd-green focus:ring-2 focus:ring-kd-green/20 text-sm transition-all outline-none"
              >
            </div>
          </div>

          <!-- Input Password with Show/Hide Toggle -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                Kata Sandi
              </label>
              <span class="text-[11px] text-gray-400">Minimal 6 karakter</span>
            </div>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
              </div>
              <input 
                type="password" 
                name="password" 
                id="password" 
                value="admin123"
                required 
                placeholder="••••••••"
                class="w-full pl-10 pr-11 py-3 rounded-xl border border-gray-200 focus:border-kd-green focus:ring-2 focus:ring-kd-green/20 text-sm transition-all outline-none"
              >
              <!-- Toggle Show/Hide Button -->
              <button 
                type="button" 
                onclick="togglePasswordVisibility()" 
                id="btnTogglePassword"
                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer"
                title="Tampilkan / Sembunyikan Kata Sandi"
              >
                <svg id="eyeIconOpen" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg id="eyeIconClosed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center justify-between pt-1">
            <label class="flex items-center space-x-2 cursor-pointer">
              <input type="checkbox" name="remember" class="w-4 h-4 rounded text-kd-green focus:ring-kd-green border-gray-300">
              <span class="text-xs text-gray-600 font-medium">Ingat saya di perangkat ini</span>
            </label>
          </div>

          <!-- Submit Button -->
          <div class="pt-2">
            <button 
              type="submit" 
              id="passwordSubmitBtn"
              class="w-full py-3.5 px-4 bg-kd-green hover:bg-kd-greenDark text-white font-bold text-sm rounded-xl shadow-lg shadow-kd-green/25 hover:shadow-kd-green/40 transition-all hover:scale-[1.01] flex items-center justify-center space-x-2 cursor-pointer"
            >
              <svg id="passSpinner" class="w-4 h-4 text-white animate-spin hidden" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
              </svg>
              <span id="passBtnText">Masuk Sekarang</span>
            </button>
          </div>
        </form>
      </div>

      <!-- ============================================================ -->
      <!-- TAB 2: VERIFIKASI LOGIN KODE WHATSAPP                         -->
      <!-- ============================================================ -->
      <div id="paneWa" class="hidden space-y-4">
        
        <!-- Step 1: Input Nomor WhatsApp Admin -->
        <div id="waStepRequest" class="space-y-4">
          <div>
            <label for="waIdentifier" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">
              Nomor WhatsApp / Email Terdaftar
            </label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 14.99 3.81 13.47 3.81 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"/>
                </svg>
              </div>
              <input 
                type="text" 
                id="waIdentifier" 
                value="081234567890" 
                placeholder="Contoh: 081234567890 atau admin@klikdokter.com"
                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:border-kd-green focus:ring-2 focus:ring-kd-green/20 text-sm transition-all outline-none"
              >
            </div>
            <p class="text-[11px] text-gray-500 mt-1.5">
              Kode OTP 6 digit akan dikirimkan ke nomor WhatsApp resmi administrator.
            </p>
          </div>

          <button 
            type="button" 
            onclick="requestWhatsAppOtp()" 
            id="waRequestBtn"
            class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-600/25 transition-all hover:scale-[1.01] flex items-center justify-center space-x-2 cursor-pointer"
          >
            <svg id="waReqSpinner" class="w-4 h-4 text-white animate-spin hidden" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span id="waReqBtnText">Kirim Kode Verifikasi via WhatsApp</span>
          </button>
        </div>

        <!-- Step 2: Input 6-Digit OTP Code (Muncul setelah request berhasil) -->
        <div id="waStepVerify" class="hidden space-y-4">
          <div class="p-3.5 bg-emerald-50/70 border border-emerald-200 rounded-2xl text-xs text-emerald-900 flex items-start space-x-2.5">
            <span class="text-base">📲</span>
            <div>
              Kode verifikasi telah dikirim ke nomor WhatsApp Anda: <strong id="waTargetMasked" class="font-bold"></strong>.
              <div class="text-[11px] text-gray-500 mt-0.5">Masa berlaku: <span id="waTimerCountdown" class="font-bold text-rose-600">05:00</span></div>
            </div>
          </div>

          <div>
            <label for="waOtpCode" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5 text-center">
              Masukkan 6 Digit Kode Verifikasi
            </label>
            <input 
              type="text" 
              id="waOtpCode" 
              maxlength="6" 
              placeholder="000000"
              autocomplete="one-time-code"
              class="w-full tracking-[0.5em] text-center text-2xl font-black py-3 rounded-xl border border-gray-200 focus:border-kd-green focus:ring-2 focus:ring-kd-green/20 outline-none transition-all"
            >
          </div>

          <button 
            type="button" 
            onclick="verifyWhatsAppOtp()" 
            id="waVerifyBtn"
            class="w-full py-3.5 px-4 bg-kd-green hover:bg-kd-greenDark text-white font-bold text-sm rounded-xl shadow-lg shadow-kd-green/25 hover:shadow-kd-green/40 transition-all hover:scale-[1.01] flex items-center justify-center space-x-2 cursor-pointer"
          >
            <svg id="waVerSpinner" class="w-4 h-4 text-white animate-spin hidden" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span id="waVerBtnText">Verifikasi &amp; Masuk</span>
          </button>

          <div class="flex items-center justify-between text-xs pt-1">
            <button type="button" onclick="backToWaRequest()" class="text-gray-500 hover:text-gray-800 font-semibold cursor-pointer">
              &larr; Ganti Nomor
            </button>
            <button type="button" id="waResendBtn" onclick="requestWhatsAppOtp()" class="text-emerald-700 font-bold hover:underline cursor-pointer">
              Kirim Ulang Kode
            </button>
          </div>
        </div>

      </div>

      <!-- Quick Demo Credentials Box -->
      <div class="mt-6 pt-5 border-t border-gray-100 bg-gray-50/70 -mx-6 -mb-6 sm:-mx-8 sm:-mb-8 p-4 sm:p-5 rounded-b-3xl">
        <div class="flex items-start space-x-2.5">
          <span class="text-base">💡</span>
          <div class="text-[11px] text-gray-500 leading-relaxed">
            <strong class="text-gray-800">Kredensial Admin Bawaan:</strong><br>
            Email: <code class="bg-gray-200/80 px-1.5 py-0.5 rounded text-gray-800 font-semibold">admin@klikdokter.com</code> | Sandi: <code class="bg-gray-200/80 px-1.5 py-0.5 rounded text-gray-800 font-semibold">admin123</code><br>
            WhatsApp Terdaftar: <code class="bg-gray-200/80 px-1.5 py-0.5 rounded text-gray-800 font-semibold">081234567890</code>
          </div>
        </div>
      </div>

    </div>

    <!-- Back to Home -->
    <div class="text-center pt-2">
      <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-kd-green font-semibold transition-colors flex items-center justify-center space-x-1">
        <span>&larr;</span>
        <span>Kembali ke Beranda</span>
      </a>
    </div>

  </div>
</section>
@endsection

@push('scripts')
<script>
  let waUserId = null;
  let waCountdownInterval = null;

  function switchAuthTab(tab) {
    const panePass = document.getElementById('panePassword');
    const paneWa = document.getElementById('paneWa');
    const btnPass = document.getElementById('tabPasswordBtn');
    const btnWa = document.getElementById('tabWaBtn');
    const alertBox = document.getElementById('waAlertBox');
    if (alertBox) alertBox.classList.add('hidden');

    if (tab === 'password') {
      panePass.classList.remove('hidden');
      paneWa.classList.add('hidden');
      btnPass.className = 'flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition-all bg-white text-gray-900 shadow-sm cursor-pointer';
      btnWa.className = 'flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition-all text-gray-500 hover:text-gray-900 cursor-pointer';
    } else {
      paneWa.classList.remove('hidden');
      panePass.classList.add('hidden');
      btnWa.className = 'flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition-all bg-white text-gray-900 shadow-sm cursor-pointer';
      btnPass.className = 'flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition-all text-gray-500 hover:text-gray-900 cursor-pointer';
    }
  }

  function togglePasswordVisibility() {
    const input = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeIconOpen');
    const eyeClosed = document.getElementById('eyeIconClosed');
    if (input.type === 'password') {
      input.type = 'text';
      eyeOpen.classList.add('hidden');
      eyeClosed.classList.remove('hidden');
    } else {
      input.type = 'password';
      eyeClosed.classList.add('hidden');
      eyeOpen.classList.remove('hidden');
    }
  }

  function handlePasswordSubmit(e) {
    const btn = document.getElementById('passwordSubmitBtn');
    const spinner = document.getElementById('passSpinner');
    const txt = document.getElementById('passBtnText');
    if (btn) btn.disabled = true;
    if (spinner) spinner.classList.remove('hidden');
    if (txt) txt.textContent = 'Memverifikasi Kredensial...';
  }

  function showWaAlert(type, message) {
    const box = document.getElementById('waAlertBox');
    if (!box) return;
    box.className = type === 'success' 
      ? 'mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-semibold' 
      : 'mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-2xl text-rose-700 text-xs font-semibold';
    box.innerHTML = message;
    box.classList.remove('hidden');
  }

  async function requestWhatsAppOtp() {
    const identifier = document.getElementById('waIdentifier').value.trim();
    if (!identifier) {
      showWaAlert('error', 'Silakan masukkan nomor WhatsApp atau email administrator.');
      return;
    }

    const btn = document.getElementById('waRequestBtn');
    const spinner = document.getElementById('waReqSpinner');
    const txt = document.getElementById('waReqBtnText');
    const resendBtn = document.getElementById('waResendBtn');

    if (btn) btn.disabled = true;
    if (resendBtn) resendBtn.disabled = true;
    if (spinner) spinner.classList.remove('hidden');
    if (txt) txt.textContent = 'Menghubungkan ke WhatsApp Gateway...';

    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const response = await fetch("{{ route('login.whatsapp.request') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ identifier: identifier })
      });

      const res = await response.json();

      if (response.ok && res.success) {
        waUserId = res.user_id;
        document.getElementById('waStepRequest').classList.add('hidden');
        document.getElementById('waStepVerify').classList.remove('hidden');
        document.getElementById('waTargetMasked').textContent = res.phone_masked || identifier;
        
        let msg = '✅ ' + (res.message || 'Kode verifikasi telah dikirim.');
        if (res.debug_code) {
          msg += ` <br><span class="inline-block mt-1 font-mono text-[11px] bg-emerald-200/60 px-2 py-0.5 rounded text-emerald-900">Kode Pengujian Lokal: <strong>${res.debug_code}</strong></span>`;
        }
        showWaAlert('success', msg);
        startOtpCountdown(300);
        setTimeout(() => document.getElementById('waOtpCode').focus(), 150);
      } else {
        showWaAlert('error', '⚠️ ' + (res.message || 'Gagal mengirim kode verifikasi WhatsApp.'));
      }
    } catch (err) {
      showWaAlert('error', '⚠️ Gagal terhubung ke server. Pastikan koneksi jaringan stabil.');
    } finally {
      if (btn) btn.disabled = false;
      if (resendBtn) resendBtn.disabled = false;
      if (spinner) spinner.classList.add('hidden');
      if (txt) txt.textContent = 'Kirim Kode Verifikasi via WhatsApp';
    }
  }

  async function verifyWhatsAppOtp() {
    const code = document.getElementById('waOtpCode').value.trim();
    if (!code || code.length !== 6) {
      showWaAlert('error', 'Silakan masukkan 6 digit kode verifikasi yang lengkap.');
      return;
    }

    const btn = document.getElementById('waVerifyBtn');
    const spinner = document.getElementById('waVerSpinner');
    const txt = document.getElementById('waVerBtnText');

    if (btn) btn.disabled = true;
    if (spinner) spinner.classList.remove('hidden');
    if (txt) txt.textContent = 'Memverifikasi Kode OTP...';

    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const response = await fetch("{{ route('login.whatsapp.verify') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ user_id: waUserId, code: code })
      });

      const res = await response.json();

      if (response.ok && res.success) {
        showWaAlert('success', '✅ Verifikasi WhatsApp berhasil! Mengalihkan ke Halaman Riwayat Admin...');
        if (waCountdownInterval) clearInterval(waCountdownInterval);
        setTimeout(() => {
          window.location.href = res.redirect || "{{ route('history') }}";
        }, 800);
      } else {
        showWaAlert('error', '⚠️ ' + (res.message || 'Kode verifikasi tidak sesuai atau sudah kedaluwarsa.'));
      }
    } catch (err) {
      showWaAlert('error', '⚠️ Gagal memverifikasi kode. Silakan coba kembali.');
    } finally {
      if (btn) btn.disabled = false;
      if (spinner) spinner.classList.add('hidden');
      if (txt) txt.textContent = 'Verifikasi & Masuk';
    }
  }

  function backToWaRequest() {
    if (waCountdownInterval) clearInterval(waCountdownInterval);
    document.getElementById('waStepVerify').classList.add('hidden');
    document.getElementById('waStepRequest').classList.remove('hidden');
  }

  function startOtpCountdown(seconds) {
    if (waCountdownInterval) clearInterval(waCountdownInterval);
    let remaining = seconds;
    const el = document.getElementById('waTimerCountdown');

    waCountdownInterval = setInterval(() => {
      remaining--;
      if (remaining <= 0) {
        clearInterval(waCountdownInterval);
        if (el) el.textContent = 'Kedaluwarsa';
        showWaAlert('error', '⚠️ Kode verifikasi telah kedaluwarsa. Silakan klik "Kirim Ulang Kode".');
      } else {
        const m = Math.floor(remaining / 60);
        const s = remaining % 60;
        if (el) el.textContent = `${m < 10 ? '0' : ''}${m}:${s < 10 ? '0' : ''}${s}`;
      }
    }, 1000);
  }
</script>
@endpush

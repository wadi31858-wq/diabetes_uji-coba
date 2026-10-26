@extends('layouts.app')

@section('title', 'Diabetes Uji Coba - Cek Risiko Diabetes')

@section('content')
<div class="relative bg-gradient-to-b from-[#eaf7ff] via-[#f4faf9] to-[#d8eeeb] min-h-[92vh] py-8 sm:py-12 overflow-hidden flex items-center justify-center">
  
  <!-- Subtle Modern Hospital Backdrop Silhouette -->
  <div 
    class="hospital-bg-animated absolute inset-0 bg-cover bg-center sm:bg-[center_top_15%] pointer-events-none opacity-25 filter blur-[1px]" 
    style="background-image: url('{{ asset('assets/hero_hospital.jpg') }}');"
  ></div>

  <!-- Flying Birds Animation in Quiz Sky -->
  @include('components.birds-animation')

  <!-- Subtle Medical Ambient Accents -->
  <div class="absolute inset-0 pointer-events-none opacity-40 select-none">
    <div class="absolute top-10 left-10 text-cyan-400/40">
      <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
        <path d="M19 10.5h-5.5V5c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v5.5H5c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5h5.5V19c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5v-5.5H19c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5z"/>
      </svg>
    </div>
    <div class="absolute top-1/3 right-8 w-80 h-80 bg-cyan-200/40 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-1/4 w-96 h-96 bg-teal-200/35 rounded-full blur-3xl"></div>
  </div>

  <div class="relative z-10 w-full max-w-3xl mx-auto px-4 sm:px-6">

    <!-- 1. TOP BRAND FLOATING PILL -->
    <div class="flex justify-center mb-5 sm:mb-6">
      <div class="inline-flex items-center space-x-3 px-5 py-2 sm:px-6 sm:py-2.5 bg-white/95 backdrop-blur-md rounded-full shadow-md border border-sky-100">
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-tr from-[#00BFA6] to-[#168FE5] flex items-center justify-center text-white shadow-sm shadow-teal-500/30 flex-shrink-0">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h2l1-2 2 4 1-2h2"></path>
          </svg>
        </div>
        <div class="border-l border-gray-200 pl-3 text-left">
          <h2 class="font-extrabold text-[#123B5D] text-sm sm:text-base leading-tight tracking-tight">
            Diabetes <span class="text-[#168FE5]">Uji Coba</span>
          </h2>
          <p class="text-[10px] sm:text-[11px] text-[#5C7893] font-medium">{{ \App\Models\SiteContent::get('quiz_subtitle', 'Deteksi Dini Diabetes, Jaga Kesehatan Anda') }}</p>
        </div>
      </div>
    </div>

    <!-- 2. STEPPER NAVIGATION PILLS -->
    <div class="max-w-md mx-auto mb-6">
      <div class="flex items-center justify-between relative px-2">
        
        <!-- Connecting Track -->
        <div class="absolute left-8 right-8 top-3.5 h-0.5 bg-gray-200 z-0">
          <div id="stepperLineProgress" class="h-full bg-gradient-to-r from-[#00BFA6] to-[#168FE5] transition-all duration-500 w-[15%]"></div>
        </div>

        <!-- Step 1 Pill -->
        <div class="relative z-10">
          <div id="stepPill1" class="flex items-center space-x-1 sm:space-x-2 px-2.5 sm:px-3.5 py-1 sm:py-1.5 bg-[#00BFA6] text-white rounded-full text-[10px] sm:text-xs font-bold shadow-md shadow-[#00BFA6]/25">
            <span class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-white text-[#00BFA6] flex items-center justify-center text-[9px] sm:text-[11px] font-extrabold flex-shrink-0">1</span>
            <span class="whitespace-nowrap"><span class="sm:hidden">Profil</span><span class="hidden sm:inline">Jenis Kelamin</span></span>
          </div>
        </div>

        <!-- Step 2 Pill -->
        <div class="relative z-10">
          <div id="stepPill2" class="flex items-center space-x-1 sm:space-x-2 px-2.5 sm:px-3.5 py-1 sm:py-1.5 bg-white text-gray-500 border border-gray-200/80 rounded-full text-[10px] sm:text-xs font-semibold shadow-xs">
            <span class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-slate-200 text-gray-600 flex items-center justify-center text-[9px] sm:text-[11px] font-extrabold flex-shrink-0">2</span>
            <span class="whitespace-nowrap"><span class="sm:hidden">Fisik</span><span class="hidden sm:inline">Data Kesehatan</span></span>
          </div>
        </div>

        <!-- Step 3 Pill -->
        <div class="relative z-10">
          <div id="stepPill3" class="flex items-center space-x-1 sm:space-x-2 px-2.5 sm:px-3.5 py-1 sm:py-1.5 bg-white text-gray-500 border border-gray-200/80 rounded-full text-[10px] sm:text-xs font-semibold shadow-xs">
            <span class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-slate-200 text-gray-600 flex items-center justify-center text-[9px] sm:text-[11px] font-extrabold flex-shrink-0">3</span>
            <span class="whitespace-nowrap"><span class="sm:hidden">Klinis</span><span class="hidden sm:inline">Hasil Analisis</span></span>
          </div>
        </div>

      </div>
    </div>

    <form id="quizForm" action="{{ route('predict') }}" method="POST">
      @csrf

      <input type="hidden" name="gender" id="selectedGenderInput" value="Laki-laki">

      <!-- ========================================================== -->
      <!-- STEP 1: PILIH JENIS KELAMIN                               -->
      <!-- ========================================================== -->
      <section id="step1Section" class="step-section space-y-5">
        
        <!-- 3. TITLE CARD BANNER -->
        <div class="max-w-2xl mx-auto">
          <div class="bg-gradient-to-r from-teal-50/90 via-white to-blue-50/90 backdrop-blur-sm border border-teal-100 rounded-3xl py-3.5 sm:py-4 px-6 text-center shadow-xs">
            <h1 class="text-xl sm:text-2xl font-black text-[#0f2942] tracking-tight">
              Pilih Jenis Kelamin Anda
            </h1>
            <p class="text-[11px] sm:text-xs text-gray-500 mt-1">
              Langkah 1 dari 3: Sesuaikan data biologis untuk akurasi perhitungan.
            </p>
          </div>
        </div>

        <!-- 4. TWO SELECTION CARDS (LAKI-LAKI & PEREMPUAN) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 max-w-2xl mx-auto">
          
          <!-- Card Laki-laki (Selected by Default) -->
          <div 
            onclick="selectGender('Laki-laki')" 
            id="cardMale"
            class="relative group bg-white rounded-3xl p-5 sm:p-6 border-2 border-[#00c9a7] ring-4 ring-[#00c9a7]/15 shadow-xl shadow-teal-500/10 cursor-pointer transition-all duration-300 hover:scale-[1.02] active:scale-[0.99] flex flex-col items-center justify-between"
          >
            <!-- Checkmark Badge on Top Right -->
            <div id="badgeMale" class="absolute top-3.5 right-3.5 w-7 h-7 rounded-full bg-[#00c9a7] text-white flex items-center justify-center shadow-md">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>

            <!-- Avatar Image: Boy waving -->
            <div class="w-44 h-36 flex items-center justify-center my-1">
              <img src="{{ asset('assets/avatar_boy.png') }}" alt="Laki-laki" class="h-full object-contain">
            </div>

            <!-- Pill Button -->
            <div id="btnPillMale" class="w-full mt-3 py-2.5 px-4 rounded-full bg-gradient-to-r from-[#009b67] to-[#00b2a9] text-white font-extrabold text-xs sm:text-sm flex items-center justify-center space-x-2 shadow-md shadow-teal-600/25 transition-all">
              <span class="text-base font-bold">♂</span>
              <span>|</span>
              <span>Laki-laki</span>
            </div>
          </div>

          <!-- Card Perempuan -->
          <div 
            onclick="selectGender('Perempuan')" 
            id="cardFemale"
            class="relative group bg-white rounded-3xl p-5 sm:p-6 border-2 border-gray-100 shadow-sm hover:border-teal-200 hover:shadow-lg cursor-pointer transition-all duration-300 hover:scale-[1.02] active:scale-[0.99] flex flex-col items-center justify-between"
          >
            <!-- Checkmark Badge on Top Right -->
            <div id="badgeFemale" class="hidden absolute top-3.5 right-3.5 w-7 h-7 rounded-full bg-[#00c9a7] text-white flex items-center justify-center shadow-md">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>

            <!-- Avatar Image: Girl waving -->
            <div class="w-44 h-36 flex items-center justify-center my-1">
              <img src="{{ asset('assets/avatar_girl.png') }}" alt="Perempuan" class="h-full object-contain">
            </div>

            <!-- Pill Button -->
            <div id="btnPillFemale" class="w-full mt-3 py-2.5 px-4 rounded-full bg-gradient-to-r from-[#f0f4ff] to-[#f8f9ff] border border-[#dbe4ff] text-[#1e3a8a] font-extrabold text-xs sm:text-sm flex items-center justify-center space-x-2 transition-all">
              <span class="text-base font-bold">♀</span>
              <span>|</span>
              <span>Perempuan</span>
            </div>
          </div>

        </div>

        <!-- 5. BOTTOM DISCLAIMER PILL BAR -->
        <div class="max-w-2xl mx-auto pt-1">
          <div class="flex items-center justify-between px-5 py-2.5 sm:py-3 bg-white/90 backdrop-blur-sm border border-teal-100 rounded-full text-xs text-gray-700 shadow-xs">
            <div class="flex items-center space-x-3">
              <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-[#0085d0] text-white flex items-center justify-center font-bold text-xs italic flex-shrink-0">
                i
              </div>
              <p class="font-medium text-gray-600 text-[11px] sm:text-xs">
                Informasi ini digunakan untuk membantu perhitungan model AI, bukan untuk memastikan diagnosis.
              </p>
            </div>
            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-[#00c9a7] text-white flex items-center justify-center shadow-xs flex-shrink-0 ml-2">
              <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
          </div>
        </div>

      </section>

      <!-- ========================================================== -->
      <!-- STEP 2: USIA, TINGGI, DAN BERAT BADAN                     -->
      <!-- ========================================================== -->
      <section id="step2Section" class="step-section hidden space-y-6 max-w-2xl mx-auto">
        
        <div class="text-center space-y-2">
          <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0f2942] tracking-tight">
            Berapa usia, tinggi, dan berat badan Anda?
          </h2>
          <p class="text-gray-500 text-xs sm:text-sm">
            Langkah 2 dari 3: Data diperlukan untuk menghitung Indeks Massa Tubuh (BMI).
          </p>
        </div>

        <div class="bg-white rounded-[28px] p-6 sm:p-8 border border-gray-100 shadow-xl space-y-5">
          
          <!-- Usia -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Usia</label>
            <div class="relative flex items-center">
              <input 
                type="number" 
                id="inputAge" 
                name="age" 
                value="30" 
                min="10" 
                max="120"
                oninput="validateStep2()"
                class="w-full text-lg font-bold text-gray-800 bg-gray-50/70 border border-gray-200 rounded-2xl py-3.5 px-4 focus:bg-white focus:border-[#00BFA6] focus:ring-1 focus:ring-[#00BFA6] outline-none"
              >
              <span class="absolute right-4 text-gray-400 font-bold text-sm pointer-events-none">Thn</span>
            </div>
          </div>

          <!-- Tinggi Badan -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Tinggi Badan</label>
            <div class="relative flex items-center">
              <input 
                type="number" 
                id="inputHeight" 
                name="height" 
                value="170" 
                min="50" 
                max="250"
                oninput="validateStep2()"
                class="w-full text-lg font-bold text-gray-800 bg-gray-50/70 border border-gray-200 rounded-2xl py-3.5 px-4 focus:bg-white focus:border-[#00BFA6] focus:ring-1 focus:ring-[#00BFA6] outline-none"
              >
              <span class="absolute right-4 text-gray-400 font-bold text-sm pointer-events-none">Cm</span>
            </div>
          </div>

          <!-- Berat Badan -->
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Berat Badan</label>
            <div class="relative flex items-center">
              <input 
                type="number" 
                id="inputWeight" 
                name="weight" 
                value="65" 
                min="20" 
                max="300"
                oninput="validateStep2()"
                class="w-full text-lg font-bold text-gray-800 bg-gray-50/70 border border-gray-200 rounded-2xl py-3.5 px-4 focus:bg-white focus:border-[#00BFA6] focus:ring-1 focus:ring-[#00BFA6] outline-none"
              >
              <span class="absolute right-4 text-gray-400 font-bold text-sm pointer-events-none">Kg</span>
            </div>
          </div>

        </div>

        <div class="flex items-center space-x-3 pt-2">
          <button 
            type="button" 
            onclick="goToStep1()" 
            class="px-6 py-3.5 border border-gray-300 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 transition-all cursor-pointer"
          >
            &larr; Kembali
          </button>
          
          <button 
            type="button" 
            id="btnStep2Continue"
            onclick="goToStep3()" 
            class="flex-1 py-3.5 bg-gradient-to-r from-[#00BFA6] to-[#168FE5] hover:opacity-95 text-white font-bold text-sm sm:text-base rounded-xl transition-all shadow-md shadow-[#00BFA6]/20 active:scale-[0.99] cursor-pointer"
          >
            Lanjutkan ke Riwayat Kesehatan &rarr;
          </button>
        </div>

      </section>

      <!-- ========================================================== -->
      <!-- STEP 3: PERTANYAAN KLINIS & RIWAYAT KESEHATAN             -->
      <!-- ========================================================== -->
      <section id="step3Section" class="step-section hidden space-y-6 max-w-2xl mx-auto">
        
        <div class="text-center space-y-2">
          <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0f2942] tracking-tight">
            Riwayat Kesehatan & Gaya Hidup
          </h2>
          <p class="text-gray-500 text-xs sm:text-sm">
            Langkah 3 dari 3: Parameter klinis untuk algoritma Support Vector Machine & XGBoost.
          </p>
        </div>

        <div class="bg-white rounded-[28px] p-6 sm:p-8 border border-gray-100 shadow-xl space-y-6">
          
          <!-- Q1: Tekanan Darah Tinggi -->
          <div class="border-b border-gray-100 pb-5">
            <label class="block text-sm font-bold text-gray-900 mb-3">
              1. Apakah Anda pernah didiagnosis memiliki tekanan darah tinggi (hipertensi)?
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="high_bp" value="0" checked class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Tidak</span>
              </label>
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="high_bp" value="1" class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Ya</span>
              </label>
            </div>
          </div>

          <!-- Q2: Riwayat Keluarga -->
          <div class="border-b border-gray-100 pb-5">
            <label class="block text-sm font-bold text-gray-900 mb-3">
              2. Apakah ada anggota keluarga inti (orang tua, saudara kandung) yang mengidap diabetes?
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="family_history" value="0" checked class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Tidak Ada</span>
              </label>
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="family_history" value="1" class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Ada (Keluarga)</span>
              </label>
            </div>
          </div>

          <!-- Q3: Aktivitas Fisik -->
          <div class="border-b border-gray-100 pb-5">
            <label class="block text-sm font-bold text-gray-900 mb-3">
              3. Apakah Anda rutin berolahraga atau beraktivitas fisik minimal 30 menit sehari?
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="phys_activity" value="1" checked class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Ya (Rutin)</span>
              </label>
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="phys_activity" value="0" class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Tidak (Jarang)</span>
              </label>
            </div>
          </div>

          <!-- Q4: Kolesterol Tinggi -->
          <div class="border-b border-gray-100 pb-5">
            <label class="block text-sm font-bold text-gray-900 mb-3">
              4. Apakah Anda memiliki riwayat kadar kolesterol tinggi?
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="high_chol" value="0" checked class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Tidak / Normal</span>
              </label>
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="high_chol" value="1" class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Ya (Tinggi)</span>
              </label>
            </div>
          </div>

          <!-- Q5: Merokok (Smoker) -->
          <div class="border-b border-gray-100 pb-5">
            <label class="block text-sm font-bold text-gray-900 mb-3">
              5. Apakah Anda memiliki kebiasaan merokok (aktif atau pernah merokok &ge; 100 batang seumur hidup)?
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="smoker" value="0" checked class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Tidak / Bukan Perokok</span>
              </label>
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="smoker" value="1" class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Ya (Perokok / Pernah)</span>
              </label>
            </div>
          </div>

          <!-- Q6: Konsumsi Sayur & Buah Harian (Fruits/Veggies) -->
          <div class="border-b border-gray-100 pb-5">
            <label class="block text-sm font-bold text-gray-900 mb-3">
              6. Apakah Anda mengonsumsi sayur dan buah secara rutin minimal 1 porsi setiap hari?
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="fruits_veggies" value="1" checked class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Ya (Rutin Harian)</span>
              </label>
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="fruits_veggies" value="0" class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Jarang / Kurang</span>
              </label>
            </div>
          </div>

          <!-- Q7: Cek Kolesterol dalam 5 Tahun (CholCheck) -->
          <div class="border-b border-gray-100 pb-5">
            <label class="block text-sm font-bold text-gray-900 mb-3">
              7. Apakah Anda pernah melakukan pemeriksaan laboratorium kolesterol dalam 5 tahun terakhir?
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="chol_check" value="1" checked class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Pernah (Dalam 5 Thn)</span>
              </label>
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="chol_check" value="0" class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Belum Pernah / Lupa</span>
              </label>
            </div>
          </div>

          <!-- Q8: Riwayat Jantung / Stroke (HeartDiseaseorAttack) -->
          <div class="border-b border-gray-100 pb-5">
            <label class="block text-sm font-bold text-gray-900 mb-3">
              8. Apakah Anda memiliki riwayat diagnosis penyakit jantung koroner atau serangan stroke?
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="heart_disease" value="0" checked class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Tidak Ada Riwayat</span>
              </label>
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="heart_disease" value="1" class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Ada (Jantung / Stroke)</span>
              </label>
            </div>
          </div>

          <!-- Q9: Riwayat Diabetes Saat Hamil (Gestational) -->
          <div id="qGestationalWrapper">
            <label class="block text-sm font-bold text-gray-900 mb-1">
              9. (Khusus Perempuan) Apakah Anda pernah didiagnosis mengalami diabetes selama masa kehamilan?
            </label>
            <p class="text-xs text-gray-400 mb-3">Jika laki-laki atau belum pernah hamil, pilih opsi "Tidak".</p>
            <div class="grid grid-cols-2 gap-3">
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="gestational_history" value="0" checked class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Tidak / Bukan Bumil</span>
              </label>
              <label class="flex items-center p-3.5 border rounded-xl cursor-pointer hover:bg-[#EAF7FF]/60 transition-colors">
                <input type="radio" name="gestational_history" value="1" class="text-[#00BFA6] focus:ring-[#00BFA6]">
                <span class="ml-2.5 text-sm font-semibold text-gray-700">Ya, Pernah Gestasional</span>
              </label>
            </div>
          </div>

        </div>

        <div class="flex items-center space-x-3 pt-2">
          <button 
            type="button" 
            onclick="goToStep2()" 
            class="px-6 py-3.5 border border-gray-300 text-gray-700 font-bold text-sm rounded-xl hover:bg-gray-50 transition-all cursor-pointer"
          >
            &larr; Kembali
          </button>
          
          <button 
            type="submit" 
            id="btnSubmitAssessment"
            class="flex-1 py-3.5 bg-gradient-to-r from-[#00BFA6] to-[#168FE5] hover:opacity-95 text-white font-extrabold text-sm sm:text-base rounded-xl transition-all shadow-md shadow-[#00BFA6]/25 active:scale-[0.99] flex items-center justify-center space-x-2 cursor-pointer"
          >
            <span>🩺 Analisis Risiko Sekarang</span>
          </button>
        </div>

      </section>

    </form>

  </div>
</div>
@endsection

@push('scripts')
<script>
  let selectedGender = 'Laki-laki';

  function selectGender(gender) {
    selectedGender = gender;
    document.getElementById('selectedGenderInput').value = gender;

    const cardMale = document.getElementById('cardMale');
    const cardFemale = document.getElementById('cardFemale');
    const badgeMale = document.getElementById('badgeMale');
    const badgeFemale = document.getElementById('badgeFemale');
    const btnPillMale = document.getElementById('btnPillMale');
    const btnPillFemale = document.getElementById('btnPillFemale');

    const qGest = document.getElementById('qGestationalWrapper');

    if (gender === 'Laki-laki') {
      cardMale.className = 'relative group bg-white rounded-3xl p-5 sm:p-6 border-2 border-[#00BFA6] ring-4 ring-[#00BFA6]/15 shadow-xl shadow-teal-500/10 cursor-pointer transition-all duration-300 hover:scale-[1.02] active:scale-[0.99] flex flex-col items-center justify-between';
      cardFemale.className = 'relative group bg-white rounded-3xl p-5 sm:p-6 border-2 border-gray-100 shadow-sm hover:border-[#00BFA6]/30 hover:shadow-lg cursor-pointer transition-all duration-300 hover:scale-[1.02] active:scale-[0.99] flex flex-col items-center justify-between';
      
      badgeMale.classList.remove('hidden');
      badgeFemale.classList.add('hidden');

      btnPillMale.className = 'w-full mt-3 py-2.5 px-4 rounded-full bg-gradient-to-r from-[#00BFA6] to-[#168FE5] text-white font-extrabold text-xs sm:text-sm flex items-center justify-center space-x-2 shadow-md shadow-[#00BFA6]/25 transition-all';
      btnPillFemale.className = 'w-full mt-3 py-2.5 px-4 rounded-full bg-gradient-to-r from-[#EAF7FF] to-[#f8f9ff] border border-[#dbe4ff] text-[#123B5D] font-extrabold text-xs sm:text-sm flex items-center justify-center space-x-2 transition-all';

      if (qGest) {
        qGest.style.display = 'none';
        const rad0 = document.querySelector('input[name="gestational_history"][value="0"]');
        if (rad0) rad0.checked = true;
      }
    } else {
      cardFemale.className = 'relative group bg-white rounded-3xl p-5 sm:p-6 border-2 border-[#00BFA6] ring-4 ring-[#00BFA6]/15 shadow-xl shadow-teal-500/10 cursor-pointer transition-all duration-300 hover:scale-[1.02] active:scale-[0.99] flex flex-col items-center justify-between';
      cardMale.className = 'relative group bg-white rounded-3xl p-5 sm:p-6 border-2 border-gray-100 shadow-sm hover:border-[#00BFA6]/30 hover:shadow-lg cursor-pointer transition-all duration-300 hover:scale-[1.02] active:scale-[0.99] flex flex-col items-center justify-between';
      
      badgeFemale.classList.remove('hidden');
      badgeMale.classList.add('hidden');

      btnPillFemale.className = 'w-full mt-3 py-2.5 px-4 rounded-full bg-gradient-to-r from-[#00BFA6] to-[#168FE5] text-white font-extrabold text-xs sm:text-sm flex items-center justify-center space-x-2 shadow-md shadow-[#00BFA6]/25 transition-all';
      btnPillMale.className = 'w-full mt-3 py-2.5 px-4 rounded-full bg-gradient-to-r from-[#EAF7FF] to-[#f8f9ff] border border-[#dbe4ff] text-[#123B5D] font-extrabold text-xs sm:text-sm flex items-center justify-center space-x-2 transition-all';

      if (qGest) {
        qGest.style.display = 'block';
      }
    }

    // Auto-advance to Step 2 smoothly
    setTimeout(() => {
      goToStep2();
    }, 380);
  }

  // Set default display for gestational question (hidden for default male)
  document.addEventListener('DOMContentLoaded', function() {
    const qGest = document.getElementById('qGestationalWrapper');
    if (qGest) qGest.style.display = 'none';
  });

  function updateStepper(step) {
    const p1 = document.getElementById('stepPill1');
    const p2 = document.getElementById('stepPill2');
    const p3 = document.getElementById('stepPill3');
    const line = document.getElementById('stepperLineProgress');

    const activeClass = 'flex items-center space-x-1 sm:space-x-2 px-2.5 sm:px-3.5 py-1 sm:py-1.5 bg-gradient-to-r from-[#00BFA6] to-[#168FE5] text-white rounded-full text-[10px] sm:text-xs font-bold shadow-md shadow-[#00BFA6]/20';
    const inactiveClass = 'flex items-center space-x-1 sm:space-x-2 px-2.5 sm:px-3.5 py-1 sm:py-1.5 bg-white text-gray-500 border border-gray-200/80 rounded-full text-[10px] sm:text-xs font-semibold shadow-xs';

    if (step === 1) {
      if (p1) p1.className = activeClass;
      if (p2) p2.className = inactiveClass;
      if (p3) p3.className = inactiveClass;
      if (line) line.style.width = '15%';
    } else if (step === 2) {
      if (p1) p1.className = activeClass;
      if (p2) p2.className = activeClass;
      if (p3) p3.className = inactiveClass;
      if (line) line.style.width = '55%';
    } else if (step === 3) {
      if (p1) p1.className = activeClass;
      if (p2) p2.className = activeClass;
      if (p3) p3.className = activeClass;
      if (line) line.style.width = '100%';
    }
  }

  function showSection(sectionId) {
    const sections = ['step1Section', 'step2Section', 'step3Section'];
    sections.forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        if (id === sectionId) {
          el.classList.remove('hidden');
        } else {
          el.classList.add('hidden');
        }
      }
    });

    if (sectionId === 'step1Section') updateStepper(1);
    if (sectionId === 'step2Section') updateStepper(2);
    if (sectionId === 'step3Section') updateStepper(3);

    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function goToStep1() { showSection('step1Section'); }
  function goToStep2() { showSection('step2Section'); validateStep2(); }
  function goToStep3() { showSection('step3Section'); }

  function validateStep2() {
    const age = parseFloat(document.getElementById('inputAge').value);
    const height = parseFloat(document.getElementById('inputHeight').value);
    const weight = parseFloat(document.getElementById('inputWeight').value);
    const btn = document.getElementById('btnStep2Continue');

    if (age > 0 && height > 0 && weight > 0) {
      btn.disabled = false;
      btn.className = 'flex-1 py-3.5 bg-[#00a870] hover:bg-[#008f5d] text-white font-bold text-sm sm:text-base rounded-xl transition-all shadow-md cursor-pointer';
    } else {
      btn.disabled = true;
      btn.className = 'flex-1 py-3.5 bg-gray-100 text-gray-400 font-bold text-sm sm:text-base rounded-xl cursor-not-allowed';
    }
  }
</script>
@endpush

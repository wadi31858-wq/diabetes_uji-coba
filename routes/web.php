<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiabetesController;
use App\Http\Controllers\AdminController;

// 1. Landing Page (Diabetes Uji Coba)
Route::get('/', [DiabetesController::class, 'index'])->name('home');

// 1b. Pusat Berita & Artikel Kesehatan
Route::get('/berita-artikel', [DiabetesController::class, 'articles'])->name('articles');
Route::get('/berita-artikel/{slug}', [DiabetesController::class, 'articleDetail'])->name('articles.detail');

// 1c. Halaman Tentang Website
Route::get('/tentang-website', [DiabetesController::class, 'about'])->name('about');

// 1d. Halaman Pembuka / Splash Screen Selamat Datang
Route::get('/welcome', [DiabetesController::class, 'welcome'])->name('welcome');
Route::get('/splash', [DiabetesController::class, 'welcome'])->name('splash');

// 2. Kuesioner Cek Risiko Diabetes Langkah Demi Langkah
Route::get('/cek-risiko', [DiabetesController::class, 'quiz'])->name('quiz');

// 3. Endpoint Prediksi XGBoost via Python CLI Bridge
Route::post('/predict', [DiabetesController::class, 'predict'])->name('predict');

// 4. Halaman Hasil Pemeriksaan Risiko Diabetes
Route::get('/hasil', [DiabetesController::class, 'result'])->name('result');

// 5. Pengalihan Rute Streamlit (Dinonaktifkan demi performa & kesederhanaan sistem)
Route::redirect('/streamlit', '/')->name('streamlit');

// 6. Riwayat Data Skrining / Audit (Khusus Admin Terautentikasi)
Route::get('/riwayat', [DiabetesController::class, 'history'])->middleware(['auth', 'admin'])->name('history');

// 7. Agentic AI Health Co-Pilot Endpoints (Dengan Perlindungan Rate Limiting)
Route::middleware(['throttle:60,1'])->group(function () {
    Route::post('/agent/chat', [DiabetesController::class, 'agentChat'])->name('agent.chat');
    Route::post('/agent/quick-action', [DiabetesController::class, 'agentQuickAction'])->name('agent.quick_action');
    Route::post('/agent/upload-file', [DiabetesController::class, 'agentUploadFile'])->name('agent.upload_file');
});
Route::get('/laporan-medis/{id?}', [DiabetesController::class, 'exportReport'])->name('agent.export_report');

// 8. Autentikasi Masuk & Keluar Pengguna / Admin (Sandi & Verifikasi WhatsApp)
Route::get('/login', [DiabetesController::class, 'login'])->name('login');
Route::post('/login', [DiabetesController::class, 'doLogin'])->name('login.post');
Route::post('/login/whatsapp/request', [DiabetesController::class, 'requestWhatsAppOtp'])->name('login.whatsapp.request');
Route::post('/login/whatsapp/verify', [DiabetesController::class, 'verifyWhatsAppOtp'])->name('login.whatsapp.verify');
Route::post('/logout', [DiabetesController::class, 'logout'])->name('logout');

// 9. Panel Pengelolaan Admin (CRUD Berita, Konten Beranda & Riwayat)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kelola Berita / Artikel (Tambah, Edit, Hapus)
    Route::get('/articles', [AdminController::class, 'articlesIndex'])->name('articles.index');
    Route::get('/articles/create', [AdminController::class, 'articleCreate'])->name('articles.create');
    Route::post('/articles', [AdminController::class, 'articleStore'])->name('articles.store');
    Route::get('/articles/{id}/edit', [AdminController::class, 'articleEdit'])->name('articles.edit');
    Route::put('/articles/{id}', [AdminController::class, 'articleUpdate'])->name('articles.update');
    Route::delete('/articles/{id}', [AdminController::class, 'articleDestroy'])->name('articles.destroy');
    Route::post('/articles/{id}/delete', [AdminController::class, 'articleDestroy'])->name('articles.destroy.post');

    // Kelola Konten Tampilan & Teks Website (CMS)
    Route::get('/content', [AdminController::class, 'contentIndex'])->name('content.index');
    Route::post('/content', [AdminController::class, 'contentUpdate'])->name('content.update');
    Route::post('/hero/reset', [AdminController::class, 'heroReset'])->name('hero.reset');

    // Pengaturan Logo Website
    Route::get('/logo', [AdminController::class, 'logoIndex'])->name('logo.index');
    Route::post('/logo', [AdminController::class, 'logoUpdate'])->name('logo.update');
    Route::post('/logo/reset', [AdminController::class, 'logoReset'])->name('logo.reset');

    // Hapus Rekam Riwayat Pemeriksaan
    Route::delete('/assessment/{id}', [AdminController::class, 'assessmentDestroy'])->name('assessment.destroy');
});

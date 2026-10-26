# 🩺 Diabetes AI Analyzer (Laravel 13 & Streamlit ML Integration)
### Sistem Deteksi Dini Risiko Diabetes Berbasis Laravel & Extreme Gradient Boosting (XGBoost)

**Diabetes AI Analyzer** adalah aplikasi web klinis modern berbasis **Laravel 13** dan **Python Machine Learning (XGBoost)**. Aplikasi ini menyediakan antarmuka publik bergaya resmi KlikDokter (*Cegah Diabetes*) yang terhubung langsung dengan model prediksi XGBoost 21-indikator CDC BRFSS dan dashboard analitik mendalam **Streamlit**.

---

## 🌟 Arsitektur Sistem

1. **Frontend & Backend Web (Laravel 13 - Port 8000):**
   - **Blade Views:** Antarmuka modern dan responsif menggunakan Tailwind CSS.
   - **Kuesioner Interaktif (`/cek-risiko`):** Langkah 1 (Gender), Langkah 2 (BMI), Langkah 3 (Klinis).
   - **Halaman Hasil (`/hasil`):** Menampilkan banner glucometer asli, indikator 3 warna (Rendah/Sedang/Tinggi), dan 4 anjuran klinis resmi.
   - **🤖 Health Co-Pilot AI (Agentic Clinical Assistant):** Asisten AI cerdas berbasis penalaran klinis (*ReAct pattern*) yang terhubung langsung dengan parameter biometrik pengguna, menyediakan konsultasi nutrisi 7 hari, kalkulator BMR & kalori harian, anjuran lab lanjutan (HbA1c/GDP), serta dukungan Google Gemini API / offline reasoning engine.
   - **Penyimpanan Riwayat (`/riwayat`):** SQLite database menyimpan setiap skrining secara aman.
   - **Streamlit AI Hub (`/streamlit`):** Menyematkan dashboard Streamlit interaktif secara langsung.

2. **Mesin Prediksi (Python CLI Bridge - `predict_cli.py`):**
   - Laravel memanggil `predict_cli.py` di latar belakang dengan data JSON.
   - Mengevaluasi model XGBoost Classifier di `model_diabetes_xgboost.pkl`.
   - Mengembalikan hasil JSON ke Laravel secara instan.

3. **Dashboard Analitik Lanjutan (Streamlit - Port 8501):**
   - Eksplorasi dataset CDC BRFSS (253.680 sampel).
   - Manajemen model, Confusion Matrix, dan penyesuaian bobot fitur.

---

## 🚀 Panduan Menjalankan

### 1. Menjalankan Laravel
```bash
php artisan serve --port=8000
```
Buka di browser: `http://127.0.0.1:8000`

### 2. Menjalankan Streamlit (Opsional untuk Analitik)
```bash
streamlit run app.py --server.headless true
```
Buka di browser: `http://localhost:8501` atau melalui menu **Streamlit AI Hub** di Laravel.

---

## 📁 Struktur Folder Proyek

```text
diabetes/
├── app/
│   ├── Http/Controllers/DiabetesController.php  # Controller logika Laravel
│   └── Models/Assessment.php                    # Model riwayat skrining
├── database/                                    # Migrasi & SQLite database Laravel
├── resources/views/                             # Template Blade (Landing, Quiz, Result, Streamlit, History)
│   ├── layouts/app.blade.php                    # Master layout (Header KlikDokter & Footer)
│   ├── landing.blade.php                        # Beranda "Cegah Diabetes"
│   ├── quiz.blade.php                           # Kuesioner 3-langkah (Gender, BMI, Klinis)
│   ├── result.blade.php                         # Halaman hasil persis tangkapan layar
│   ├── streamlit.blade.php                      # Hub terintegrasi dashboard Streamlit
│   └── history.blade.php                        # Tabel riwayat & audit skrining
├── routes/web.php                               # Definisi route Laravel
├── public/assets/                               # Asset gambar banner glucometer
├── predict_cli.py                               # Jembatan CLI prediksi Python XGBoost
├── models/                                      # Metadata evaluasi & konfigurasi model
├── data/                                        # Dataset CDC BRFSS & SQLite
├── pages/                                       # Modul halaman Streamlit (Admin & Analitik)
├── .streamlit/                                  # Konfigurasi tema Streamlit
├── app.py                                       # Entrypoint utama aplikasi Streamlit
├── train_model.py                               # Script pelatihan model XGBoost terstratifikasi
├── requirements.txt                             # Dependensi Python
└── composer.json                                # Dependensi PHP & Laravel
```

---

## ⚙️ 21 Indikator Kesehatan yang Digunakan

| No | Nama Fitur | Keterangan & Karakteristik Input |
|---|---|---|
| 1 | `HighBP` | Tekanan darah tinggi / hipertensi (0 = Tidak, 1 = Ya) |
| 2 | `HighChol` | Kolesterol tinggi (0 = Tidak, 1 = Ya) |
| 3 | `CholCheck` | Cek kolesterol dalam 5 tahun terakhir (0 = Tidak, 1 = Ya) |
| 4 | `BMI` | Indeks Massa Tubuh (Numerik 12.0 - 98.0 kg/m²) |
| 5 | `Smoker` | Pernah merokok minimal 100 batang seumur hidup (0 = Tidak, 1 = Ya) |
| 6 | `Stroke` | Riwayat penyakit stroke (0 = Tidak, 1 = Ya) |
| 7 | `HeartDiseaseorAttack` | Riwayat penyakit jantung koroner / infark miokard (0 = Tidak, 1 = Ya) |
| 8 | `PhysActivity` | Aktivitas fisik rutin dalam 30 hari terakhir (0 = Tidak, 1 = Ya) |
| 9 | `Fruits` | Konsumsi buah minimal 1x per hari (0 = Tidak, 1 = Ya) |
| 10 | `Veggies` | Konsumsi sayur minimal 1x per hari (0 = Tidak, 1 = Ya) |
| 11 | `HvyAlcoholConsump` | Konsumsi alkohol berat (0 = Tidak, 1 = Ya) |
| 12 | `AnyHealthcare` | Memiliki jaminan kesehatan / BPJS (0 = Tidak, 1 = Ya) |
| 13 | `NoDocbcCost` | Terkendala biaya dokter dlm 12 bulan terakhir (0 = Tidak, 1 = Ya) |
| 14 | `GenHlth` | Penilaian kondisi kesehatan umum (Skala 1 = Sangat Sehat s.d. 5 = Buruk) |
| 15 | `MentHlth` | Hari stres / gangguan kesehatan mental dlm 30 hari (0 - 30 hari) |
| 16 | `PhysHlth` | Hari sakit fisik / cedera dlm 30 hari (0 - 30 hari) |
| 17 | `DiffWalk` | Kesulitan berjalan atau menaiki tangga (0 = Tidak, 1 = Ya) |
| 18 | `Sex` | Jenis kelamin biologis (0 = Wanita, 1 = Pria) |
| 19 | `Age` | Kategori usia CDC (1 = 18-24 thn, ..., 13 = 80+ thn) |
| 20 | `Education` | Tingkat pendidikan formal (Skala 1 - 6) |
| 21 | `Income` | Kategori rentang pendapatan rumah tangga (Skala 1 - 8) |

---

## 🚀 Panduan Menjalankan di VS Code

### 1. Prasyarat Sistem
- **Python:** Versi 3.9, 3.10, atau 3.11 (Direkomendasikan Python 3.10).
- **VS Code:** Pastikan ekstensi *Python* dari Microsoft telah terpasang.

### 2. Membuka Proyek di Terminal
Buka folder proyek di VS Code, lalu buka Terminal (`Ctrl + ~`):

```bash
# Buat Virtual Environment (opsional namun disarankan)
python -m venv .venv

# Aktifkan Virtual Environment di Windows (PowerShell):
.venv\Scripts\Activate.ps1

# Instal seluruh dependensi:
pip install -r requirements.txt
```

### 3. Melatih Model Machine Learning
Sebelum menjalankan aplikasi web untuk pertama kali, latih model XGBoost dan buat metadata evaluasi:

```bash
python train_model.py
```
*Proses pelatihan memakan waktu singkat dan akan menghasilkan file `model_diabetes_xgboost.pkl` serta `models/metadata.json`.*

### 4. Menjalankan Website
Jalankan server aplikasi Streamlit:

```bash
streamlit run app.py
```
Aplikasi akan otomatis terbuka di browser pada alamat:
`http://localhost:8501`

---

## 🔑 Kredensial Administrator Default

Saat database pertama kali diinisialisasi, sistem secara otomatis membuat akun admin default:
- **Username:** `admin`
- **Kata Sandi:** `Admin123!`

> 💡 **Penting:** Anda dapat mengubah kata sandi ini kapan saja setelah login melalui menu **Pengaturan Sistem -> Keamanan & Akun**.

---

## 🧪 Panduan Pengujian Fitur

### A. Pengujian Pengunjung:
1. Buka halaman **Prediksi Risiko**.
2. Masukkan parameter kesehatan normal (misal BMI 21.0, tidak merokok, aktif bergerak, konsumsi buah & sayur). Klik tombol **Proses Analisis Risiko XGBoost**. Hasil akan menunjukkan **Risiko Rendah**.
3. Coba masukkan parameter komorbid (misal BMI 34.0, kolesterol tinggi, riwayat penyakit jantung, usia >60 tahun, skala kesehatan 5). Hasil akan menunjukkan **Berisiko Tinggi** beserta rincian faktor perhatian dan anjuran tes laboratorium HbA1c / GDP.

### B. Pengujian Administrator:
1. Buka menu **Login Admin** dan masukkan kredensial `admin` / `Admin123!`.
2. Amati bahwa menu sidebar langsung terbuka menampilkan tab **Panel Administrator**.
3. Buka **Dashboard Admin** untuk melihat pembaruan grafik donat dan metrik prediksi secara real-time.
4. Buka **Riwayat Prediksi** untuk mencari ID prediksi, menyaring berdasarkan kelas, dan mencoba fitur ekspor CSV.
5. Buka **Manajemen Model** untuk melihat Confusion Matrix dan bobot koefisien fitur.
6. Coba uji coba keamanan: Buka jendela incognito di browser dan coba akses langsung menu admin tanpa login; sistem akan otomatis memblokir akses dan meminta autentikasi.

---

## 🛠️ Mengatasi Masalah Umum (Troubleshooting)

1. **Error: `ModuleNotFoundError: No module named 'streamlit'`**
   - Pastikan virtual environment telah diaktifkan, atau jalankan `pip install -r requirements.txt`.
2. **Error: `Model Machine Learning Belum Tersedia`**
   - Jalankan `python train_model.py` di terminal untuk melatih model terlebih dahulu.
3. **Execution Policy Error di PowerShell (`cannot be loaded because running scripts is disabled`)**
   - Jalankan perintah berikut di PowerShell Administrator:
     ```powershell
     Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
     ```
4. **Port 8501 Sedang Digunakan**
   - Jalankan pada port lain:
     ```bash
     streamlit run app.py --server.port 8502
     ```

---

## 🌐 Panduan Deployment ke Cloud

### Opsi 1: Streamlit Community Cloud (Gratis)
1. Unggah kode ke repositori **GitHub** (pastikan menyertakan `data/diabetes_health_indicators.csv`, `models/`, `pages/`, `app.py`, dan `requirements.txt`).
2. Masuk ke [share.streamlit.io](https://share.streamlit.io).
3. Sambungkan repositori GitHub Anda dan pilih file utama: `app.py`.
4. Klik **Deploy**! Website Anda akan langsung online dan memiliki URL publik HTTPS.

### Opsi 2: Mengakses dari Jaringan Lokal (HP / Laptop Lain)
Jika ingin mengakses website dari smartphone yang terhubung pada Wi-Fi yang sama:
```bash
streamlit run app.py --server.address 0.0.0.0
```
Buka browser HP dan ketik `http://[IP-Komputer-Anda]:8501` (contoh: `http://192.168.1.15:8501`).

---

## 📄 Lisensi & Hak Cipta
Aplikasi ini dikembangkan untuk tujuan penelitian akademik, pendidikan kedokteran preventif, dan portofolio data science di bawah lisensi open-source.

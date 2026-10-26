"""
Halaman Prediksi Diabetes - Diabetes AI Analyzer
Formulir input 20 indikator kesehatan, validasi, prediksi model XGBoost,
analisis faktor risiko, edukasi preventif, dan privasi pengunjung.
"""

import os
import json
import sys
import numpy as np
import pandas as pd
import joblib
import streamlit as st

_parent_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _parent_dir not in sys.path:
    sys.path.insert(0, _parent_dir)

from database import track_visit, save_prediction

MODEL_DIR = os.path.join(os.path.dirname(os.path.dirname(__file__)), 'models')
MODEL_PATH = os.path.join(MODEL_DIR, 'diabetes_model.joblib')
METADATA_PATH = os.path.join(MODEL_DIR, 'metadata.json')

FEATURES = [
    'HighBP', 'HighChol', 'CholCheck', 'BMI', 'Smoker', 'Stroke', 'HeartDiseaseorAttack',
    'PhysActivity', 'Fruits', 'Veggies', 'HvyAlcoholConsump', 'AnyHealthcare',
    'NoDocbcCost', 'GenHlth', 'MentHlth', 'PhysHlth', 'DiffWalk', 'Sex', 'Age',
    'Education', 'Income'
]


@st.cache_resource
def load_trained_model():
    """Memuat model XGBoost dan metadata dari disk."""
    base_dir = os.path.dirname(os.path.dirname(__file__))
    candidates = [
        os.path.join(base_dir, 'model_diabetes_xgboost.pkl'),
        os.path.join(base_dir, 'models', 'diabetes_model.joblib'),
    ]
    model_file = None
    for c in candidates:
        if os.path.exists(c):
            model_file = c
            break
    if not model_file:
        return None, None
    try:
        model = joblib.load(model_file)
        metadata = {}
        if os.path.exists(METADATA_PATH):
            with open(METADATA_PATH, 'r', encoding='utf-8') as f:
                metadata = json.load(f)
        return model, metadata
    except Exception as e:
        st.error(f"Gagal memuat model machine learning: {e}")
        return None, None


def render_prediction_page():
    track_visit("Prediksi")

    st.markdown("""
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 2rem; font-weight: 800; color: #FFFFFF; margin-bottom: 0.3rem;">
            🔍 Analisis Skrining Risiko Diabetes
        </h1>
        <p style="color: var(--text-muted); font-size: 1rem;">
            Isi 21 indikator kesehatan di bawah ini dengan jujur untuk mendapatkan estimasi risiko 
            berdasarkan model Extreme Gradient Boosting (XGBoost).
        </p>
    </div>
    """, unsafe_allow_html=True)

    # Cek ketersediaan model
    model, metadata = load_trained_model()
    if model is None:
        st.error("⚠️ **Model Machine Learning Belum Tersedia di Sistem.**")
        st.info("Administrator perlu melatih model terlebih dahulu melalui script `train_model.py` atau dashboard admin sebelum fitur prediksi dapat digunakan.")
        return

    # Banner Privasi & Anonimitas
    st.markdown("""
    <div class="medical-disclaimer" style="margin-top: 0; margin-bottom: 1.5rem;">
        🔒 <strong>Jaminan Privasi Pengunjung:</strong> Sistem ini tidak meminta dan tidak menyimpan data identitas 
        pribadi seperti Nama, NIK, Nomor HP, atau Alamat Rumah. Seluruh analisis bersifat anonim.
    </div>
    """, unsafe_allow_html=True)

    # Form Pengisian Data
    with st.form("prediction_form"):
        # TAB 1: Antropometri & Demografi
        st.markdown("#### 1. Data Demografi & Fisik")
        col_f1, col_f2 = st.columns(2)

        with col_f1:
            sex_choice = st.radio(
                "Jenis Kelamin",
                options=["Wanita", "Pria"],
                index=0,
                help="Berdasarkan jenis kelamin biologis."
            )
            sex_val = 1 if sex_choice == "Pria" else 0

            age_options = {
                "18 - 24 tahun": 1,
                "25 - 29 tahun": 2,
                "30 - 34 tahun": 3,
                "35 - 39 tahun": 4,
                "40 - 44 tahun": 5,
                "45 - 49 tahun": 6,
                "50 - 54 tahun": 7,
                "55 - 59 tahun": 8,
                "60 - 64 tahun": 9,
                "65 - 69 tahun": 10,
                "70 - 74 tahun": 11,
                "75 - 79 tahun": 12,
                "80 tahun ke atas": 13
            }
            age_label = st.selectbox(
                "Kategori Usia",
                options=list(age_options.keys()),
                index=4,
                help="Pilih rentang usia Anda saat ini."
            )
            age_val = age_options[age_label]

        with col_f2:
            st.markdown("**Indeks Massa Tubuh (BMI)**")
            use_calc = st.checkbox("Bantu hitung BMI dari Berat & Tinggi Badan", value=False)
            if use_calc:
                c_tb, c_bb = st.columns(2)
                with c_tb:
                    tb = st.number_input("Tinggi Badan (cm)", min_value=100.0, max_value=230.0, value=165.0, step=0.5)
                with c_bb:
                    bb = st.number_input("Berat Badan (kg)", min_value=30.0, max_value=250.0, value=65.0, step=0.5)
                calculated_bmi = round(bb / ((tb / 100) ** 2), 1)
                st.info(f"💡 Estimasi Nilai BMI Anda: **{calculated_bmi}**")
                bmi_val = calculated_bmi
            else:
                bmi_val = st.number_input(
                    "Nilai BMI (Indeks Massa Tubuh)",
                    min_value=12.0,
                    max_value=98.0,
                    value=25.0,
                    step=0.1,
                    help="Rentang normal Asia Pasifik: 18.5 - 22.9. Overweight: 23 - 24.9. Obesitas: >= 25."
                )

        st.markdown("---")

        # TAB 2: Riwayat Kesehatan & Kardiovaskular
        st.markdown("#### 2. Riwayat Kesehatan & Kondisi Kardiovaskular")
        col_m1, col_m2 = st.columns(2)

        with col_m1:
            high_bp_choice = st.radio(
                "Apakah Anda pernah didiagnosis memiliki Tekanan Darah Tinggi (Hipertensi)?",
                options=["Tidak", "Ya"],
                index=0
            )
            high_bp_val = 1 if high_bp_choice == "Ya" else 0

            high_chol_choice = st.radio(
                "Apakah Anda pernah didiagnosis memiliki Kolesterol Tinggi?",
                options=["Tidak", "Ya"],
                index=0
            )
            high_chol_val = 1 if high_chol_choice == "Ya" else 0

            chol_check_choice = st.radio(
                "Apakah Anda pernah cek kolesterol dalam 5 tahun terakhir?",
                options=["Tidak", "Ya"],
                index=1
            )
            chol_check_val = 1 if chol_check_choice == "Ya" else 0

            heart_disease_choice = st.radio(
                "Riwayat Penyakit Jantung Koroner / Serangan Jantung?",
                options=["Tidak", "Ya"],
                index=0
            )
            heart_disease_val = 1 if heart_disease_choice == "Ya" else 0

        with col_m2:
            stroke_choice = st.radio(
                "Apakah Anda memiliki riwayat penyakit Stroke?",
                options=["Tidak", "Ya"],
                index=0
            )
            stroke_val = 1 if stroke_choice == "Ya" else 0

            diff_walk_choice = st.radio(
                "Apakah Anda mengalami kesulitan serius saat berjalan atau menaiki tangga?",
                options=["Tidak", "Ya"],
                index=0
            )
            diff_walk_val = 1 if diff_walk_choice == "Ya" else 0

            gen_hlth_options = {
                "1 - Sangat Sehat (Excellent)": 1,
                "2 - Sangat Baik (Very Good)": 2,
                "3 - Baik (Good)": 3,
                "4 - Cukup (Fair)": 4,
                "5 - Kurang / Buruk (Poor)": 5
            }
            gen_hlth_label = st.selectbox(
                "Secara umum, bagaimana Anda menilai kondisi kesehatan Anda?",
                options=list(gen_hlth_options.keys()),
                index=1
            )
            gen_hlth_val = gen_hlth_options[gen_hlth_label]

        st.markdown("---")

        # TAB 3: Gaya Hidup & Kebiasaan Sehari-hari
        st.markdown("#### 3. Gaya Hidup & Kebiasaan Sehari-hari")
        col_l1, col_l2 = st.columns(2)

        with col_l1:
            smoker_choice = st.radio(
                "Apakah Anda pernah merokok minimal 100 batang seumur hidup?",
                options=["Tidak", "Ya"],
                index=0
            )
            smoker_val = 1 if smoker_choice == "Ya" else 0

            phys_act_choice = st.radio(
                "Aktivitas fisik / olahraga di luar pekerjaan dalam 30 hari terakhir?",
                options=["Tidak", "Ya"],
                index=1
            )
            phys_act_val = 1 if phys_act_choice == "Ya" else 0

            hvy_alcohol_choice = st.radio(
                "Konsumsi alkohol berlebih (Pria >14 minuman/minggu, Wanita >7/minggu)?",
                options=["Tidak", "Ya"],
                index=0
            )
            hvy_alcohol_val = 1 if hvy_alcohol_choice == "Ya" else 0

        with col_l2:
            fruits_choice = st.radio(
                "Rutin mengonsumsi buah minimal 1 kali setiap hari?",
                options=["Tidak", "Ya"],
                index=1
            )
            fruits_val = 1 if fruits_choice == "Ya" else 0

            veggies_choice = st.radio(
                "Rutin mengonsumsi sayuran minimal 1 kali setiap hari?",
                options=["Tidak", "Ya"],
                index=1
            )
            veggies_val = 1 if veggies_choice == "Ya" else 0

        st.markdown("---")

        # TAB 4: Hari Sakit & Akses Kesehatan
        st.markdown("#### 4. Kebugaran Fisik, Mental, dan Sosial")
        col_s1, col_s2 = st.columns(2)

        with col_s1:
            phys_hlth_val = st.slider(
                "Berapa hari kondisi kesehatan fisik Anda kurang baik dalam 30 hari terakhir?",
                min_value=0,
                max_value=30,
                value=0,
                help="Termasuk sakit fisik, cedera, kelelahan parah (0 - 30 hari)."
            )

            ment_hlth_val = st.slider(
                "Berapa hari kondisi kesehatan mental/stres kurang baik dalam 30 hari terakhir?",
                min_value=0,
                max_value=30,
                value=0,
                help="Termasuk stres, depresi, atau masalah emosional (0 - 30 hari)."
            )

            healthcare_choice = st.radio(
                "Apakah Anda memiliki jaminan kesehatan (BPJS / Asuransi)?",
                options=["Tidak", "Ya"],
                index=1
            )
            healthcare_val = 1 if healthcare_choice == "Ya" else 0

        with col_s2:
            nodoc_choice = st.radio(
                "Pernah terhalang ke dokter dalam 12 bulan terakhir karena masalah biaya?",
                options=["Tidak", "Ya"],
                index=0
            )
            nodoc_val = 1 if nodoc_choice == "Ya" else 0

            edu_options = {
                "Tidak sekolah / Hanya TK": 1,
                "SD (Tingkat 1 - 8)": 2,
                "SMP / Sebagian SMA (Tingkat 9 - 11)": 3,
                "Lulus SMA / Sederajat (Tingkat 12)": 4,
                "Diploma / Kuliah 1-3 Tahun": 5,
                "Lulus Sarjana / Perguruan Tinggi (>= 4 Tahun)": 6
            }
            edu_label = st.selectbox(
                "Tingkat Pendidikan Terakhir",
                options=list(edu_options.keys()),
                index=3
            )
            edu_val = edu_options[edu_label]

            income_options = {
                "Tingkat 1 (< $10.000 / Sangat Rendah)": 1,
                "Tingkat 2 ($10.000 - $15.000 / Rendah)": 2,
                "Tingkat 3 ($15.000 - $20.000 / Rendah-Menengah)": 3,
                "Tingkat 4 ($20.000 - $25.000 / Menengah Bawah)": 4,
                "Tingkat 5 ($25.000 - $35.000 / Menengah)": 5,
                "Tingkat 6 ($35.000 - $50.000 / Menengah Atas)": 6,
                "Tingkat 7 ($50.000 - $75.000 / Tinggi)": 7,
                "Tingkat 8 (>= $75.000 / Sangat Mampu)": 8
            }
            income_label = st.selectbox(
                "Kategori Pendapatan Rumah Tangga",
                options=list(income_options.keys()),
                index=4
            )
            income_val = income_options[income_label]

        st.markdown("---")

        # Consent Checkbox
        consent = st.checkbox(
            "Saya menyetujui penyimpanan ringkasan indikator ini secara anonim untuk statistik dan penelitian sistem.",
            value=True
        )

        submit_btn = st.form_submit_button("🩺 Proses Analisis Risiko XGBoost", type="primary", use_container_width=True)

    # Ketika Tombol Submit Ditekan
    if submit_btn:
        # Validasi input
        if bmi_val < 10 or bmi_val > 100:
            st.error("Nilai BMI di luar batas fisiologis manusia yang valid (10 - 100). Harap periksa kembali.")
            return

        # Siapkan dictionary data input sesuai urutan 21 fitur model
        input_dict = {
            'HighBP': int(high_bp_val),
            'HighChol': int(high_chol_val),
            'CholCheck': int(chol_check_val),
            'BMI': float(bmi_val),
            'Smoker': int(smoker_val),
            'Stroke': int(stroke_val),
            'HeartDiseaseorAttack': int(heart_disease_val),
            'PhysActivity': int(phys_act_val),
            'Fruits': int(fruits_val),
            'Veggies': int(veggies_val),
            'HvyAlcoholConsump': int(hvy_alcohol_val),
            'AnyHealthcare': int(healthcare_val),
            'NoDocbcCost': int(nodoc_val),
            'GenHlth': int(gen_hlth_val),
            'MentHlth': int(ment_hlth_val),
            'PhysHlth': int(phys_hlth_val),
            'DiffWalk': int(diff_walk_val),
            'Sex': int(sex_val),
            'Age': int(age_val),
            'Education': int(edu_val),
            'Income': int(income_val)
        }

        # Konversi ke DataFrame dengan urutan kolom persis
        input_df = pd.DataFrame([input_dict])[FEATURES]

        with st.spinner("Model XGBoost sedang mengevaluasi 21 indikator kesehatan Anda..."):
            try:
                # Prediksi menggunakan Scikit-learn Pipeline
                prediction = int(model.predict(input_df)[0])
                label_text = "Berisiko Tinggi (Diabetes / Pre-Diabetes)" if prediction == 1 else "Risiko Rendah (Non-Diabetes)"

                # Simpan ke database jika persetujuan diberikan
                model_ver = metadata.get('model_version', '1.0.0')
                pred_id = save_prediction(input_dict, prediction, label_text, model_ver, int(consent))

            except Exception as e:
                st.error(f"Terjadi kesalahan saat memproses data: {e}")
                return

        st.markdown("---")
        st.markdown("## 📊 Hasil Analisis Risiko Kesehatan")

        if prediction == 1:
            # Tampilan Hasil: Risiko Tinggi
            st.markdown(f"""
            <div class="result-high-risk">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                    <div>
                        <div class="badge badge-risk-high" style="margin-bottom: 0.6rem; font-size: 0.9rem;">
                            ⚠️ PERINGATAN RISIKO TINGGI
                        </div>
                        <h2 style="color: #FCA5A5; margin: 0; font-size: 1.8rem; font-weight: 800;">
                            Terdeteksi Berisiko Tinggi Terhadap Diabetes
                        </h2>
                    </div>
                    <div style="font-size: 0.85rem; color: #FCA5A5; margin-top: 0.5rem;">
                        ID Ref: #{pred_id if pred_id else 'ANON'} | Versi Model: XGBoost v{model_ver}
                    </div>
                </div>
                <hr style="border-color: var(--danger-border); margin: 1rem 0;">
                <p style="color: #FECACA; font-size: 1rem; line-height: 1.6;">
                    Berdasarkan kombinasi 21 indikator kesehatan dan pola hidup yang dimasukkan, 
                    algoritma Extreme Gradient Boosting (XGBoost) mengklasifikasikan profil Anda ke dalam 
                    <strong>kelompok berisiko tinggi terhadap diabetes melitus atau kondisi pre-diabetes</strong>.
                </p>
            </div>
            """, unsafe_allow_html=True)
        else:
            # Tampilan Hasil: Risiko Rendah
            st.markdown(f"""
            <div class="result-low-risk">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap;">
                    <div>
                        <div class="badge badge-risk-low" style="margin-bottom: 0.6rem; font-size: 0.9rem;">
                            ✅ KONDISI BAIK / RISIKO RENDAH
                        </div>
                        <h2 style="color: #6EE7B7; margin: 0; font-size: 1.8rem; font-weight: 800;">
                            Risiko Rendah Terhadap Diabetes
                        </h2>
                    </div>
                    <div style="font-size: 0.85rem; color: #6EE7B7; margin-top: 0.5rem;">
                        ID Ref: #{pred_id if pred_id else 'ANON'} | Versi Model: XGBoost v{model_ver}
                    </div>
                </div>
                <hr style="border-color: var(--success-border); margin: 1rem 0;">
                <p style="color: #D1FAE5; font-size: 1rem; line-height: 1.6;">
                    Berdasarkan data yang Anda berikan, model Extreme Gradient Boosting (XGBoost) mengklasifikasikan 
                    profil Anda saat ini berada dalam <strong>kelompok risiko rendah</strong>. Pertahankan 
                    gaya hidup aktif dan pola makan bergizi seimbang.
                </p>
            </div>
            """, unsafe_allow_html=True)

        # Analisis Faktor Risiko Pengguna (Explainability)
        st.markdown("### 🔍 Identifikasi Faktor Risiko Anda")
        risk_factors_found = []
        positive_factors_found = []

        if bmi_val >= 25.0:
            risk_factors_found.append(f"Indeks Massa Tubuh (BMI = {bmi_val}) termasuk kategori kelebihan berat badan/obesitas.")
        else:
            positive_factors_found.append(f"Indeks Massa Tubuh (BMI = {bmi_val}) berada dalam rentang terkontrol.")

        if high_bp_val == 1:
            risk_factors_found.append("Memiliki riwayat tekanan darah tinggi (hipertensi).")
        else:
            positive_factors_found.append("Tekanan darah dalam batas normal (tidak hipertensi).")

        if high_chol_val == 1:
            risk_factors_found.append("Memiliki riwayat kadar kolesterol darah tinggi.")
        else:
            positive_factors_found.append("Tidak memiliki riwayat kolesterol tinggi.")

        if gen_hlth_val >= 4:
            risk_factors_found.append("Penilaian kondisi kesehatan umum berada pada level cukup atau buruk.")
        else:
            positive_factors_found.append("Penilaian kesehatan umum dalam kategori sehat/baik.")

        if age_val >= 7:
            risk_factors_found.append("Kategori usia di atas 50 tahun yang secara fisiologis lebih rentan terhadap resistensi insulin.")

        if heart_disease_val == 1 or stroke_val == 1:
            risk_factors_found.append("Memiliki riwayat penyakit kardiovaskular (jantung / stroke).")

        if phys_act_val == 0:
            risk_factors_found.append("Kurangnya aktivitas fisik teratur dalam 30 hari terakhir.")
        else:
            positive_factors_found.append("Rutin melakukan aktivitas fisik / olahraga.")

        if fruits_val == 1 and veggies_val == 1:
            positive_factors_found.append("Kebiasaan konsumsi buah dan sayur harian yang baik.")

        col_rf1, col_rf2 = st.columns(2)
        with col_rf1:
            st.markdown("##### ⚠️ Faktor Perhatian:")
            if risk_factors_found:
                for rf in risk_factors_found:
                    st.markdown(f"- 🔸 {rf}")
            else:
                st.markdown("Tidak terdeteksi faktor risiko gaya hidup mayor yang mencolok.")

        with col_rf2:
            st.markdown("##### 🛡️ Faktor Pelindung:")
            if positive_factors_found:
                for pf in positive_factors_found:
                    st.markdown(f"- 🔹 {pf}")
            else:
                st.markdown("Perlu ditingkatkan kebiasaan protektif seperti olahraga teratur dan diet sehat.")

        # Rekomendasi & Saran Medis
        st.markdown("### 💡 Rekomendasi Langkah Medis Selanjutnya")
        r1, r2, r3 = st.columns(3)
        with r1:
            st.markdown("""
            <div class="analyzer-card">
                <div class="card-title">🩺 Pemeriksaan Laboratorium</div>
                <p class="card-description">
                    Lakukan pemeriksaan <strong>HbA1c (Hemoglobin A1c)</strong> dan <strong>Gula Darah Puasa (GDP)</strong> 
                    di fasilitas kesehatan terdekat untuk mendapatkan parameter biokimiawi yang pasti.
                </p>
            </div>
            """, unsafe_allow_html=True)
        with r2:
            st.markdown("""
            <div class="analyzer-card">
                <div class="card-title">🥗 Modifikasi Pola Makan</div>
                <p class="card-description">
                    Kurangi konsumsi karbohidrat sederhana dan minuman berpemanis. Tingkatkan asupan serat dari 
                    sayuran hijau dan biji-bijian utuh untuk menstabilkan respon glukosa.
                </p>
            </div>
            """, unsafe_allow_html=True)
        with r3:
            st.markdown("""
            <div class="analyzer-card">
                <div class="card-title">🏃 Aktivitas Fisik Teratur</div>
                <p class="card-description">
                    Targetkan minimal 150 menit olahraga intensitas sedang per minggu (seperti jalan cepat 30 menit/hari) 
                    untuk meningkatkan sensitivitas insulin pada otot rangka.
                </p>
            </div>
            """, unsafe_allow_html=True)

        st.markdown("""
        <div class="medical-disclaimer">
            📌 <strong>Catatan Medis:</strong> Hasil analisis ini adalah estimasi matematis model Machine Learning 
            dan tidak menampilkan persentase probabilitas karena model linear belum melalui kalibrasi kurva probabilitas isotronik klinis. 
            Konsultasikan hasil ini secara langsung dengan dokter untuk pemeriksaan fisik dan laboratorium lengkap.
        </div>
        """, unsafe_allow_html=True)


if __name__ == '__main__':
    render_prediction_page()

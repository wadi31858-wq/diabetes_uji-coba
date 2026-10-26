"""
Halaman Beranda (Home) - Diabetes AI Analyzer
Menyajikan informasi sistem, deteksi dini, cara kerja machine learning,
tombol aksi cepat ke prediksi, dan disclaimer medis.
"""

import os
import sys
import streamlit as st

_parent_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _parent_dir not in sys.path:
    sys.path.insert(0, _parent_dir)

from database import track_visit, get_setting

def render_home_page():
    # Catat kunjungan pengunjung publik
    track_visit("Beranda")

    site_title = get_setting("site_title", "Diabetes AI Analyzer")
    developer_name = get_setting("developer_name", "Tim Data Mining & AI Healthcare")
    institution = get_setting("institution_name", "Universitas Nurul Jadid (UNUJA)")

    # Hero Section
    st.markdown(f"""
    <div class="hero-container">
        <div class="badge badge-info" style="margin-bottom: 0.8rem;">
            🔬 Clinical Machine Learning Support System
        </div>
        <h1 class="hero-title">{site_title}</h1>
        <p class="hero-subtitle">
            Platform deteksi dini risiko diabetes berbasis kecerdasan buatan menggunakan 
            algoritma <strong>Extreme Gradient Boosting (XGBoost)</strong>. Menganalisis 21 indikator kesehatan 
            dan gaya hidup berdasarkan dataset klinis terstandardisasi CDC BRFSS.
        </p>
    </div>
    """, unsafe_allow_html=True)

    # CTA Button
    col_btn, col_empty = st.columns([2, 5])
    with col_btn:
        if st.button("🚀 Mulai Analisis Risiko Sekarang", type="primary", use_container_width=True):
            try:
                target = st.session_state.get('page_pred_obj')
                if target:
                    st.switch_page(target)
                else:
                    st.switch_page("pages/prediction.py")
            except Exception:
                st.session_state['current_page'] = 'prediction'
                st.rerun()

    # Medical Disclaimer Notice
    st.markdown("""
    <div class="medical-disclaimer">
        ⚠️ <strong>Pemberitahuan Medis Penting (Disclaimer):</strong><br>
        Sistem ini dibangun untuk tujuan edukasi, penelitian akademik, dan skrining awal (deteksi dini). 
        Hasil analisis kecerdasan buatan merupakan <strong>estimasi probabilitas statistik</strong> dan 
        <strong>BUKAN pengganti diagnosis resmi dari dokter atau tenaga medis profesional</strong>. 
        Jangan mengubah rencana pengobatan atau obat-obatan tanpa berkonsultasi langsung dengan dokter spesialis Anda.
    </div>
    """, unsafe_allow_html=True)

    st.markdown("---")

    # Fitur Utama (3 Kolom Card)
    st.markdown("### 🌟 Mengapa Deteksi Dini Diabetes Sangat Krusial?")
    c1, c2, c3 = st.columns(3)

    with c1:
        st.markdown("""
        <div class="analyzer-card">
            <div class="card-title">🩺 The Silent Killer</div>
            <p class="card-description">
                Diabetes sering kali berkembang bertahun-tahun tanpa gejala yang nyata. 
                Lebih dari <strong>50% penderita diabetes di Indonesia belum terdiagnosis</strong> 
                sehingga berisiko komplikasi serius seperti penyakit jantung dan gagal ginjal.
            </p>
        </div>
        """, unsafe_allow_html=True)

    with c2:
        c2_html = """
        <div class="analyzer-card">
            <div class="card-title">⚡ Algoritma XGBoost Akurat</div>
            <p class="card-description">
                Menggunakan <strong>Extreme Gradient Boosting (XGBoost)</strong> yang dioptimalkan 
                secara terstratifikasi dengan pembobotan kelas untuk mendeteksi individu berisiko tinggi 
                secara sensitif dan objektif.
            </p>
        </div>
        """
        st.markdown(c2_html, unsafe_allow_html=True)

    with c3:
        st.markdown("""
        <div class="analyzer-card">
            <div class="card-title">🔒 Privasi Terjamin</div>
            <p class="card-description">
                Pengunjung dapat melakukan pengecekan risiko secara anonim tanpa perlu mendaftar akun 
                atau memasukkan identitas pribadi (Nama, NIK, No. HP). Data kesehatan Anda aman.
            </p>
        </div>
        """, unsafe_allow_html=True)

    st.markdown("---")

    # Alur Kerja Machine Learning
    st.markdown("### ⚙️ Cara Kerja Machine Learning pada Diabetes AI Analyzer")

    s1, s2, s3, s4 = st.columns(4)
    with s1:
        st.markdown("""
        <div class="analyzer-card" style="text-align: center;">
            <div class="step-number">1</div>
            <div class="card-title" style="justify-content: center; margin-top: 0.5rem;">Input Data</div>
            <p class="card-description" style="font-size: 0.85rem;">
                Pengguna mengisi 21 indikator gaya hidup, antropometri (BMI), dan riwayat kesehatan.
            </p>
        </div>
        """, unsafe_allow_html=True)

    with s2:
        st.markdown("""
        <div class="analyzer-card" style="text-align: center;">
            <div class="step-number">2</div>
            <div class="card-title" style="justify-content: center; margin-top: 0.5rem;">Standardisasi</div>
            <p class="card-description" style="font-size: 0.85rem;">
                Pipeline Scikit-learn menerapkan <em>StandardScaler</em> yang identik dengan proses pelatihan model.
            </p>
        </div>
        """, unsafe_allow_html=True)

    with s3:
        st.markdown("""
        <div class="analyzer-card" style="text-align: center;">
            <div class="step-number">3</div>
            <div class="card-title" style="justify-content: center; margin-top: 0.5rem;">Klasifikasi XGBoost</div>
            <p class="card-description" style="font-size: 0.85rem;">
                Model XGBoost menganalisis pohon keputusan (decision trees) secara ensemble gradient boosting.
            </p>
        </div>
        """, unsafe_allow_html=True)

    with s4:
        st.markdown("""
        <div class="analyzer-card" style="text-align: center;">
            <div class="step-number">4</div>
            <div class="card-title" style="justify-content: center; margin-top: 0.5rem;">Rekomendasi</div>
            <p class="card-description" style="font-size: 0.85rem;">
                Sistem memberikan estimasi tingkat risiko serta panduan tindakan pencegahan preventif.
            </p>
        </div>
        """, unsafe_allow_html=True)

    # Footer Info
    st.markdown("<br>", unsafe_allow_html=True)
    st.markdown(f"""
    <div style="text-align: center; color: var(--text-dim); font-size: 0.85rem; padding: 1.5rem 0;">
        Dikembangkan oleh <strong>{developer_name}</strong> | {institution} <br>
        Didukung oleh Scikit-learn, Python, & Streamlit Framework
    </div>
    """, unsafe_allow_html=True)


if __name__ == '__main__':
    render_home_page()

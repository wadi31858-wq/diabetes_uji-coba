"""
Halaman Tentang (About) - Diabetes AI Analyzer
Informasi tujuan proyek, penjelasan algoritma XGBoost, informasi dataset CDC BRFSS,
evaluasi model tervalidasi, dan profil pengembang.
"""

import os
import sys
import json
import streamlit as st

_parent_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _parent_dir not in sys.path:
    sys.path.insert(0, _parent_dir)

from database import track_visit, get_setting

METADATA_PATH = os.path.join(os.path.dirname(os.path.dirname(__file__)), 'models', 'metadata.json')


def load_metadata():
    if os.path.exists(METADATA_PATH):
        try:
            with open(METADATA_PATH, 'r', encoding='utf-8') as f:
                return json.load(f)
        except Exception:
            return None
    return None


def render_about_page():
    track_visit("Tentang")

    site_title = get_setting("site_title", "Diabetes AI Analyzer")
    developer_name = get_setting("developer_name", "Tim Data Mining & AI Healthcare")
    institution = get_setting("institution_name", "Universitas Nurul Jadid (UNUJA)")
    email = get_setting("support_email", "admin@diabetes-analyzer.ai")

    st.markdown(f"""
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 2rem; font-weight: 800; color: #FFFFFF; margin-bottom: 0.3rem;">
            ℹ️ Tentang {site_title}
        </h1>
        <p style="color: var(--text-muted); font-size: 1rem;">
            Inisiatif sistem pendukung keputusan klinis (Clinical Decision Support System) berbasis 
            Machine Learning untuk deteksi dini risiko diabetes pada masyarakat luas.
        </p>
    </div>
    """, unsafe_allow_html=True)

    tab_a1, tab_a2, tab_a3, tab_a4 = st.tabs([
        "🎯 Tujuan Proyek", 
        "🧠 Algoritma XGBoost", 
        "📊 Dataset CDC BRFSS", 
        "👥 Tim Pengembang"
    ])

    with tab_a1:
        st.markdown("### 🎯 Visi & Tujuan Pembuatan")
        st.write(f"""
        **{site_title}** dikembangkan sebagai solusi pencegahan preventif terhadap beban penyakit diabetes 
        yang semakin meningkat secara eksponensial di seluruh dunia. 
        
        Melalui pemanfaatan data mining dan algoritma klasifikasi cerdas, sistem ini bertujuan untuk:
        """)
        st.markdown("""
        1. **Meningkatkan Kesadaran Dini (*Early Screening*):** Memberikan akses mudah, cepat, dan gratis bagi masyarakat umum untuk mengukur estimasi risiko diabetes mereka secara mandiri.
        2. **Menjembatani Data Mining dan Kedokteran Preventif:** Membuktikan bagaimana pola data multi-indikator (antropometri, komorbiditas, dan perilaku hidup) dapat diekstrak menjadi wawasan prediktif yang bernilai klinis.
        3. **Mendorong Konsultasi Medis:** Menjadi katalis yang mendorong individu dengan estimasi risiko tinggi untuk segera memeriksakan diri ke dokter sebelum terjadi komplikasi kronis.
        4. **Menjaga Standar Privasi Pengguna:** Mengedepankan etika data medis dengan tidak meminta data pribadi sensitif (PII) yang dapat mengidentifikasi seseorang secara langsung.
        """)

    with tab_a2:
        st.markdown("### 🧠 Mengapa Extreme Gradient Boosting (XGBoost)?")
        st.write("""
        **Extreme Gradient Boosting (XGBoost)** adalah algoritma *supervised learning* berbasis ensemble decision trees yang sangat kuat dan 
        terbukti menjadi standar emas (*state-of-the-art*) dalam pemodelan data tabular klinis.
        """)

        c_xgb1, c_xgb2 = st.columns(2)
        with c_xgb1:
            st.markdown("""
            <div class="analyzer-card">
                <div class="card-title">🌳 Ensemble Gradient Tree Boosting</div>
                <p class="card-description">
                    XGBoost membangun ratusan pohon keputusan (*decision trees*) secara bertahap dan berurutan. Setiap pohon baru 
                    secara spesifik dilatih untuk mengoreksi residu atau kesalahan prediksi dari pohon-pohon sebelumnya, menghasilkan 
                    akurasi prediksi yang sangat tinggi.
                </p>
            </div>
            """, unsafe_allow_html=True)

        with c_xgb2:
            st.markdown("""
            <div class="analyzer-card">
                <div class="card-title">🛡️ Regularisasi & Ketahanan Overfitting</div>
                <p class="card-description">
                    Dilengkapi dengan regularisasi internal (L1 Lasso dan L2 Ridge) yang membatasi kompleksitas bobot daun, 
                    menjaga model tetap tangguh terhadap pencilan (*outliers*) dan mencegah *overfitting* pada dataset skala besar.
                </p>
            </div>
            """, unsafe_allow_html=True)

        st.markdown("#### Keunggulan Arsitektur XGBoost yang Diterapkan:")
        st.markdown(r"""
        - **Optimalisasi Data Tabular:** Sangat efektif menangani interaksi non-linear yang kompleks antar 21 indikator kesehatan tanpa kehilangan informasi kritis.
        - **Penanganan Ketidakseimbangan Kelas (*Class Imbalance*):** Menggunakan pembobotan `scale_pos_weight` untuk memastikan kasus positif diabetes terdeteksi secara sensitif (*high recall*).
        - **Interpretasi Feature Importance:** Memungkinkan pelacakan kontribusi setiap variabel klinis terhadap keputusan prediksi akhir (*Explainable AI*).
        """)

    with tab_a3:
        st.markdown("### 📊 Informasi Dataset: CDC BRFSS")
        st.write("""
        Sistem ini dilatih menggunakan dataset **Diabetes Health Indicators** yang bersumber dari survei tahunan 
        **Behavioral Risk Factor Surveillance System (BRFSS) 2015** oleh **Centers for Disease Control and Prevention (CDC)** di Amerika Serikat.
        """)

        meta = load_metadata()
        if meta and "dataset_info" in meta:
            dinfo = meta["dataset_info"]
            m1, m2, m3, m4 = st.columns(4)
            with m1:
                st.markdown(f"""
                <div class="metric-box">
                    <div class="metric-label">Total Sampel</div>
                    <div class="metric-value">{dinfo.get('total_samples', 0):,}</div>
                </div>
                """, unsafe_allow_html=True)
            with m2:
                st.markdown(f"""
                <div class="metric-box">
                    <div class="metric-label">Data Latih (80%)</div>
                    <div class="metric-value">{dinfo.get('train_samples', 0):,}</div>
                </div>
                """, unsafe_allow_html=True)
            with m3:
                st.markdown(f"""
                <div class="metric-box">
                    <div class="metric-label">Data Uji (20%)</div>
                    <div class="metric-value">{dinfo.get('test_samples', 0):,}</div>
                </div>
                """, unsafe_allow_html=True)
            with m4:
                st.markdown(f"""
                <div class="metric-box">
                    <div class="metric-label">Total Fitur</div>
                    <div class="metric-value">{len(meta.get('features', []))}</div>
                </div>
                """, unsafe_allow_html=True)

            st.markdown("<br>", unsafe_allow_html=True)
            st.markdown("#### Ringkasan Evaluasi Model Tervalidasi:")
            metrics = meta.get("metrics", {})
            cm = metrics.get("confusion_matrix", [[0, 0], [0, 0]])

            col_met1, col_met2 = st.columns(2)
            with col_met1:
                st.markdown(f"""
                - **Algoritma:** {meta.get('algorithm', meta.get('model_name', 'XGBoost'))}
                - **Tanggal Pelatihan:** {meta.get('training_date', '-')}
                - **Status Model:** <span class="badge badge-risk-low">{meta.get('status', 'Aktif')}</span>
                - **Akurasi Pengujian:** **{metrics.get('accuracy', 0)*100:.2f}%**
                - **Recall Kelas Diabetes (Sensitivitas):** **{metrics.get('recall_class_1', 0)*100:.2f}%**
                - **F1-Score (Diabetes):** {metrics.get('f1_class_1', 0):.4f}
                """, unsafe_allow_html=True)

            with col_met2:
                st.markdown(f"""
                **Confusion Matrix (Data Uji {dinfo.get('test_samples', 0):,} data):**
                - True Negatives (TN): **{cm[0][0]:,}**
                - False Positives (FP): **{cm[0][1]:,}**
                - False Negatives (FN): **{cm[1][0]:,}**
                - True Positives (TP): **{cm[1][1]:,}**
                """)
        else:
            st.info("Informasi evaluasi model akan dimuat secara dinamis setelah pelatihan model dilakukan.")

    with tab_a4:
        st.markdown("### 👥 Pengembang & Lisensi")
        st.markdown(f"""
        <div class="analyzer-card">
            <h3 style="color: var(--teal-light); margin-top: 0;">{site_title}</h3>
            <p><strong>Pengembang:</strong> {developer_name}</p>
            <p><strong>Institusi:</strong> {institution}</p>
            <p><strong>Kontak / Support:</strong> <a href="mailto:{email}" style="color: var(--teal-light);">{email}</a></p>
            <p><strong>Lisensi:</strong> Open-Source Academic Research License</p>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Aplikasi ini dibangun menggunakan Python 3.10+, Streamlit, Scikit-learn, Pandas, NumPy, SQLite, dan Plotly. 
                Didedikasikan untuk pengembangan ilmu pengetahuan dan kesehatan masyarakat.
            </p>
        </div>
        """, unsafe_allow_html=True)


if __name__ == '__main__':
    render_about_page()

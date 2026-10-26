"""
Manajemen Model Machine Learning - Admin Diabetes AI Analyzer
Menampilkan metadata model, metrik evaluasi tervalidasi, visualisasi Plotly Confusion Matrix,
analisis bobot koefisien fitur, dan prosedur pelatihan ulang tervalidasi.
"""

import os
import sys
import json
import pandas as pd
import plotly.express as px
import streamlit as st

_parent_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _parent_dir not in sys.path:
    sys.path.insert(0, _parent_dir)

from auth import require_admin, get_current_user
from database import log_activity
from train_model import train_xgboost_model, MODEL_PATH, METADATA_PATH


def load_metadata():
    if os.path.exists(METADATA_PATH):
        try:
            with open(METADATA_PATH, 'r', encoding='utf-8') as f:
                return json.load(f)
        except Exception:
            return None
    return None


def render_admin_model_page():
    # 1. Validasi Hak Akses Admin
    require_admin()

    user = get_current_user()
    current_username = user.get('username', 'admin')

    st.markdown("""
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 2rem; font-weight: 800; color: #FFFFFF; margin-bottom: 0.3rem;">
            ⚙️ Manajemen Model Machine Learning (XGBoost)
        </h1>
        <p style="color: var(--text-muted); font-size: 1rem;">
            Pantau status operasional model, evaluasi metrik kinerja tervalidasi, dan lakukan pembaruan model.
        </p>
    </div>
    """, unsafe_allow_html=True)

    meta = load_metadata()
    if not meta:
        st.error("Model Machine Learning belum ditemukan di sistem.")
        if st.button("🚀 Latih Model XGBoost Sekarang", type="primary"):
            with st.spinner("Sedang melatih model XGBoost..."):
                train_xgboost_model()
                st.cache_resource.clear()
                st.success("Model berhasil dilatih dan disimpan!")
                st.rerun()
        return

    # 2. Ringkasan Status Model
    dinfo = meta.get('dataset_info', {})
    metrics = meta.get('metrics', {})

    col_s1, col_s2, col_s3, col_s4 = st.columns(4)
    with col_s1:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">Algoritma</div>
            <div class="metric-value" style="font-size: 1.3rem;">XGBoost (Classifier)</div>
            <div class="metric-sub">Versi: {meta.get('model_version', '1.0.0')}</div>
        </div>
        """, unsafe_allow_html=True)
    with col_s2:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">Status Model</div>
            <div class="metric-value" style="color: #34D399; font-size: 1.3rem;">Aktif</div>
            <div class="metric-sub">Tervalidasi</div>
        </div>
        """, unsafe_allow_html=True)
    with col_s3:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">Data Latih / Uji</div>
            <div class="metric-value" style="font-size: 1.3rem;">{dinfo.get('train_samples', 0):,} / {dinfo.get('test_samples', 0):,}</div>
            <div class="metric-sub">Stratified Split (80:20)</div>
        </div>
        """, unsafe_allow_html=True)
    with col_s4:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">Waktu Pelatihan</div>
            <div class="metric-value" style="font-size: 1.3rem;">{meta.get('training_duration_seconds', 0)}s</div>
            <div class="metric-sub">{meta.get('training_date', '-')}</div>
        </div>
        """, unsafe_allow_html=True)

    st.markdown("<br>", unsafe_allow_html=True)

    # 3. Metrik Evaluasi Komprehensif
    st.markdown("### 📊 Metrik Evaluasi Kinerja (Data Uji 50.736 Sampel)")

    m_col1, m_col2, m_col3, m_col4 = st.columns(4)
    with m_col1:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">Akurasi Global</div>
            <div class="metric-value">{metrics.get('accuracy', 0)*100:.2f}%</div>
            <div class="metric-sub">Skor Keseluruhan</div>
        </div>
        """, unsafe_allow_html=True)
    with m_col2:
        st.markdown(f"""
        <div class="metric-box" style="border-color: rgba(20, 184, 166, 0.5);">
            <div class="metric-label" style="color: var(--teal-light);">Recall (Sensitivitas Kelas 1)</div>
            <div class="metric-value">{metrics.get('recall_class_1', 0)*100:.2f}%</div>
            <div class="metric-sub">Kasus Positif Terdeteksi</div>
        </div>
        """, unsafe_allow_html=True)
    with m_col3:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">Precision (Kelas 1)</div>
            <div class="metric-value">{metrics.get('precision_class_1', 0)*100:.2f}%</div>
            <div class="metric-sub">Ketepatan Prediksi Positif</div>
        </div>
        """, unsafe_allow_html=True)
    with m_col4:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">Macro F1-Score</div>
            <div class="metric-value">{metrics.get('macro_f1', 0):.4f}</div>
            <div class="metric-sub">Keseimbangan Antar Kelas</div>
        </div>
        """, unsafe_allow_html=True)

    st.markdown("---")

    # 4. Visualisasi Confusion Matrix & Bobot Fitur (Plotly)
    c_vis1, c_vis2 = st.columns([1, 1])

    plotly_layout_dark = dict(
        paper_bgcolor='rgba(17, 34, 64, 0.6)',
        plot_bgcolor='rgba(17, 34, 64, 0.6)',
        font=dict(color='#F1F5F9', family='Plus Jakarta Sans, sans-serif')
    )

    with c_vis1:
        st.markdown("##### 🔲 Heatmap Confusion Matrix")
        cm = metrics.get('confusion_matrix', [[0, 0], [0, 0]])
        z = cm
        x = ['Prediksi Negatif (0)', 'Prediksi Positif (1)']
        y = ['Aktual Negatif (0)', 'Aktual Positif (1)']

        fig_cm = px.imshow(
            z,
            text_auto=True,
            x=x,
            y=y,
            color_continuous_scale=[[0, '#0A192F'], [0.5, '#0D9488'], [1, '#14B8A6']]
        )
        fig_cm.update_layout(
            title_text="Confusion Matrix XGBoost",
            coloraxis_showscale=False,
            **plotly_layout_dark,
            margin=dict(l=40, r=40, t=50, b=40)
        )
        st.plotly_chart(fig_cm, use_container_width=True)

        st.markdown(f"""
        <div style="font-size: 0.85rem; color: var(--text-muted); padding: 0.5rem;">
            • <strong>True Negatives (TN):</strong> {cm[0][0]:,} orang sehat terklasifikasi benar.<br>
            • <strong>False Positives (FP):</strong> {cm[0][1]:,} orang sehat terklasifikasi waspada.<br>
            • <strong>False Negatives (FN):</strong> {cm[1][0]:,} kasus terlewat.<br>
            • <strong>True Positives (TP):</strong> {cm[1][1]:,} pasien diabetes terdeteksi tepat ({metrics.get('recall_class_1', 0)*100:.1f}%).
        </div>
        """, unsafe_allow_html=True)

    with c_vis2:
        st.markdown("##### ⚖️ Kepentingan Fitur (Feature Importance) XGBoost")
        weights = meta.get('feature_weights', {})
        if weights:
            sorted_weights = sorted(weights.items(), key=lambda item: item[1])
            w_df = pd.DataFrame(sorted_weights, columns=['Fitur', 'Koefisien'])
            w_df['Tipe'] = 'Tingkat Pengaruh Fitur'

            fig_w = px.bar(
                w_df,
                x='Koefisien',
                y='Fitur',
                orientation='h',
                color='Tipe',
                color_discrete_map={
                    'Tingkat Pengaruh Fitur': '#14B8A6'
                }
            )
            fig_w.update_layout(
                title_text="Pengaruh Fitur terhadap Keputusan XGBoost",
                xaxis_title="Importance Score (XGBoost)",
                yaxis_title="",
                **plotly_layout_dark,
                margin=dict(l=40, r=20, t=50, b=40)
            )
            st.plotly_chart(fig_w, use_container_width=True)
        else:
            st.info("Informasi koefisien bobot fitur belum tersedia.")

    st.markdown("---")

    # 5. Prosedur Pelatihan Ulang Model Tervalidasi
    st.markdown("### 🔄 Pelatihan Ulang Model (Retraining Model)")
    st.markdown("""
    <div class="analyzer-card">
        <p style="font-size: 0.95rem; color: var(--text-muted); margin-top: 0;">
            Pelatihan ulang akan membaca dataset aktif di direktori <code>data/diabetes_health_indicators.csv</code>, 
            melakukan validasi skema dan data leakage, membagi data secara terstratifikasi, membangun ulang pipeline 
            Scikit-learn, mengevaluasi data uji, dan memperbarui file model <code>models/diabetes_model.joblib</code>.
        </p>
    </div>
    """, unsafe_allow_html=True)

    c_ret1, c_ret2 = st.columns([2, 5])
    with c_ret1:
        if st.button("⚡ Latih Ulang Model Sekarang", type="primary", use_container_width=True):
            with st.spinner("Sedang memproses pelatihan model XGBoost secara terstratifikasi..."):
                try:
                    new_meta = train_xgboost_model()
                    log_activity(
                        current_username,
                        "RETRAIN_MODEL",
                        f"Berhasil melatih ulang model XGBoost. Akurasi: {new_meta['metrics']['accuracy']*100:.2f}%, Recall: {new_meta['metrics']['recall_class_1']*100:.2f}%"
                    )
                    st.cache_resource.clear()
                    st.success("✅ Model XGBoost berhasil diperbarui dan divalidasi!")
                    st.rerun()
                except Exception as e:
                    st.error(f"Gagal melatih model: {e}")


if __name__ == '__main__':
    render_admin_model_page()

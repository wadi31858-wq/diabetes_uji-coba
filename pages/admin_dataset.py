"""
Manajemen Dataset - Admin Diabetes AI Analyzer
Eksplorasi dataset, distribusi kelas target, pemeriksaan integritas data (missing & duplicate),
serta pengunggahan dataset baru dengan validasi skema ketat.
"""

import os
import sys
import pandas as pd
import plotly.express as px
import streamlit as st

_parent_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _parent_dir not in sys.path:
    sys.path.insert(0, _parent_dir)

from auth import require_admin, get_current_user
from database import log_activity
from train_model import DATA_PATH, FEATURES, TARGET

def render_admin_dataset_page():
    # 1. Validasi Hak Akses Admin
    require_admin()

    user = get_current_user()
    current_username = user.get('username', 'admin')

    st.markdown("""
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 2rem; font-weight: 800; color: #FFFFFF; margin-bottom: 0.3rem;">
            📁 Manajemen & Eksplorasi Dataset
        </h1>
        <p style="color: var(--text-muted); font-size: 1rem;">
            Pantau integritas dataset aktif, distribusi kelas target, data duplikat, dan validasi pengunggahan dataset baru.
        </p>
    </div>
    """, unsafe_allow_html=True)

    if not os.path.exists(DATA_PATH):
        st.error(f"File dataset tidak ditemukan di: `{DATA_PATH}`")
        return

    # 2. Muat Dataset Aktif (cached)
    @st.cache_data
    def load_active_data():
        return pd.read_csv(DATA_PATH)

    df = load_active_data()

    # 3. Metrik Ringkasan Dataset
    total_rows = len(df)
    total_cols = len(df.columns)
    missing_vals = int(df.isnull().sum().sum())
    duplicate_rows = int(df.duplicated().sum())

    c1, c2, c3, c4 = st.columns(4)
    with c1:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">Total Baris Sampel</div>
            <div class="metric-value">{total_rows:,}</div>
            <div class="metric-sub">Observasi Pasien</div>
        </div>
        """, unsafe_allow_html=True)
    with c2:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">Total Kolom Fitur</div>
            <div class="metric-value">{total_cols}</div>
            <div class="metric-sub">{len(FEATURES)} Fitur + Target</div>
        </div>
        """, unsafe_allow_html=True)
    with c3:
        st.markdown(f"""
        <div class="metric-box" style="border-color: rgba(16, 185, 129, 0.4);">
            <div class="metric-label">Data Kosong (NaN)</div>
            <div class="metric-value" style="color: #34D399;">{missing_vals}</div>
            <div class="metric-sub">Lengkap & Bersih</div>
        </div>
        """, unsafe_allow_html=True)
    with c4:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">Data Duplikat</div>
            <div class="metric-value" style="font-size: 1.5rem;">{duplicate_rows:,}</div>
            <div class="metric-sub">Karakteristik Alami BRFSS</div>
        </div>
        """, unsafe_allow_html=True)

    st.markdown("<br>", unsafe_allow_html=True)

    # 4. Distribusi Kelas Target
    st.markdown("### 🎯 Distribusi Kelas Target (`Diabetes_binary`)")
    col_d1, col_d2 = st.columns([1, 1])

    plotly_layout_dark = dict(
        paper_bgcolor='rgba(17, 34, 64, 0.6)',
        plot_bgcolor='rgba(17, 34, 64, 0.6)',
        font=dict(color='#F1F5F9', family='Plus Jakarta Sans, sans-serif'),
        margin=dict(l=20, r=20, t=30, b=20)
    )

    if TARGET in df.columns:
        counts = df[TARGET].value_counts().reset_index()
        counts.columns = ['Kelas', 'Jumlah']
        counts['Label'] = counts['Kelas'].map({
            0: '0 - Non-Diabetes',
            1: '1 - Diabetes / Pre-Diabetes'
        })
        counts['Persentase'] = (counts['Jumlah'] / total_rows * 100).round(2)

        with col_d1:
            fig_bar = px.bar(
                counts,
                x='Label',
                y='Jumlah',
                color='Label',
                text='Jumlah',
                color_discrete_map={
                    '0 - Non-Diabetes': '#14B8A6',
                    '1 - Diabetes / Pre-Diabetes': '#EF4444'
                }
            )
            fig_bar.update_layout(
                title_text="Jumlah Sampel per Kelas Target",
                xaxis_title="",
                yaxis_title="Jumlah Sampel",
                showlegend=False,
                **plotly_layout_dark
            )
            st.plotly_chart(fig_bar, use_container_width=True)

        with col_d2:
            fig_pie = px.pie(
                counts,
                values='Jumlah',
                names='Label',
                hole=0.5,
                color='Label',
                color_discrete_map={
                    '0 - Non-Diabetes': '#14B8A6',
                    '1 - Diabetes / Pre-Diabetes': '#EF4444'
                }
            )
            fig_pie.update_layout(
                title_text="Rasio Keseimbangan Kelas (%)",
                **plotly_layout_dark
            )
            st.plotly_chart(fig_pie, use_container_width=True)

    st.markdown("---")

    # 5. Preview & Struktur Data
    st.markdown("### 👁️ Pratinjau 10 Baris Pertama Dataset")
    st.dataframe(df.head(10), use_container_width=True)

    with st.expander("Lihat Rangkuman Statistik Deskriptif (df.describe)"):
        st.dataframe(df.describe().T.round(2), use_container_width=True)

    st.markdown("---")

    # 6. Unggah & Validasi Dataset Baru
    st.markdown("### 📤 Unggah Dataset Baru (Validasi Skema Otomatis)")
    st.markdown("""
    <div class="analyzer-card">
        <p style="font-size: 0.9rem; color: var(--text-muted); margin: 0;">
            Dataset yang diunggah harus berformat <strong>CSV</strong> dan wajib memuat 21 kolom fitur: 
            <code>HighBP, HighChol, CholCheck, BMI, Smoker, Stroke, HeartDiseaseorAttack, PhysActivity, Fruits, Veggies, HvyAlcoholConsump, AnyHealthcare, NoDocbcCost, GenHlth, MentHlth, PhysHlth, DiffWalk, Sex, Age, Education, Income</code> 
            serta kolom target <code>Diabetes_binary</code>.
        </p>
    </div>
    """, unsafe_allow_html=True)

    uploaded_file = st.file_uploader("Pilih file CSV dataset baru", type=["csv"])

    if uploaded_file is not None:
        try:
            new_df = pd.read_csv(uploaded_file)
            st.success(f"File berhasil dibaca: {len(new_df):,} baris dan {len(new_df.columns)} kolom.")

            # Validasi Skema
            missing_cols = [c for c in FEATURES if c not in new_df.columns]
            has_target = TARGET in new_df.columns

            if missing_cols:
                st.error(f"❌ Skema tidak valid! Kolom fitur berikut hilang: `{missing_cols}`")
            elif not has_target:
                st.error(f"❌ Skema tidak valid! Kolom target `{TARGET}` tidak ditemukan.")
            else:
                st.success("✅ **Validasi Skema Berhasil!** Seluruh 21 fitur dan target terdeteksi lengkap.")
                if st.button("💾 Konfirmasi & Terapkan Dataset Baru", type="primary"):
                    new_df.to_csv(DATA_PATH, index=False)
                    st.cache_data.clear()
                    log_activity(
                        current_username,
                        "UPDATE_DATASET",
                        f"Mengunggah dataset baru: {len(new_df):,} baris"
                    )
                    st.success("Dataset aktif berhasil diperbarui! Jangan lupa untuk melatih ulang model di menu Manajemen Model.")
                    st.rerun()

        except Exception as e:
            st.error(f"Terjadi kesalahan saat memvalidasi dataset: {e}")


if __name__ == '__main__':
    render_admin_dataset_page()

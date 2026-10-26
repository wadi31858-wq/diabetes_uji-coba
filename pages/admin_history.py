

"""
Manajemen Riwayat Prediksi - Admin Diabetes AI Analyzer
Melihat daftar riwayat prediksi, filter tanggal & kelas, pencarian,
ekspor data ke CSV, dan penghapusan sesuai kebijakan privasi & retensi data.
"""
import os
import sys
from datetime import datetime, timedelta
import pandas as pd
import streamlit as st

_parent_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _parent_dir not in sys.path:
    sys.path.insert(0, _parent_dir)

from auth import require_admin, get_current_user
from database import (
    get_prediction_history,
    count_predictions_total,
    delete_prediction,
    delete_predictions_by_retention,
    log_activity,
    get_setting
)


def render_admin_history_page():
    # 1. Validasi Hak Akses Admin
    require_admin()

    user = get_current_user()
    current_username = user.get('username', 'admin')

    st.markdown("""
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 2rem; font-weight: 800; color: #FFFFFF; margin-bottom: 0.3rem;">
            📑 Manajemen Riwayat Prediksi
        </h1>
        <p style="color: var(--text-muted); font-size: 1rem;">
            Kelola arsip log prediksi anonim, filter data, ekspor laporan, dan terapkan kebijakan retensi data.
        </p>
    </div>
    """, unsafe_allow_html=True)

    # Filter & Pencarian
    st.markdown("### 🔍 Filter & Pencarian")
    f_col1, f_col2, f_col3, f_col4 = st.columns([2, 2, 2, 2])

    with f_col1:
        search_kw = st.text_input("Cari ID / Label", placeholder="Contoh: 12 atau Tinggi")
    with f_col2:
        filter_status = st.selectbox(
            "Filter Hasil",
            options=["Semua", "Risiko Rendah", "Berisiko Tinggi"],
            index=0
        )
    with f_col3:
        start_d = st.date_input("Dari Tanggal", value=datetime.now() - timedelta(days=30))
    with f_col4:
        end_d = st.date_input("Sampai Tanggal", value=datetime.now())

    # Ambil data
    start_d_str = start_d.strftime("%Y-%m-%d") if start_d else None
    end_d_str = end_d.strftime("%Y-%m-%d") if end_d else None

    total_records = count_predictions_total(
        filter_result=filter_status,
        search_keyword=search_kw,
        start_date=start_d_str,
        end_date=end_d_str
    )

    limit = 50
    st.markdown(f"**Menampilkan arsip riwayat:** Total ditemukan **{total_records}** catatan.")

    if total_records > 0:
        history_df = get_prediction_history(
            limit=limit,
            offset=0,
            filter_result=filter_status,
            search_keyword=search_kw,
            start_date=start_d_str,
            end_date=end_d_str
        )

        # Siapkan kolom tampilan yang informatif
        display_cols = [
            'id', 'timestamp', 'prediction_label', 'bmi', 'gen_hlth', 
            'high_chol', 'smoker', 'diff_walk', 'age', 'model_version'
        ]
        available_cols = [c for c in display_cols if c in history_df.columns]
        view_df = history_df[available_cols].copy()
        view_df.rename(columns={
            'id': 'ID',
            'timestamp': 'Waktu Prediksi',
            'prediction_label': 'Hasil Prediksi',
            'bmi': 'BMI',
            'gen_hlth': 'Skala Sehat (1-5)',
            'high_chol': 'Kolesterol Tinggi',
            'smoker': 'Merokok',
            'diff_walk': 'Sulit Jalan',
            'age': 'Kat. Usia',
            'model_version': 'Versi Model'
        }, inplace=True)

        st.dataframe(view_df, use_container_width=True, hide_index=True)

        # Opsi Ekspor CSV
        csv_data = history_df.to_csv(index=False).encode('utf-8')
        c_exp1, c_exp2 = st.columns([2, 5])
        with c_exp1:
            st.download_button(
                label="📥 Unduh Data (CSV)",
                data=csv_data,
                file_name=f"riwayat_prediksi_diabetes_{datetime.now().strftime('%Y%m%d_%H%M%S')}.csv",
                mime="text/csv",
                type="primary"
            )

    else:
        st.info("Belum ada data riwayat yang cocok dengan kriteria pencarian atau filter yang dipilih.")

    st.markdown("---")

    # Fitur Penghapusan & Kebijakan Retensi Data
    st.markdown("### 🗑️ Kebijakan Pembersihan & Retensi Data")
    c_del1, c_del2 = st.columns(2)

    with c_del1:
        st.markdown("""
        <div class="analyzer-card">
            <h4 style="color: #F87171; margin-top: 0;">Hapus Berdasarkan ID Spesifik</h4>
            <p style="font-size: 0.85rem; color: var(--text-muted);">
                Hapus catatan prediksi individual jika ada permintaan penghapusan data atau pengujian.
            </p>
        </div>
        """, unsafe_allow_html=True)
        with st.form("delete_single_form"):
            del_id = st.number_input("Masukkan ID Prediksi yang Ingin Dihapus", min_value=1, step=1)
            del_single_btn = st.form_submit_button("Hapus Catatan Ini", type="secondary")

            if del_single_btn:
                success = delete_prediction(int(del_id))
                if success:
                    log_activity(current_username, "DELETE_PREDICTION", f"Menghapus rekaman prediksi ID #{del_id}")
                    st.success(f"Catatan ID #{del_id} berhasil dihapus.")
                    st.rerun()
                else:
                    st.error(f"Catatan dengan ID #{del_id} tidak ditemukan.")

    with c_del2:
        retention_setting = get_setting("retention_days", "90")
        st.markdown(f"""
        <div class="analyzer-card">
            <h4 style="color: #FBBF24; margin-top: 0;">Terapkan Kebijakan Retensi Otomatis</h4>
            <p style="font-size: 0.85rem; color: var(--text-muted);">
                Sesuai standar privasi, data lama yang melebihi batas retensi ({retention_setting} hari) dapat dibersihkan secara massal.
            </p>
        </div>
        """, unsafe_allow_html=True)

        with st.form("retention_purge_form"):
            days_input = st.number_input(
                "Hapus data yang lebih lama dari (hari):",
                min_value=1,
                max_value=365,
                value=int(retention_setting)
            )
            purge_btn = st.form_submit_button("🧹 Bersihkan Data Usang", type="secondary")

            if purge_btn:
                count_deleted = delete_predictions_by_retention(int(days_input))
                log_activity(
                    current_username,
                    "PURGE_RETENTION",
                    f"Membersihkan {count_deleted} data prediksi yang lebih lama dari {days_input} hari"
                )
                st.success(f"Berhasil membersihkan {count_deleted} catatan data usang.")
                st.rerun()


if __name__ == '__main__':
    render_admin_history_page()

"""
Pengaturan Sistem & Audit Log - Admin Diabetes AI Analyzer
Mengelola profil sistem, kebijakan retensi data, ganti kata sandi,
dan pemantauan audit trail aktivitas administrator.
"""

import os
import sys
import streamlit as st

_parent_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _parent_dir not in sys.path:
    sys.path.insert(0, _parent_dir)

from auth import require_admin, get_current_user, change_admin_password, logout
from database import (
    get_all_settings,
    set_setting,
    get_activity_logs,
    log_activity
)

def render_admin_settings_page():
    # 1. Validasi Hak Akses Admin
    require_admin()

    user = get_current_user()
    current_username = user.get('username', 'admin')

    st.markdown("""
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 2rem; font-weight: 800; color: #FFFFFF; margin-bottom: 0.3rem;">
            ⚙️ Pengaturan Sistem & Audit Trail
        </h1>
        <p style="color: var(--text-muted); font-size: 1rem;">
            Kelola konfigurasi umum, keamanan akun administrator, kebijakan data, dan log audit.
        </p>
    </div>
    """, unsafe_allow_html=True)

    tab_set1, tab_set2, tab_set3 = st.tabs([
        "🛠️ Konfigurasi Umum", 
        "🔒 Keamanan & Akun", 
        "📋 Audit Trail Log Aktivitas"
    ])

    with tab_set1:
        st.markdown("### 🏷️ Informasi Aplikasi & Website")
        settings = get_all_settings()

        with st.form("general_settings_form"):
            c_s1, c_s2 = st.columns(2)
            with c_s1:
                title_val = st.text_input("Judul Website / Sistem", value=settings.get('site_title', 'Diabetes AI Analyzer'))
                dev_val = st.text_input("Nama Pengembang / Tim", value=settings.get('developer_name', 'Tim Data Mining & AI Healthcare'))
            with c_s2:
                inst_val = st.text_input("Nama Institusi / Universitas", value=settings.get('institution_name', 'Universitas Nurul Jadid (UNUJA)'))
                email_val = st.text_input("Email Kontak / Support", value=settings.get('support_email', 'admin@diabetes-analyzer.ai'))

            st.markdown("---")
            st.markdown("### 🗄️ Kebijakan Privasi & Retensi Data")
            retention_val = st.number_input(
                "Masa Retensi Riwayat Prediksi Otomatis (Hari)",
                min_value=7,
                max_value=730,
                value=int(settings.get('retention_days', '90')),
                help="Berapa hari data prediksi disimpan sebelum dibersihkan oleh sistem."
            )

            submit_settings = st.form_submit_button("💾 Simpan Perubahan Pengaturan", type="primary")

        if submit_settings:
            set_setting('site_title', title_val, current_username)
            set_setting('developer_name', dev_val, current_username)
            set_setting('institution_name', inst_val, current_username)
            set_setting('support_email', email_val, current_username)
            set_setting('retention_days', str(retention_val), current_username)

            log_activity(current_username, "UPDATE_SETTINGS", "Memperbarui konfigurasi informasi website dan retensi")
            st.success("✅ Pengaturan sistem berhasil diperbarui!")
            st.rerun()

    with tab_set2:
        st.markdown("### 🔑 Ganti Kata Sandi Administrator")
        st.write(f"Pengguna saat ini: **{user.get('full_name')}** (`{current_username}`)")

        with st.form("change_password_form"):
            old_pwd = st.text_input("Kata Sandi Saat Ini", type="password")
            new_pwd = st.text_input("Kata Sandi Baru (Min. 6 karakter)", type="password")
            confirm_pwd = st.text_input("Ulangi Kata Sandi Baru", type="password")

            pwd_btn = st.form_submit_button("Perbarui Kata Sandi", type="primary")

        if pwd_btn:
            if not old_pwd or not new_pwd:
                st.error("Silakan lengkapi seluruh formulir kata sandi.")
            elif new_pwd != confirm_pwd:
                st.error("Konfirmasi kata sandi baru tidak cocok.")
            else:
                success, msg = change_admin_password(current_username, old_pwd, new_pwd)
                if success:
                    st.success(f"✅ {msg}")
                else:
                    st.error(f"❌ {msg}")

        st.markdown("---")
        st.markdown("### 🚪 Sesi Login")
        st.write("Klik tombol di bawah ini untuk mengakhiri sesi administrator dengan aman.")
        if st.button("Logout dari Akun Admin", type="secondary"):
            logout()

    with tab_set3:
        st.markdown("### 📋 Audit Trail - Rekam Jejak Aktivitas Admin")
        st.write("Mencatat setiap tindakan penting (login, perubahan model, pembaruan dataset, dan modifikasi pengaturan).")

        logs_df = get_activity_logs(limit=100)
        if not logs_df.empty:
            view_logs = logs_df[['id', 'timestamp', 'username', 'action', 'details']].copy()
            view_logs.rename(columns={
                'id': 'Log ID',
                'timestamp': 'Waktu',
                'username': 'Pengguna',
                'action': 'Aktivitas',
                'details': 'Rincian Keterangan'
            }, inplace=True)
            st.dataframe(view_logs, use_container_width=True, hide_index=True)

            csv_log = logs_df.to_csv(index=False).encode('utf-8')
            st.download_button(
                label="📥 Unduh Log Audit (CSV)",
                data=csv_log,
                file_name="audit_logs_diabetes_ai.csv",
                mime="text/csv"
            )
        else:
            st.info("Belum ada log audit yang tersimpan.")


if __name__ == '__main__':
    render_admin_settings_page()

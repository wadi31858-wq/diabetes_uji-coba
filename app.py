"""
Aplikasi Utama Diabetes AI Analyzer
Sistem Deteksi Dini Risiko Diabetes Berbasis Machine Learning (XGBoost)
"""

import os
import streamlit as st

# Konfigurasi Halaman Streamlit (Wajib di awal script)
st.set_page_config(
    page_title="Diabetes AI Analyzer",
    page_icon="🩺",
    layout="wide",
    initial_sidebar_state="expanded"
)

# Impor Modul Database & Autentikasi
import database
from auth import is_authenticated, get_current_user, logout

# Impor Halaman-Halaman
from pages.home import render_home_page
from pages.prediction import render_prediction_page
from pages.education import render_education_page
from pages.about import render_about_page
from pages.login import render_login_page
from pages.admin_dashboard import render_admin_dashboard_page
from pages.admin_history import render_admin_history_page
from pages.admin_model import render_admin_model_page
from pages.admin_dataset import render_admin_dataset_page
from pages.admin_settings import render_admin_settings_page


def inject_custom_css():
    """Menyisipkan file styling CSS kustom tema Dark Navy & Teal."""
    css_path = os.path.join(os.path.dirname(__file__), 'assets', 'style.css')
    if os.path.exists(css_path):
        with open(css_path, 'r', encoding='utf-8') as f:
            st.markdown(f'<style>{f.read()}</style>', unsafe_allow_html=True)


def main():
    # Injeksi CSS Kustom
    inject_custom_css()

    # Inisialisasi Objek Halaman Streamlit
    page_home = st.Page(render_home_page, title="Beranda", icon="🏠", default=True)
    page_pred = st.Page(render_prediction_page, title="Prediksi Risiko", icon="🩺")
    page_edu = st.Page(render_education_page, title="Edukasi Diabetes", icon="📚")
    page_about = st.Page(render_about_page, title="Tentang Sistem", icon="ℹ️")
    page_login = st.Page(render_login_page, title="Login Admin", icon="🔐")

    # Halaman Khusus Administrator
    page_dash = st.Page(render_admin_dashboard_page, title="Dashboard Admin", icon="📊")
    page_history = st.Page(render_admin_history_page, title="Riwayat Prediksi", icon="📑")
    page_model = st.Page(render_admin_model_page, title="Manajemen Model", icon="⚙️")
    page_dataset = st.Page(render_admin_dataset_page, title="Manajemen Dataset", icon="📁")
    page_settings = st.Page(render_admin_settings_page, title="Pengaturan Sistem", icon="🔧")

    # Simpan objek halaman di session state untuk navigasi switch_page
    st.session_state['page_pred_obj'] = page_pred
    st.session_state['page_dash_obj'] = page_dash

    # Navigasi Dinamis Berdasarkan Status Login
    if is_authenticated():
        user = get_current_user()
        nav_structure = {
            "Menu Publik": [page_home, page_pred, page_edu, page_about],
            "Panel Administrator": [page_dash, page_history, page_model, page_dataset, page_settings]
        }
    else:
        nav_structure = {
            "Menu Pengunjung": [page_home, page_pred, page_edu, page_about],
            "Akses Petugas": [page_login]
        }

    # Render Navigasi Streamlit
    pg = st.navigation(nav_structure)

    # Sidebar Header Branding
    with st.sidebar:
        st.markdown("""
        <div style="text-align: center; padding: 1rem 0; border-bottom: 1px solid rgba(20, 184, 166, 0.2); margin-bottom: 1rem;">
            <div style="font-size: 2.2rem; margin-bottom: 0.2rem;">🩺</div>
            <div style="font-size: 1.15rem; font-weight: 800; color: #5EEAD4; letter-spacing: -0.01em;">
                Diabetes AI Analyzer
            </div>
            <div style="font-size: 0.75rem; color: #94A3B8;">
                Extreme Gradient Boosting (XGBoost)
            </div>
        </div>
        """, unsafe_allow_html=True)

    # Eksekusi Halaman Terpilih
    pg.run()

    # Sidebar Footer Status & Kontrol Pengguna
    with st.sidebar:
        st.markdown("---")
        if is_authenticated():
            user = get_current_user()
            st.markdown(f"""
            <div style="background: rgba(20, 184, 166, 0.1); border: 1px solid rgba(20, 184, 166, 0.3); border-radius: 8px; padding: 0.75rem; margin-bottom: 0.8rem;">
                <div style="font-size: 0.8rem; color: #5EEAD4; font-weight: 700;">🟢 SESI ADMIN AKTIF</div>
                <div style="font-size: 0.85rem; color: #FFFFFF; font-weight: 600;">{user.get('full_name')}</div>
                <div style="font-size: 0.75rem; color: #94A3B8;">@{user.get('username')}</div>
            </div>
            """, unsafe_allow_html=True)
            if st.button("🚪 Keluar (Logout)", key="sidebar_logout_btn", use_container_width=True, type="secondary"):
                logout()
        else:
            st.markdown("""
            <div style="font-size: 0.78rem; color: #94A3B8; text-align: center; line-height: 1.4;">
                🔒 <strong>Privasi 100% Terjaga</strong><br>
                Analisis tanpa perlu login dan tanpa data identitas pribadi.
            </div>
            """, unsafe_allow_html=True)


if __name__ == '__main__':
    main()

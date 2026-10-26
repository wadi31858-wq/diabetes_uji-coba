"""
Halaman Login Admin - Diabetes AI Analyzer
Formulir autentikasi aman untuk administrator sistem.
"""

import os
import sys
import streamlit as st

_parent_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _parent_dir not in sys.path:
    sys.path.insert(0, _parent_dir)

from auth import login, logout, is_authenticated, get_current_user
from database import track_visit

def render_login_page():
    track_visit("Login")

    st.markdown("""
    <div style="margin-bottom: 1.5rem; text-align: center;">
        <h1 style="font-size: 2rem; font-weight: 800; color: #FFFFFF; margin-bottom: 0.3rem;">
            🔐 Masuk Administrator Sistem
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">
            Akses dashboard kontrol, manajemen model machine learning, riwayat prediksi, dan audit log.
        </p>
    </div>
    """, unsafe_allow_html=True)

    # Jika sudah terautentikasi
    if is_authenticated():
        user = get_current_user()
        st.success(f"✅ Anda saat ini sudah masuk sebagai **{user.get('full_name', 'Admin')}** (`{user.get('username')}`).")

        c1, c2 = st.columns(2)
        with c1:
            if st.button("📊 Buka Dashboard Admin", type="primary", use_container_width=True):
                try:
                    target = st.session_state.get('page_dash_obj')
                    if target:
                        st.switch_page(target)
                    else:
                        st.switch_page("pages/admin_dashboard.py")
                except Exception:
                    st.rerun()
        with c2:
            if st.button("🚪 Keluar (Logout)", type="secondary", use_container_width=True):
                logout()
        return

    # Form Login jika belum masuk
    col_l1, col_center, col_l2 = st.columns([1, 2, 1])

    with col_center:
        st.markdown("""
        <div class="analyzer-card">
            <h3 style="margin-top: 0; color: var(--teal-light); text-align: center;">Formulir Autentikasi</h3>
            <p style="font-size: 0.85rem; color: var(--text-muted); text-align: center;">
                Password disimpan menggunakan PBKDF2-HMAC-SHA256 dengan Salt kriptografis unik.
            </p>
        </div>
        """, unsafe_allow_html=True)

        with st.form("admin_login_form"):
            username = st.text_input("Username", placeholder="Masukkan username admin").strip()
            password = st.text_input("Kata Sandi", type="password", placeholder="Masukkan password")

            submit_btn = st.form_submit_button("🔑 Masuk ke Sistem", type="primary", use_container_width=True)

        if submit_btn:
            if not username or not password:
                st.warning("Silakan isi username dan kata sandi terlebih dahulu.")
            else:
                success, msg = login(username, password)
                if success:
                    st.success("Login berhasil! Mengalihkan ke dashboard admin...")
                    st.session_state['current_page'] = 'admin_dashboard'
                    st.rerun()
                else:
                    st.error(f"❌ {msg}")

        # Info default untuk pengujian awal
        st.markdown("""
        <div style="background: rgba(255, 255, 255, 0.03); border-radius: 8px; padding: 0.8rem; margin-top: 1rem; font-size: 0.8rem; color: var(--text-dim); text-align: center;">
            💡 <em>Akun Admin Awal:</em> <code>admin</code> | Sandi: <code>Admin123!</code><br>
            (Dapat diganti kapan saja melalui menu Pengaturan Sistem)
        </div>
        """, unsafe_allow_html=True)


if __name__ == '__main__':
    render_login_page()

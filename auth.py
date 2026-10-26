"""
Modul Autentikasi dan Keamanan untuk Diabetes AI Analyzer.
Menangani:
- Hashing password menggunakan PBKDF2-HMAC-SHA256 dengan Salt kriptografis aman
- Manajemen status autentikasi melalui Streamlit Session State
- Pembatasan hak akses halaman admin (Route Guard)
- Audit log aktivitas login/logout
- Pembuatan dan pembaruan akun admin
"""

import hashlib
import hmac
import secrets
from datetime import datetime
import streamlit as st

from database import get_connection, log_activity


def hash_password(password: str, salt: str | None = None) -> tuple[str, str]:
    """
    Menghasilkan hash password aman menggunakan PBKDF2-HMAC-SHA256.
    Mengembalikan (hash_hex, salt_hex).
    """
    if salt is None:
        salt = secrets.token_hex(16)
    
    pwd_bytes = password.encode('utf-8')
    salt_bytes = bytes.fromhex(salt)
    
    # 100,000 iterasi PBKDF2 standar industri
    derived = hashlib.pbkdf2_hmac('sha256', pwd_bytes, salt_bytes, 100000)
    return derived.hex(), salt


def verify_password(password: str, salt: str, password_hash: str) -> bool:
    """
    Memverifikasi password dengan membandingkan hash menggunakan timing attack resistant compare_digest.
    """
    computed_hash, _ = hash_password(password, salt)
    return hmac.compare_digest(computed_hash, password_hash)


def get_user_by_username(username: str):
    """Mengambil data pengguna dari database berdasarkan username."""
    with get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM users WHERE username = ?", (username.strip().lower(),))
        row = cursor.fetchone()
        return dict(row) if row else None


def create_user(username: str, password: str, full_name: str, role: str = 'admin') -> bool:
    """Membuat pengguna baru dengan password yang telah di-hash secara aman."""
    username = username.strip().lower()
    if not username or len(password) < 6:
        return False

    existing = get_user_by_username(username)
    if existing:
        return False

    pwd_hash, salt = hash_password(password)
    now_str = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

    with get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("""
            INSERT INTO users (username, password_hash, salt, full_name, role, created_at, last_login)
            VALUES (?, ?, ?, ?, ?, ?, NULL)
        """, (username, pwd_hash, salt, full_name, role, now_str))
        conn.commit()
        return True


def ensure_default_admin():
    """Memastikan setidaknya ada 1 akun admin default saat sistem pertama kali dijalankan."""
    with get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT COUNT(*) FROM users")
        count = cursor.fetchone()[0]
        if count == 0:
            # Buat akun admin awal: username 'admin', password 'Admin123!'
            create_user('admin', 'Admin123!', 'Administrator Sistem', 'admin')
            log_activity('system', 'INITIALIZE_ADMIN', 'Membuat akun administrator default awal')


def authenticate(username: str, password: str) -> dict | None:
    """
    Memvalidasi kredensial login pengguna.
    Mengembalikan data user jika valid, atau None jika gagal.
    """
    user = get_user_by_username(username)
    if not user:
        return None

    if verify_password(password, user['salt'], user['password_hash']):
        # Perbarui waktu last_login
        now_str = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        with get_connection() as conn:
            cursor = conn.cursor()
            cursor.execute("UPDATE users SET last_login = ? WHERE id = ?", (now_str, user['id']))
            conn.commit()
        return user
    return None


def login(username: str, password: str) -> tuple[bool, str]:
    """
    Melakukan proses login dan mencatat session state di Streamlit.
    """
    user = authenticate(username, password)
    if user:
        st.session_state['authenticated'] = True
        st.session_state['user'] = {
            'id': user['id'],
            'username': user['username'],
            'full_name': user['full_name'],
            'role': user['role']
        }
        log_activity(user['username'], 'LOGIN_SUCCESS', 'Berhasil login ke sistem')
        return True, "Login berhasil."
    else:
        log_activity(username, 'LOGIN_FAILED', 'Gagal login (kredensial salah)')
        return False, "Username atau kata sandi tidak cocok."


def logout():
    """Mengakhiri sesi admin dengan aman."""
    current_user = st.session_state.get('user', {})
    username = current_user.get('username', 'anonymous')
    log_activity(username, 'LOGOUT', 'Admin keluar dari sistem')

    st.session_state['authenticated'] = False
    st.session_state['user'] = None
    st.rerun()


def is_authenticated() -> bool:
    """Mengecek apakah sesi saat ini telah terautentikasi."""
    return st.session_state.get('authenticated', False) is True


def get_current_user() -> dict | None:
    """Mengembalikan data pengguna yang sedang login."""
    return st.session_state.get('user', None)


def require_admin():
    """
    Guard pelindung halaman admin.
    Mencegah akses halaman hanya dengan mengubah URL atau menu jika belum login.
    """
    if not is_authenticated():
        st.error("⛔ **Akses Terbatas: Hak Akses Administrator Diperlukan!**")
        st.info("Halaman ini hanya dapat diakses oleh Admin yang terautentikasi. Silakan masuk melalui halaman login.")
        col1, col2 = st.columns([1, 4])
        with col1:
            if st.button("🔑 Buka Halaman Login", type="primary"):
                st.session_state['redirect_to_login'] = True
                st.rerun()
        st.stop()


def change_admin_password(username: str, old_password: str, new_password: str) -> tuple[bool, str]:
    """Memperbarui kata sandi akun admin."""
    if len(new_password) < 6:
        return False, "Kata sandi baru minimal harus 6 karakter."

    user = get_user_by_username(username)
    if not user:
        return False, "Pengguna tidak ditemukan."

    if not verify_password(old_password, user['salt'], user['password_hash']):
        return False, "Kata sandi lama tidak sesuai."

    new_hash, new_salt = hash_password(new_password)
    with get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute(
            "UPDATE users SET password_hash = ?, salt = ? WHERE username = ?",
            (new_hash, new_salt, username)
        )
        conn.commit()

    log_activity(username, 'PASSWORD_CHANGE', 'Kata sandi berhasil diubah')
    return True, "Kata sandi berhasil diperbarui."


# Pastikan akun admin awal tersedia
ensure_default_admin()

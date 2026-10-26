"""
Modul Database SQLite untuk Diabetes AI Analyzer.
Mengelola:
- Akun pengguna/admin dan hak akses
- Riwayat prediksi pengunjung (tanpa data identitas pribadi / PII)
- Log aktivitas sistem & admin (audit trail)
- Statistik pengunjung & metrik penggunaan
- Pengaturan konfigurasi sistem & retensi data
"""

import os
import sqlite3
from datetime import datetime, timedelta
import pandas as pd

DB_DIR = os.path.join(os.path.dirname(__file__), 'data')
DB_PATH = os.path.join(DB_DIR, 'app_database.db')


def get_connection():
    """Membuka koneksi ke database SQLite."""
    os.makedirs(DB_DIR, exist_ok=True)
    conn = sqlite3.connect(DB_PATH)
    conn.row_factory = sqlite3.Row
    return conn


def init_db():
    """Inisialisasi tabel-tabel database jika belum ada."""
    with get_connection() as conn:
        cursor = conn.cursor()

        # 1. Tabel Pengguna (Admin)
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password_hash TEXT NOT NULL,
                salt TEXT NOT NULL,
                full_name TEXT NOT NULL,
                role TEXT NOT NULL DEFAULT 'admin',
                created_at TEXT NOT NULL,
                last_login TEXT
            )
        ''')

        # 2. Tabel Riwayat Prediksi
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS predictions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                timestamp TEXT NOT NULL,
                high_chol INTEGER NOT NULL,
                chol_check INTEGER NOT NULL,
                bmi REAL NOT NULL,
                smoker INTEGER NOT NULL,
                stroke INTEGER NOT NULL,
                heart_disease INTEGER NOT NULL,
                phys_activity INTEGER NOT NULL,
                fruits INTEGER NOT NULL,
                veggies INTEGER NOT NULL,
                hvy_alcohol INTEGER NOT NULL,
                any_healthcare INTEGER NOT NULL,
                no_doc_cost INTEGER NOT NULL,
                gen_hlth INTEGER NOT NULL,
                ment_hlth INTEGER NOT NULL,
                phys_hlth INTEGER NOT NULL,
                diff_walk INTEGER NOT NULL,
                sex INTEGER NOT NULL,
                age INTEGER NOT NULL,
                education INTEGER NOT NULL,
                income INTEGER NOT NULL,
                prediction_result INTEGER NOT NULL,
                prediction_label TEXT NOT NULL,
                model_version TEXT NOT NULL,
                consent_given INTEGER NOT NULL DEFAULT 1
            )
        ''')

        # 3. Tabel Log Aktivitas Admin (Audit Trail)
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS activity_logs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                timestamp TEXT NOT NULL,
                username TEXT NOT NULL,
                action TEXT NOT NULL,
                details TEXT
            )
        ''')

        # 4. Tabel Pelacak Pengunjung (Statistik)
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS visitor_counter (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                page_name TEXT NOT NULL,
                timestamp TEXT NOT NULL
            )
        ''')

        # 5. Tabel Pengaturan Sistem
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS system_settings (
                key TEXT PRIMARY KEY,
                value TEXT NOT NULL,
                updated_at TEXT NOT NULL,
                updated_by TEXT NOT NULL
            )
        ''')

        # Inisialisasi Pengaturan Default jika belum ada
        default_settings = [
            ('site_title', 'Diabetes AI Analyzer', 'system'),
            ('developer_name', 'Tim Data Mining & AI Healthcare', 'system'),
            ('retention_days', '90', 'system'),
            ('privacy_notice_enabled', 'true', 'system'),
            ('institution_name', 'Universitas Nurul Jadid (UNUJA)', 'system'),
            ('support_email', 'admin@diabetes-analyzer.ai', 'system')
        ]
        for key, val, user in default_settings:
            cursor.execute('''
                INSERT OR IGNORE INTO system_settings (key, value, updated_at, updated_by)
                VALUES (?, ?, ?, ?)
            ''', (key, val, datetime.now().strftime("%Y-%m-%d %H:%M:%S"), user))

        conn.commit()


# ==========================================
# MANAJEMEN PENGUNJUNG & STATISTIK
# ==========================================

def track_visit(page_name="home"):
    """Mencatat kunjungan halaman tanpa menyimpan IP atau data pribadi."""
    try:
        with get_connection() as conn:
            cursor = conn.cursor()
            cursor.execute(
                "INSERT INTO visitor_counter (page_name, timestamp) VALUES (?, ?)",
                (page_name, datetime.now().strftime("%Y-%m-%d %H:%M:%S"))
            )
            conn.commit()
    except Exception as e:
        print(f"Error tracking visit: {e}")


def get_visitor_counts():
    """Mengambil total kunjungan dan kunjungan hari ini."""
    today = datetime.now().strftime("%Y-%m-%d")
    with get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT COUNT(*) FROM visitor_counter")
        total = cursor.fetchone()[0]

        cursor.execute("SELECT COUNT(*) FROM visitor_counter WHERE timestamp LIKE ?", (f"{today}%",))
        today_visits = cursor.fetchone()[0]

        cursor.execute("""
            SELECT page_name, COUNT(*) as count 
            FROM visitor_counter 
            GROUP BY page_name 
            ORDER BY count DESC
        """)
        per_page = {row['page_name']: row['count'] for row in cursor.fetchall()}

        return {
            "total_visits": total,
            "today_visits": today_visits,
            "per_page": per_page
        }


# ==========================================
# MANAJEMEN PREDIKSI
# ==========================================

def save_prediction(input_data: dict, result: int, label: str, model_version: str = "1.0.0", consent: int = 1):
    """Menyimpan hasil prediksi jika pengguna menyetujui."""
    if not consent:
        return None

    now_str = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    with get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute('''
            INSERT INTO predictions (
                timestamp, high_chol, chol_check, bmi, smoker, stroke,
                heart_disease, phys_activity, fruits, veggies, hvy_alcohol,
                any_healthcare, no_doc_cost, gen_hlth, ment_hlth, phys_hlth,
                diff_walk, sex, age, education, income,
                prediction_result, prediction_label, model_version, consent_given
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ''', (
            now_str,
            int(input_data.get('HighChol', 0)),
            int(input_data.get('CholCheck', 0)),
            float(input_data.get('BMI', 0)),
            int(input_data.get('Smoker', 0)),
            int(input_data.get('Stroke', 0)),
            int(input_data.get('HeartDiseaseorAttack', 0)),
            int(input_data.get('PhysActivity', 0)),
            int(input_data.get('Fruits', 0)),
            int(input_data.get('Veggies', 0)),
            int(input_data.get('HvyAlcoholConsump', 0)),
            int(input_data.get('AnyHealthcare', 0)),
            int(input_data.get('NoDocbcCost', 0)),
            int(input_data.get('GenHlth', 0)),
            int(input_data.get('MentHlth', 0)),
            int(input_data.get('PhysHlth', 0)),
            int(input_data.get('DiffWalk', 0)),
            int(input_data.get('Sex', 0)),
            int(input_data.get('Age', 0)),
            int(input_data.get('Education', 0)),
            int(input_data.get('Income', 0)),
            int(result),
            label,
            model_version,
            consent
        ))
        conn.commit()
        return cursor.lastrowid


def get_prediction_history(limit=100, offset=0, filter_result=None, search_keyword=None, start_date=None, end_date=None):
    """Mengambil riwayat prediksi dengan fitur filter, pencarian, dan paginasi."""
    with get_connection() as conn:
        query = "SELECT * FROM predictions WHERE 1=1"
        params = []

        if filter_result is not None and filter_result != "Semua":
            query += " AND prediction_result = ?"
            params.append(1 if filter_result == "Berisiko Tinggi" or filter_result == 1 else 0)

        if start_date:
            query += " AND timestamp >= ?"
            params.append(f"{start_date} 00:00:00")

        if end_date:
            query += " AND timestamp <= ?"
            params.append(f"{end_date} 23:59:59")

        if search_keyword:
            query += " AND (id LIKE ? OR prediction_label LIKE ?)"
            params.extend([f"%{search_keyword}%", f"%{search_keyword}%"])

        query += " ORDER BY id DESC LIMIT ? OFFSET ?"
        params.extend([limit, offset])

        df = pd.read_sql_query(query, conn, params=params)
        return df


def count_predictions_total(filter_result=None, search_keyword=None, start_date=None, end_date=None):
    """Menghitung total baris prediksi sesuai filter."""
    with get_connection() as conn:
        cursor = conn.cursor()
        query = "SELECT COUNT(*) FROM predictions WHERE 1=1"
        params = []

        if filter_result is not None and filter_result != "Semua":
            query += " AND prediction_result = ?"
            params.append(1 if filter_result == "Berisiko Tinggi" or filter_result == 1 else 0)

        if start_date:
            query += " AND timestamp >= ?"
            params.append(f"{start_date} 00:00:00")

        if end_date:
            query += " AND timestamp <= ?"
            params.append(f"{end_date} 23:59:59")

        if search_keyword:
            query += " AND (id LIKE ? OR prediction_label LIKE ?)"
            params.extend([f"%{search_keyword}%", f"%{search_keyword}%"])

        cursor.execute(query, params)
        return cursor.fetchone()[0]


def delete_prediction(prediction_id: int):
    """Menghapus catatan prediksi tertentu berdasarkan ID."""
    with get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("DELETE FROM predictions WHERE id = ?", (prediction_id,))
        conn.commit()
        return cursor.rowcount > 0


def delete_predictions_by_retention(retention_days: int):
    """Menghapus data prediksi yang lebih lama dari kebijakan retensi."""
    cutoff_date = (datetime.now() - timedelta(days=retention_days)).strftime("%Y-%m-%d %H:%M:%S")
    with get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("DELETE FROM predictions WHERE timestamp < ?", (cutoff_date,))
        deleted_count = cursor.rowcount
        conn.commit()
        return deleted_count


def get_prediction_statistics():
    """Mengambil ringkasan statistik komprehensif riwayat prediksi."""
    with get_connection() as conn:
        cursor = conn.cursor()

        # Total
        cursor.execute("SELECT COUNT(*) FROM predictions")
        total = cursor.fetchone()[0]

        # Kelas 0 (Risiko Rendah)
        cursor.execute("SELECT COUNT(*) FROM predictions WHERE prediction_result = 0")
        class_0 = cursor.fetchone()[0]

        # Kelas 1 (Berisiko Tinggi)
        cursor.execute("SELECT COUNT(*) FROM predictions WHERE prediction_result = 1")
        class_1 = cursor.fetchone()[0]

        # Prediksi Hari Ini
        today = datetime.now().strftime("%Y-%m-%d")
        cursor.execute("SELECT COUNT(*) FROM predictions WHERE timestamp LIKE ?", (f"{today}%",))
        today_count = cursor.fetchone()[0]

        # Aktivitas 7 hari terakhir
        cursor.execute("""
            SELECT SUBSTR(timestamp, 1, 10) as date, 
                   COUNT(*) as total,
                   SUM(CASE WHEN prediction_result = 1 THEN 1 ELSE 0 END) as high_risk,
                   SUM(CASE WHEN prediction_result = 0 THEN 1 ELSE 0 END) as low_risk
            FROM predictions
            WHERE timestamp >= date('now', '-7 days')
            GROUP BY SUBSTR(timestamp, 1, 10)
            ORDER BY date ASC
        """)
        daily_trends = [dict(row) for row in cursor.fetchall()]

        # Rata-rata BMI
        cursor.execute("SELECT AVG(bmi) FROM predictions")
        avg_bmi = cursor.fetchone()[0] or 0.0

        return {
            "total_predictions": total,
            "low_risk_count": class_0,
            "high_risk_count": class_1,
            "today_predictions": today_count,
            "daily_trends": daily_trends,
            "avg_bmi": round(avg_bmi, 1)
        }


# ==========================================
# MANAJEMEN AUDIT LOG AKTIVITAS
# ==========================================

def log_activity(username: str, action: str, details: str = ""):
    """Mencatat aktivitas admin ke dalam audit log."""
    try:
        now_str = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        with get_connection() as conn:
            cursor = conn.cursor()
            cursor.execute(
                "INSERT INTO activity_logs (timestamp, username, action, details) VALUES (?, ?, ?, ?)",
                (now_str, username, action, details)
            )
            conn.commit()
    except Exception as e:
        print(f"Error logging activity: {e}")


def get_activity_logs(limit=100):
    """Mengambil daftar log aktivitas admin."""
    with get_connection() as conn:
        df = pd.read_sql_query(
            "SELECT * FROM activity_logs ORDER BY id DESC LIMIT ?",
            conn,
            params=[limit]
        )
        return df


# ==========================================
# PENGATURAN SISTEM
# ==========================================

def get_setting(key: str, default: str = ""):
    """Mengambil nilai konfigurasi sistem."""
    with get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT value FROM system_settings WHERE key = ?", (key,))
        row = cursor.fetchone()
        return row['value'] if row else default


def set_setting(key: str, value: str, updated_by: str = "admin"):
    """Memperbarui atau menambah konfigurasi sistem."""
    now_str = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    with get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute('''
            INSERT INTO system_settings (key, value, updated_at, updated_by)
            VALUES (?, ?, ?, ?)
            ON CONFLICT(key) DO UPDATE SET
                value = excluded.value,
                updated_at = excluded.updated_at,
                updated_by = excluded.updated_by
        ''', (key, str(value), now_str, updated_by))
        conn.commit()


def get_all_settings():
    """Mengambil seluruh pengaturan sistem."""
    with get_connection() as conn:
        cursor = conn.cursor()
        cursor.execute("SELECT key, value, updated_at, updated_by FROM system_settings")
        return {row['key']: row['value'] for row in cursor.fetchall()}


# Inisialisasi Database saat modul dimuat
init_db()

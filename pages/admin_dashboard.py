"""
Dashboard Utama Administrator - Diabetes AI Analyzer
Menampilkan statistik komprehensif, metrik pengunjung, riwayat prediksi,
dan grafik visualisasi interaktif Plotly.
"""

import os
import sys
import json
import pandas as pd
import plotly.express as px
import plotly.graph_objects as go
import streamlit as st

_parent_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _parent_dir not in sys.path:
    sys.path.insert(0, _parent_dir)

from auth import require_admin, get_current_user
from database import (
    get_visitor_counts,
    get_prediction_statistics,
    get_activity_logs
)

METADATA_PATH = os.path.join(os.path.dirname(os.path.dirname(__file__)), 'models', 'metadata.json')


def load_model_metadata():
    if os.path.exists(METADATA_PATH):
        try:
            with open(METADATA_PATH, 'r', encoding='utf-8') as f:
                return json.load(f)
        except Exception:
            return None
    return None


def render_admin_dashboard_page():
    # 1. Validasi Hak Akses Admin (Route Guard)
    require_admin()

    user = get_current_user()
    admin_name = user.get('full_name', 'Administrator') if user else 'Administrator'

    st.markdown(f"""
    <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 800; color: #FFFFFF; margin-bottom: 0.3rem;">
                📊 Dashboard Administrator
            </h1>
            <p style="color: var(--text-muted); font-size: 1rem; margin: 0;">
                Selamat datang kembali, <strong>{admin_name}</strong>. Berikut adalah ikhtisar operasional sistem secara real-time.
            </p>
        </div>
        <div style="margin-top: 0.5rem;">
            <span class="badge badge-risk-low">● Sistem Beroperasi Normal</span>
        </div>
    </div>
    """, unsafe_allow_html=True)

    # 2. Ambil Data Statistik dari Database & Model
    visitor_data = get_visitor_counts()
    pred_stats = get_prediction_statistics()
    meta = load_model_metadata()

    total_visitors = visitor_data.get('total_visits', 0)
    total_preds = pred_stats.get('total_predictions', 0)
    low_risk = pred_stats.get('low_risk_count', 0)
    high_risk = pred_stats.get('high_risk_count', 0)
    avg_bmi = pred_stats.get('avg_bmi', 0.0)

    metrics = (meta.get('metrics') or {}) if isinstance(meta, dict) else {}
    acc_val = metrics.get('accuracy', 0.0) if isinstance(metrics, dict) else 0.0
    try:
        model_acc = float(acc_val) * 100 if acc_val is not None else 0.0
    except (ValueError, TypeError):
        model_acc = 0.0
    model_status = meta.get('status', 'Belum Ada') if isinstance(meta, dict) else 'Belum Ada'

    # 3. Baris Metrik Utama (5 Box Card)
    m1, m2, m3, m4, m5 = st.columns(5)
    with m1:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">👥 Pengunjung</div>
            <div class="metric-value">{total_visitors:,}</div>
            <div class="metric-sub">Hari ini: +{visitor_data.get('today_visits', 0)}</div>
        </div>
        """, unsafe_allow_html=True)

    with m2:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">📋 Total Prediksi</div>
            <div class="metric-value">{total_preds:,}</div>
            <div class="metric-sub">Hari ini: +{pred_stats.get('today_predictions', 0)}</div>
        </div>
        """, unsafe_allow_html=True)

    with m3:
        st.markdown(f"""
        <div class="metric-box" style="border-color: rgba(16, 185, 129, 0.4);">
            <div class="metric-label" style="color: #6EE7B7;">✅ Risiko Rendah</div>
            <div class="metric-value" style="color: #34D399;">{low_risk:,}</div>
            <div class="metric-sub">{round((low_risk / total_preds * 100), 1) if total_preds > 0 else 0}% dari total</div>
        </div>
        """, unsafe_allow_html=True)

    with m4:
        st.markdown(f"""
        <div class="metric-box" style="border-color: rgba(239, 68, 68, 0.4);">
            <div class="metric-label" style="color: #FCA5A5;">⚠️ Risiko Tinggi</div>
            <div class="metric-value" style="color: #F87171;">{high_risk:,}</div>
            <div class="metric-sub">{round((high_risk / total_preds * 100), 1) if total_preds > 0 else 0}% dari total</div>
        </div>
        """, unsafe_allow_html=True)

    with m5:
        st.markdown(f"""
        <div class="metric-box">
            <div class="metric-label">⚡ Akurasi XGBoost</div>
            <div class="metric-value">{model_acc:.1f}%</div>
            <div class="metric-sub">{model_status}</div>
        </div>
        """, unsafe_allow_html=True)

    st.markdown("<br>", unsafe_allow_html=True)

    # 4. Baris Grafik Plotly Interaktif
    st.markdown("### 📈 Visualisasi Aktivitas & Distribusi Data")
    col_chart1, col_chart2 = st.columns([1, 1])

    # Tema Gelap Plotly
    plotly_layout_dark = dict(
        paper_bgcolor='rgba(17, 34, 64, 0.6)',
        plot_bgcolor='rgba(17, 34, 64, 0.6)',
        font=dict(color='#F1F5F9', family='Plus Jakarta Sans, sans-serif'),
        margin=dict(l=20, r=20, t=40, b=20)
    )

    with col_chart1:
        st.markdown("##### 🍩 Distribusi Hasil Klasifikasi Risiko")
        if total_preds > 0:
            donut_df = pd.DataFrame({
                'Kategori': ['Risiko Rendah (Non-Diabetes)', 'Berisiko Tinggi (Diabetes)'],
                'Jumlah': [low_risk, high_risk],
                'Warna': ['#14B8A6', '#EF4444']
            })
            fig_donut = px.pie(
                donut_df,
                values='Jumlah',
                names='Kategori',
                hole=0.55,
                color='Kategori',
                color_discrete_map={
                    'Risiko Rendah (Non-Diabetes)': '#14B8A6',
                    'Berisiko Tinggi (Diabetes)': '#EF4444'
                }
            )
            fig_donut.update_layout(**plotly_layout_dark)
            st.plotly_chart(fig_donut, use_container_width=True)
        else:
            st.info("Belum ada data prediksi yang tercatat untuk ditampilkan pada grafik donat.")

    with col_chart2:
        st.markdown("##### 📊 Tren Prediksi Harian (7 Hari Terakhir)")
        trends = pred_stats.get('daily_trends', [])
        if trends:
            trend_df = pd.DataFrame(trends)
            fig_bar = go.Figure()
            fig_bar.add_trace(go.Bar(
                x=trend_df['date'],
                y=trend_df['low_risk'],
                name='Risiko Rendah',
                marker_color='#14B8A6'
            ))
            fig_bar.add_trace(go.Bar(
                x=trend_df['date'],
                y=trend_df['high_risk'],
                name='Risiko Tinggi',
                marker_color='#EF4444'
            ))
            fig_bar.update_layout(
                barmode='stack',
                xaxis_title='Tanggal',
                yaxis_title='Jumlah Prediksi',
                **plotly_layout_dark
            )
            st.plotly_chart(fig_bar, use_container_width=True)
        else:
            st.info("Belum ada tren prediksi harian yang tersimpan dalam 7 hari terakhir.")

    # 5. Ringkasan Performa Model & Log Aktivitas
    st.markdown("---")
    col_inf1, col_inf2 = st.columns([1, 1])

    with col_inf1:
        st.markdown("### 🔬 Ringkasan Performa Model XGBoost")
        if meta and isinstance(meta.get('metrics'), dict):
            m = meta['metrics']
            st.markdown(f"""
            <div class="analyzer-card">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Algoritma:</span>
                    <strong>{meta.get('algorithm', meta.get('model_name', 'XGBoost'))}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Tipe Model:</span>
                    <strong>{meta.get('model_type', 'XGBClassifier')}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Tanggal Latih:</span>
                    <strong>{meta.get('training_date', '-')}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Akurasi Total:</span>
                    <strong style="color: var(--teal-light);">{m.get('accuracy', 0)*100:.2f}%</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Recall / Sensitivitas (Kelas 1):</span>
                    <strong style="color: var(--teal-light);">{m.get('recall_class_1', 0)*100:.2f}%</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Precision (Kelas 1):</span>
                    <strong>{m.get('precision_class_1', 0)*100:.2f}%</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>Macro F1-Score:</span>
                    <strong>{m.get('macro_f1', 0):.4f}</strong>
                </div>
            </div>
            """, unsafe_allow_html=True)
        else:
            st.warning("Metadata model belum ditemukan.")

    with col_inf2:
        st.markdown("### 📋 Log Aktivitas Terakhir")
        logs_df = get_activity_logs(limit=5)
        if not logs_df.empty:
            for _, row in logs_df.iterrows():
                st.markdown(f"""
                <div style="border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding: 0.5rem 0;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-dim);">
                        <span>🕒 {row['timestamp']}</span>
                        <span>👤 <strong>{row['username']}</strong></span>
                    </div>
                    <div style="font-size: 0.9rem; color: var(--text-main); margin-top: 0.2rem;">
                        <strong>{row['action']}</strong>: {row['details']}
                    </div>
                </div>
                """, unsafe_allow_html=True)
        else:
            st.info("Belum ada log aktivitas admin yang tercatat.")


if __name__ == '__main__':
    render_admin_dashboard_page()

"""
Halaman Edukasi Diabetes - Diabetes AI Analyzer
Pusat literasi kesehatan tentang diabetes melitus, faktor risiko,
penjelasan 20 indikator klinis, pencegahan, dan referensi medis resmi.
"""

import os
import sys
import streamlit as st

_parent_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if _parent_dir not in sys.path:
    sys.path.insert(0, _parent_dir)

from database import track_visit

def render_education_page():
    track_visit("Edukasi")

    st.markdown("""
    <div style="margin-bottom: 1.5rem;">
        <h1 style="font-size: 2rem; font-weight: 800; color: #FFFFFF; margin-bottom: 0.3rem;">
            📚 Pusat Edukasi & Literasi Diabetes Melitus
        </h1>
        <p style="color: var(--text-muted); font-size: 1rem;">
            Pelajari konsep medis diabetes, faktor risiko klinis, makna indikator kesehatan, 
            serta langkah pencegahan berbasis panduan resmi kementerian kesehatan dan organisasi dunia.
        </p>
    </div>
    """, unsafe_allow_html=True)

    tab_edu1, tab_edu2, tab_edu3, tab_edu4 = st.tabs([
        "🩺 Mengenal Diabetes", 
        "🔍 Faktor Risiko & 21 Indikator", 
        "🛡️ Panduan Pencegahan", 
        "📖 Pemeriksaan & Sumber Resmi"
    ])

    with tab_edu1:
        st.markdown("### Apa itu Diabetes Melitus?")
        st.write("""
        **Diabetes Melitus (DM)** adalah penyakit metabolik kronis yang ditandai dengan kadar glukosa darah 
        (gula darah) yang tinggi melebihi nilai normal (hiperglikemia). Kondisi ini terjadi karena pankreas 
        tidak memproduksi insulin yang cukup, atau tubuh tidak dapat menggunakan insulin yang dihasilkan secara efektif.
        """)

        col_t1, col_t2 = st.columns(2)
        with col_t1:
            st.markdown("""
            <div class="analyzer-card">
                <div class="card-title">1️⃣ Diabetes Tipe 1</div>
                <p class="card-description">
                    Reaksi autoimun di mana sistem imun tubuh menyerang sel beta pankreas pembuat insulin. 
                    Penderita membutuhkan terapi insulin seumur hidup. Umumnya terdeteksi pada usia anak-anak 
                    atau remaja.
                </p>
            </div>
            """, unsafe_allow_html=True)
            st.markdown("""
            <div class="analyzer-card">
                <div class="card-title">2️⃣ Diabetes Tipe 2 (Terbanyak: ~90-95%)</div>
                <p class="card-description">
                    Tubuh mengalami resistensi insulin, di mana sel tidak merespons insulin dengan optimal. 
                    Sangat erat kaitannya dengan obesitas (BMI tinggi), kurang aktivitas fisik, pola makan, 
                    dan faktor penuaan.
                </p>
            </div>
            """, unsafe_allow_html=True)

        with col_t2:
            st.markdown("""
            <div class="analyzer-card">
                <div class="card-title">3️⃣ Pre-Diabetes</div>
                <p class="card-description">
                    Kondisi di mana kadar gula darah lebih tinggi dari normal tetapi belum memenuhi kriteria diabetes. 
                    <strong>Kondisi ini masih dapat dipulihkan (reversibel)</strong> jika dilakukan intervensi 
                    gaya hidup secara intensif.
                </p>
            </div>
            """, unsafe_allow_html=True)
            st.markdown("""
            <div class="analyzer-card">
                <div class="card-title">4️⃣ Diabetes Gestasional</div>
                <p class="card-description">
                    Kadar gula darah tinggi yang terjadi selama masa kehamilan pada wanita yang sebelumnya tidak 
                    memiliki riwayat diabetes. Meningkatkan risiko komplikasi kehamilan dan diabetes tipe 2 di kemudian hari.
                </p>
            </div>
            """, unsafe_allow_html=True)

        st.markdown("#### Gejala Klasik Diabetes (Trias 3P):")
        st.markdown("""
        - 🥤 **Poliuria (Sering Kencing):** Frekuensi buang air kecil meningkat drastis, terutama pada malam hari.
        - 💧 **Polidipsia (Sering Haus):** Rasa haus terus menerus yang tidak kunjung hilang karena dehidrasi osmotik.
        - 🍽️ **Polifagia (Sering Lapar):** Nafsu makan meningkat tajam namun berat badan justru terus menyusut tanpa sebab jelas.
        - 😴 **Kelelahan Ekstrem:** Tubuh tidak dapat memanfaatkan glukosa sebagai sumber energi utama sel.
        - 👁️ **Penglihatan Kabur:** Perubahan kadar cairan pada lensa mata akibat tingginya konsentrasi gula darah.
        """)

    with tab_edu2:
        st.markdown("### Mengapa 21 Indikator Ini Dipilih?")
        st.write("""
        Model XGBoost kami menggunakan 21 indikator dari Behavioral Risk Factor Surveillance System (BRFSS) CDC, 
        survei kesehatan masyarakat tahunan terbesar di dunia. Berikut korelasi klinisnya:
        """)

        with st.expander("1. Tekanan Darah Tinggi / Hipertensi (HighBP)", expanded=True):
            st.write("""
            Hipertensi adalah salah satu komponen kunci dalam **sindrom metabolik**. Kerusakan mikrovaskular akibat 
            tekanan darah tinggi secara langsung memperparah resistensi insulin dan mempercepat komplikasi kardiovaskular 
            pada penyandang diabetes.
            """)

        with st.expander("2. Indeks Massa Tubuh (BMI)"):
            st.write("""
            BMI adalah indikator utama adipositas. Jaringan lemak berlebih, terutama lemak viseral di area perut, 
            menghasilkan sitokin pro-inflamasi dan asam lemak bebas yang secara langsung memicu **resistensi insulin**.
            - BMI < 18.5: Kurang berat badan
            - BMI 18.5 - 22.9: Normal (Standar Asia)
            - BMI 23.0 - 24.9: Berisiko (Overweight)
            - BMI ≥ 25.0: Obesitas Tingkat I & II
            """)

        with st.expander("2. Kolesterol Tinggi & Cek Kolesterol (HighChol & CholCheck)"):
            st.write("""
            Dislipidemia (kadar kolesterol dan trigliserida abnormal) sering berjalan beriringan dengan diabetes 
            dalam sindrom metabolik. Penderita kolesterol tinggi memiliki risiko aterosklerosis dan penyakit jantung 
            yang jauh lebih tinggi.
            """)

        with st.expander("3. Kondisi Kesehatan Umum (GenHlth, PhysHlth, MentHlth)"):
            st.write("""
            Penilaian subjektif atas kesehatan umum terbukti berkorelasi kuat dengan penyakit metabolik kronis. 
            Stres mental berkepanjangan meningkatkan hormon kortisol dan epinefrin yang menaikkan gula darah.
            """)

        with st.expander("4. Gaya Hidup (Merokok, Olahraga, Buah, Sayur, Alkohol)"):
            st.write("""
            - **Merokok:** Nikotin merusak sel beta pankreas dan memperburuk penyerapan glukosa.
            - **Aktivitas Fisik:** Otot yang berkontraksi saat olahraga dapat menyerap glukosa darah tanpa tergantung pada insulin.
            - **Serat Buah & Sayur:** Memperlambat penyerapan karbohidrat sehingga mencegah lonjakan (*spike*) glukosa.
            - **Alkohol Berlebih:** Mengganggu fungsi detoksifikasi hati dan metabolisme glikogen.
            """)

        with st.expander("5. Demografi & Sosial Ekonomi (Usia, Jenis Kelamin, Pendidikan, Pendapatan)"):
            st.write("""
            - **Usia:** Sensitivitas insulin dan massa otot cenderung menurun seiring bertambahnya usia (>45 tahun).
            - **Sosial Ekonomi:** Mempengaruhi aksesibilitas pangan bergizi, jaminan kesehatan, dan fasilitas skrining rutin.
            """)

    with tab_edu3:
        st.markdown("### 🛡️ Langkah Nyata Pencegahan Diabetes Tipe 2")
        p1, p2 = st.columns(2)

        with p1:
            st.markdown("""
            #### 🥗 1. Pola Makan Sehat (Piring Makanku)
            - **Batasi Gula Tambahan:** Maksimal 4 sendok makan (50 gram) gula per hari sesuai anjuran Kemenkes RI.
            - **Pilih Karbohidrat Kompleks:** Beras merah, oat, ubi jalar, roti gandum utuh alih-alih nasi putih berlebih atau tepung olahan.
            - **Perbanyak Sayuran:** Isi separuh piring dengan sayuran hijau berserat tinggi.
            - **Hindari Lemak Trans & Jenuh:** Kurangi makanan gorengan dan santan kental berulang.
            """)

            st.markdown("""
            #### 🏃 2. Aktif Bergerak
            - Minimal **150 menit per minggu** olahraga intensitas sedang (jalan cepat, bersepeda, berenang).
            - Kurangi waktu duduk terlalu lama (*sedentary lifestyle*). Berdirilah setiap 45 menit.
            """)

        with p2:
            st.markdown("""
            #### ⚖️ 3. Jaga Berat Badan Ideal
            - Penurunan berat badan sebesar **5% - 7%** pada individu yang kelebihan berat badan terbukti menurunkan risiko diabetes tipe 2 hingga **58%**!
            - Ukur lingkar pinggang secara rutin (Pria < 90 cm, Wanita < 80 cm).
            """)

            st.markdown("""
            #### 🚭 4. Hindari Rokok & Kelola Stres
            - Berhenti merokok menurunkan risiko resistensi insulin dan penyakit kardiovaskular secara drastis.
            - Tidur cukup 7-8 jam per hari. Kurang tidur kronis memicu resistensi insulin dan meningkatkan hormon ghrelin (lapar).
            """)

    with tab_edu4:
        st.markdown("### 🔬 Pemeriksaan Medis Berkala")
        st.markdown("""
        Bagi individu berusia di atas 35 tahun atau memiliki faktor risiko, disarankan melakukan pemeriksaan laboratorium:
        """)

        st.markdown("""
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Jenis Pemeriksaan</th>
                    <th>Nilai Normal</th>
                    <th>Pre-Diabetes</th>
                    <th>Diabetes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>HbA1c (Rata-rata 3 Bulan)</strong></td>
                    <td>&lt; 5.7%</td>
                    <td>5.7% - 6.4%</td>
                    <td>&ge; 6.5%</td>
                </tr>
                <tr>
                    <td><strong>Gula Darah Puasa (GDP)</strong></td>
                    <td>&lt; 100 mg/dL</td>
                    <td>100 - 125 mg/dL</td>
                    <td>&ge; 126 mg/dL</td>
                </tr>
                <tr>
                    <td><strong>Gula Darah 2 Jam PP (TTGO)</strong></td>
                    <td>&lt; 140 mg/dL</td>
                    <td>140 - 199 mg/dL</td>
                    <td>&ge; 200 mg/dL</td>
                </tr>
                <tr>
                    <td><strong>Gula Darah Sewaktu (GDS)</strong></td>
                    <td>&lt; 140 mg/dL</td>
                    <td>140 - 199 mg/dL</td>
                    <td>&ge; 200 mg/dL + Gejala</td>
                </tr>
            </tbody>
        </table>
        """, unsafe_allow_html=True)

        st.markdown("---")
        st.markdown("### 🌐 Sumber Informasi Kesehatan Terpercaya:")
        st.markdown("""
        - 🇮🇩 **Kementerian Kesehatan Republik Indonesia (Kemenkes RI)**: [kemkes.go.id](https://www.kemkes.go.id)
        - 🩺 **Perkumpulan Endokrinologi Indonesia (PERKENI)**: [perkeni.org](https://perkeni.org)
        - 🌍 **World Health Organization (WHO) - Diabetes Fact Sheet**: [who.int/news-room/fact-sheets/detail/diabetes](https://www.who.int/news-room/fact-sheets/detail/diabetes)
        - 🇺🇸 **American Diabetes Association (ADA)**: [diabetes.org](https://diabetes.org)
        - 📊 **Centers for Disease Control and Prevention (CDC - BRFSS)**: [cdc.gov/diabetes](https://www.cdc.gov/diabetes)
        """)


if __name__ == '__main__':
    render_education_page()

<?php

namespace App\Services\Agent\Tools;

class MedicalKnowledgeRetrievalTool
{
    /**
     * Basis Pengetahuan Klinis Komprehensif (PERKENI 2021, ADA Standards of Care 2024, WHO).
     * Meliputi seluruh kelas farmakoterapi (oral & injeksi), komorbiditas, gejala, komplikasi,
     * diet, puasa Ramadhan, interpretasi laboratorium, dan gaya hidup.
     */
    protected array $knowledgeBase = [
        
        // ==============================================================
        // 1. KELAS FARMAKOTERAPI: SEMUA OBAT DIABETES
        // ==============================================================
        
        'metformin' => [
            'topic' => 'Metformin (Biguanida) - Lini Pertama Terapi Oral',
            'reference' => 'Konsensus PERKENI 2021 & ADA 2024 - Guideline Farmakoterapi Lini Pertama',
            'content' => "Metformin (nama dagang: Glucophage, Formin, Nevox) adalah obat lini pertama (gold standard) bagi diabetisi tipe 2.\n"
                . "• **Mekanisme:** Menurunkan produksi glukosa oleh hati (glukoneogenesis hepatik) dan meningkatkan sensitivitas reseptor insulin di sel otot/lemak.\n"
                . "• **Aturan Pakai:** Wajib diminum BERSAMA atau SEGERA SESUDAH makan (dosis awal lazim 500 mg 1-2x/hari, dapat ditingkatkan bertahap hingga maksimal 2000-2500 mg/hari).\n"
                . "• **Keunggulan:** Tidak menyebabkan hipoglikemia, berat badan netral/cenderung turun, serta melindungi pembuluh darah jantung.\n"
                . "• **Efek Samping Umum:** Rasa begah, mual, diare sementara (hilang setelah adaptasi 1-2 minggu). Jangan hentikan tiba-tiba tanpa konfirmasi dokter.\n"
                . "• **Kontraindikasi:** Pasien gangguan ginjal berat dengan laju filtrasi glomerulus eGFR < 30 mL/menit/1.73m² (risiko asidosis laktat)."
        ],

        'sulfonylurea' => [
            'topic' => 'Sulfonilurea (Glimepiride, Glibenclamide, Gliclazide)',
            'reference' => 'PERKENI 2021 (Hal. 44) - Obat Pemacu Sekresi Insulin (Sekretagog)',
            'content' => "Golongan Sulfonilurea (Glimepiride/Amaryl, Gliclazide/Diamicron MR, Glibenclamide/Daonil) bekerja merangsang sel beta pankreas memproduksi lebih banyak insulin.\n"
                . "• **Aturan Minum:** Diminum 15–30 menit SEBELUM sarapan (makan pagi) dengan air putih.\n"
                . "• **Peringatan Utama:** Sangat berisiko memicu HIPOGLIKEMIA (gula darah drop < 70 mg/dL) bila pasien terlambat makan atau berolahraga berat tanpa asupan karbohidrat.\n"
                . "• **Efek Lain:** Kenaikan berat badan ringan (1-2 kg) karena sifat insulinotropik.\n"
                . "• **Catatan Khusus:** Glimepiride dan Gliclazide MR lebih disukai daripada Glibenclamide karena risiko hipoglikemianya jauh lebih rendah dan lebih ramah ginjal pada lansia."
        ],

        'sglt2_inhibitor' => [
            'topic' => 'SGLT-2 Inhibitor (Empagliflozin / Jardiance, Dapagliflozin / Forxiga)',
            'reference' => 'ADA Standards of Care 2024 - Cardiorenal Protection in Type 2 Diabetes',
            'content' => "SGLT-2 Inhibitor (Empagliflozin/Jardiance, Dapagliflozin/Forxiga) adalah golongan modern dengan manfaat ganda pada jantung dan ginjal.\n"
                . "• **Mekanisme Unik:** Menghambat penyerapan kembali glukosa di ginjal, sehingga kelebihan gula dibuang langsung lewat urine (glukosuria terukur ~70 gram gula/hari).\n"
                . "• **Manfaat Klinis:** Menurunkan HbA1c (0.6-1.0%), menurunkan berat badan (2-3 kg), menurunkan tekanan darah, dan terbukti signifikan mencegah perburukan gagal jantung serta penyakit ginjal kronis (CKD).\n"
                . "• **Aturan Minum:** 1 kali sehari di pagi hari, sebelum atau sesudah makan.\n"
                . "• **Edukasi Pasien:** Wajib minum air putih minimal 2–2.5 liter per hari dan menjaga higienitas area kelamin setelah buang air kecil untuk mencegah Infeksi Saluran Kemih (ISK) atau infeksi jamur genital."
        ],

        'dpp4_inhibitor' => [
            'topic' => 'DPP-4 Inhibitor (Sitagliptin / Januvia, Vildagliptin / Galvus, Linagliptin / Trajenta)',
            'reference' => 'PERKENI 2021 & ADA 2024 - Terapi Berbasis Hormon Inkretin',
            'content' => "Golongan DPP-4 Inhibitor (Sitagliptin/Januvia, Vildagliptin/Galvus, Linagliptin/Trajenta) bekerja mempertahankan kadar hormon alami GLP-1 dalam tubuh.\n"
                . "• **Mekanisme:** Mencegah enzim DPP-4 memecah hormon inkretin, sehingga insulin dilepaskan hanya saat gula darah naik sehabis makan, sekaligus menekan pelepasan hormon glukagon.\n"
                . "• **Keunggulan:** Aman dan nyaman untuk lansia; netral terhadap berat badan; risiko hipoglikemia hampir 0% jika digunakan sebagai monoterapi.\n"
                . "• **Aturan Minum:** 1 kali sehari (Sitagliptin 100 mg / Linagliptin 5 mg) atau 2 kali sehari (Vildagliptin 50 mg), fleksibel sebelum atau sesudah makan.\n"
                . "• **Linagliptin:** Keunggulan khusus diekskresikan lewat empedu, sehingga tidak memerlukan penyesuaian dosis pada pasien gangguan ginjal berat."
        ],

        'glp1_agonist' => [
            'topic' => 'GLP-1 RA (Semaglutide / Ozempic / Rybelsus, Liraglutide / Victoza)',
            'reference' => 'ADA 2024 & FDA Guidelines - Incretin Mimetic Therapy',
            'content' => "GLP-1 Receptor Agonist (Semaglutide/Ozempic suntik atau Rybelsus tablet, Liraglutide/Victoza) adalah terapi mutakhir dengan efikasi penurunan gula dan berat badan tertinggi.\n"
                . "• **Mekanisme:** Meniru hormon GLP-1: merangsang insulin saat gula naik, memperlambat pengosongan lambung, dan menekan pusat nafsu makan di otak (rasa kenyang bertahan lama).\n"
                . "• **Manfaat Utama:** Penurunan HbA1c drastis (1.5-2.0%), penurunan berat badan hingga 10-15%, serta proteksi kuat terhadap serangan jantung dan stroke pada diabetisi berisiko kardiovaskular tinggi.\n"
                . "• **Cara Pakai:** Injeksi subkutan mingguan (Ozempic) atau harian (Victoza). Versi oral (Rybelsus) diminum saat perut kosong bangun tidur dengan sedikit air putih (maks. 120 ml), lalu puasa makan/minum selama 30 menit.\n"
                . "• **Efek Samping:** Mual, kembung, begah pada minggu awal adaptasi. Dosis dimulai dari paling rendah lalu dinaikkan bertahap."
        ],

        'acarbose_tzd' => [
            'topic' => 'Acarbose (Glucobay) & Pioglitazone (Actos)',
            'reference' => 'PERKENI 2021 (Hal. 46-48)',
            'content' => "• **Acarbose (Glucobay):** Penghambat alfa-glukosidase di dinding usus. Memperlambat pemecahan karbohidrat menjadi glukosa sehingga mencegah lonjakan gula 2 jam pasca makan (GD2PP). Diminum bersama SUAPAN PERTAMA makan utama. Efek samping: sering buang angin (flatus) dan perut kembung karena fermentasi karbohidrat di usus besar.\n"
                . "• **Pioglitazone (Actos):** Golongan Tiazolidindion (TZD). Mengaktifkan reseptor PPAR-gamma di sel otot dan lemak untuk membuka pintu masuk glukosa tanpa memicu hipoglikemia. Diminum 1x sehari. Kontraindikasi pada penderita gagal jantung (NYHA class III/IV) karena dapat menyebabkan retensi cairan (bengkak pada pergelangan kaki)."
        ],

        'insulin_therapy' => [
            'topic' => 'Terapi Insulin Lengkap: Jenis, Cara Suntik, dan Penyimpanan',
            'reference' => 'Konsensus PERKENI 2021 - Bab Terapi Insulin & Teknik Injeksi FITTER',
            'content' => "Insulin adalah hormon biologis alami penurun glukosa darah paling poten tanpa batasan dosis maksimal (titrasi sesuai kebutuhan target).\n\n"
                . "**1. Klasifikasi Jenis Insulin:**\n"
                . "• *Basal / Kerja Panjang:* Glargine (Lantus), Detemir (Levemir), Degludec (Tresiba). Bekerja stabil 24 jam tanpa puncak lonjakan, disuntikkan 1x sehari di jam yang sama (umumnya malam menjelang tidur).\n"
                . "• *Prandial / Kerja Cepat:* Aspart (NovoRapid), Lispro (Humalog), Glulisine (Apidra). Menangani lonjakan gula sehabis makan, disuntikkan 5-15 menit sebelum makan utama.\n"
                . "• *Campuran (Premixed):* NovoMix 30 (70% basal + 30% cepat) disuntikkan 2x sehari sebelum sarapan dan makan malam.\n\n"
                . "**2. Titik & Teknik Penyuntikan:**\n"
                . "• Lokasi terbaik: Area perut (2-3 jari di luar pusar - penyerapan paling konsisten), paha samping luar, atau lengan atas belakang.\n"
                . "• Wajib **rotasi lokasi suntik** setiap hari untuk mencegah lipohipertrofi (benjolan lemak keras di bawah kulit yang menghambat penyerapan insulin).\n"
                . "• Cubit kulit ringan jika kurus, suntikkan jarum tegak lurus (90°), tekan tombol pen sampai angka 0, lalu tahan 6-10 detik sebelum mencabut jarum.\n\n"
                . "**3. Cara Penyimpanan:**\n"
                . "• Pen insulin cadangan/belum dibuka: Simpan di rak pintu kulkas suhu 2–8°C (JANGAN di freezer / jangan sampai beku).\n"
                . "• Pen insulin yang sedang aktif dipakai: Cukup simpan di suhu ruangan sejuk (< 30°C) terhindar dari sinar matahari langsung, tahan hingga 28–30 hari."
        ],

        'comorbidity_drugs' => [
            'topic' => 'Obat Pendamping Diabetisi: Kolesterol (Statin) & Ginjal/Tensi (ACE-i / ARB)',
            'reference' => 'ADA 2024 Cardiovascular Disease and Risk Management',
            'content' => "Penyandang diabetes memerlukan perlindungan komprehensif terhadap pembuluh darah jantung dan ginjal:\n"
                . "• **Statin (Atorvastatin 20-40 mg, Simvastatin, Rosuvastatin):** Wajib diberikan pada diabetisi usia ≥ 40 tahun untuk menurunkan kolesterol jahat (target LDL < 70 mg/dL atau < 55 mg/dL) guna mencegah plak aterosklerosis penyebab serangan jantung dan stroke.\n"
                . "• **Proteksi Ginjal & Tensi (Candesartan, Telmisartan, Ramipril, Captopril):** Golongan ARB/ACE-inhibitor ini tidak hanya menurunkan tekanan darah (target < 130/80 mmHg), tetapi secara spesifik melebarkan pembuluh arteriol eferen ginjal untuk mengurangi tekanan intraglomerulus dan menghentikan kebocoran albumin (cegah gagal ginjal cuci darah).\n"
                . "• **Aspirin Dosis Rendah (80-100 mg):** Diberikan sebagai prevensi sekunder bagi penderita diabetes yang telah memiliki riwayat penyakit jantung koroner atau stroke."
        ],

        'herbal_supplements' => [
            'topic' => 'Obat Herbal, Jamu, dan Suplemen Alami untuk Diabetes',
            'reference' => 'Kajian Fitofarmaka BPOM & Journal of Evidence-Based Integrative Medicine',
            'content' => "Banyak pasien menanyakan efektivitas bahan herbal alami:\n"
                . "• **Kayu Manis (Cinnamomum burmannii):** Mengandung polifenol yang dapat meningkatkan sensitivitas reseptor insulin secara ringan (konsumsi 1/2 sdt per hari).\n"
                . "• **Pare (Momordica charantia):** Mengandung senyawa mirip insulin bernama *polipeptida-p* dan *charantin* yang membantu penyerapan glukosa ke jaringan perifer.\n"
                . "• **Daun Salam & Daun Kelor:** Kaya antioksidan flavonoid yang meredakan inflamasi vaskular sistemik.\n"
                . "• **Suplemen Alpha Lipoic Acid (ALA):** Dosis 600 mg/hari terbukti klinis meredakan gejala nyeri dan kebas saraf pada neuropati diabetik.\n"
                . "⚠️ **PERINGATAN DOKTER:** Bahan herbal hanya bersifat komplementer (pendukung gaya hidup sehat). **JANGAN PERNAH** menghentikan atau mengganti obat medis utama/insulin dengan ramuan herbal tanpa persetujuan dokter penanggung jawab, karena herbal tidak terukur dosis pastinya dan dapat memicu lonjakan gula darah fatal (KAD/HHS)."
        ],

        // ==============================================================
        // 2. SEMUA TENTANG PENYAKIT DIABETES (GEJALA, KOMPLIKASI, LAB)
        // ==============================================================

        'diabetes_basics_types' => [
            'topic' => 'Klasifikasi & Perbedaan Jenis Diabetes (Tipe 1, Tipe 2, Gestasional, Prediabetes)',
            'reference' => 'World Health Organization (WHO) & PERKENI 2021',
            'content' => "• **Diabetes Tipe 1:** Terjadi akibat reaksi autoimun di mana sistem imun tubuh menghancurkan sel beta pankreas sendiri, sehingga tubuh TIDAK BISA memproduksi insulin sama sekali. Umumnya terdiagnosis pada usia anak/remaja dan mutlak membutuhkan terapi insulin seumur hidup.\n"
                . "• **Diabetes Tipe 2 (90-95% kasus):** Terjadi akibat *resistensi insulin* (pankreas memproduksi insulin, tetapi sel-sel tubuh resisten/kebal terhadap kerjanya akibat timbunan lemak dan kurang gerak). Dipicu faktor genetik dan gaya hidup tidak sehat.\n"
                . "• **Diabetes Gestasional:** Terjadi pada ibu hamil (trimester 2-3) akibat hormon plasenta yang melawan insulin. Wajib dikendalikan ketat demi mencegah bayi lahir dengan berat badan berlebih (> 4 kg/makrosomia) dan komplikasi persalinan.\n"
                . "• **Prediabetes:** Kondisi di mana gula darah sudah di atas normal tetapi belum masuk kategori diabetes. Bersifat **100% REVERSIBEL (bisa kembali normal sepenuhnya)** melalui penurunan berat badan 5-7% dan olahraga rutin."
        ],

        'symptoms_diagnosis' => [
            'topic' => 'Gejala Klasik Trias 3P dan Tanda-Tanda Awal Diabetes',
            'reference' => 'Pedoman Diagnosis PERKENI 2021',
            'content' => "Gejala klinis diabetes melitus terbagi menjadi:\n"
                . "1. **Trias Klasik 3P:**\n"
                . "   • *Poliuria:* Sering buang air kecil, terutama terbangun berkali-kali di malam hari (nokturia), karena ginjal membuang kelebihan gula lewat air kencing.\n"
                . "   • *Polidipsia:* Rasa haus terus-menerus dan tenggorokan kering akibat dehidrasi cairan tubuh yang keluar lewat urine.\n"
                . "   • *Polifagia:* Cepat merasa lapar namun berat badan justru turun drastis (karena sel tubuh kelaparan energi dan memecah otot/lemak sebagai bahan bakar).\n\n"
                . "2. **Gejala Tambahan:**\n"
                . "   • Tubuh lemas dan mudah mengantuk setelah makan.\n"
                . "   • Penglihatan tiba-tiba buram atau berganti-ganti fokus kacamata.\n"
                . "   • Luka di kaki atau kulit yang sangat lambat sembuh bahkan membusuk.\n"
                . "   • Sensasi kesemutan, kebas, atau rasa panas terbakar pada ujung jari kaki dan tangan.\n"
                . "   • Gatal-gatal di lipatan paha/area kelamin akibat infeksi jamur (*Candida*)."
        ],

        'lab_markers' => [
            'topic' => 'Nilai Baku Standar Hasil Tes Laboratorium (GDP, GD2PP, GDS, HbA1c)',
            'reference' => 'Standar Diagnosis Laboratorium PERKENI 2021 & ADA 2024',
            'content' => "Berikut tabel nilai rujukan resmi diagnosis diabetes melalui sampel darah vena:\n\n"
                . "| Parameter Uji | Normal Sehat | Rentang Prediabetes | Kriteria Diabetes Melitus |\n"
                . "| :--- | :--- | :--- | :--- |\n"
                . "| **HbA1c (Rerata 3 Bulan)** | < 5.7% | 5.7% – 6.4% | **≥ 6.5%** |\n"
                . "| **Gula Darah Puasa (GDP / 8-10 jam)** | < 100 mg/dL | 100 – 125 mg/dL | **≥ 126 mg/dL** |\n"
                . "| **Gula Darah 2 Jam PP (GD2PP / TTGO)** | < 140 mg/dL | 140 – 199 mg/dL | **≥ 200 mg/dL** |\n"
                . "| **Gula Darah Sewaktu (GDS acak)** | < 140 mg/dL | 140 – 199 mg/dL | **≥ 200 mg/dL (+ Gejala 3P)** |\n\n"
                . "**Target Terapi bagi Penderita Diabetes Tipe 2:**\n"
                . "• HbA1c: < 7.0% (atau < 6.5% untuk pasien muda tanpa riwayat hipoglikemia)\n"
                . "• GDP sebelum makan: 80 – 130 mg/dL\n"
                . "• GD2PP pasca makan: < 180 mg/dL\n"
                . "• Tekanan Darah: < 130/80 mmHg"
        ],

        'acute_hypo_hyper' => [
            'topic' => 'Kegawatdaruratan: Hipoglikemia (Aturan 15-15) vs KAD & HHS',
            'reference' => 'Endocrine Society Clinical Practice Guidelines & ADA 2024',
            'content' => "**1. HIPOGLIKEMIA (Gula Darah Rendah < 70 mg/dL):**\n"
                . "• *Tanda:* Keringat dingin membasahi pakaian, gemetar hebat (tremor), jantung berdebar kencang (palpitasi), pusing melayang, gelisah, pucat, hingga hilang kesadaran.\n"
                . "• *Tindakan Darurat (Aturan 15-15):*\n"
                . "  1. Segera minumkan **15 gram gula murni** (setara: 1/2 gelas teh manis dengan 1 sdm gula pasir, atau 1/2 cangkir jus buah manis, atau 3 buah permen manis).\n"
                . "  2. Tunggu 15 menit, lalu ukur kembali gula darah.\n"
                . "  3. Jika masih < 70 mg/dL, ulangi pemberian 15 gram gula sekali lagi.\n"
                . "  4. Begitu gula darah > 70 mg/dL, segera berikan makanan padat berprotein (nasi/roti + telur/ayam) agar gula tidak drop kembali.\n"
                . "  5. Jika pasien tidak sadar: **JANGAN** memasukkan cairan ke dalam mulut (risiko tersedak masuk paru-paru). Segera bawa ke IGD RS terdekat!\n\n"
                . "**2. KETOASIDOSIS DIABETIK (KAD) & HHS (Gula Darah Terlalu Tinggi):**\n"
                . "• Ditandai gula darah melonjak sangat tinggi (> 250-600 mg/dL), nafas berbau buah manis menyengat (aseton), nafas cepat dan dalam (Kussmaul), mual muntah hebat, nyeri perut, dan linglung. Merupakan kondisi gawat darurat medis yang memerlukan infus cairan dan insulin intravena di rumah sakit."
        ],

        'foot_care_neuropathy' => [
            'topic' => 'Neuropati Diabetik & Panduan Perawatan Kaki (Cegah Amputasi)',
            'reference' => 'International Working Group on the Diabetic Foot (IWGDF) Guidelines',
            'content' => "Kerusakan saraf (neuropati) membuat kaki diabetisi mati rasa (kebas). Pasien sering tidak menyadari tertusuk duri, lecet sepatu, atau terkena air panas, yang jika dibiarkan dapat berubah menjadi borok membusuk (ulkus diabetikum) hingga berujung amputasi.\n\n"
                . "**7 Langkah Emas Menjaga Kaki Diabetes:**\n"
                . "1. Periksa telapak kaki, tumit, dan sela-sela jari kaki setiap hari menggunakan cermin (cek apakah ada luka, lecet, kemerahan, atau kapalan).\n"
                . "2. Cuci kaki setiap hari dengan air hangat suam kuku (bukan air mendidih) dan sabun lembut, lalu keringkan sela-sela jari hingga benar-benar tuntas.\n"
                . "3. Oleskan pelembap/lotion pada punggung dan telapak kaki agar tidak pecah-pecah, tetapi **HINDARI** mengoleskan pelembap di sela-sela jari kaki.\n"
                . "4. Potong kuku kaki secara **LURUS** (jangan memotong melengkung ke sudut dalam untuk mencegah cantengan/infeksi kuku).\n"
                . "5. **JANGAN PERNAH BERJALAN TANPA ALAS KAKI (NYEKER)**, baik di dalam rumah apalagi di luar ruangan.\n"
                . "6. Gunakan kaus kaki katun bersih tanpa lipatan jahitan ketat dan sepatu berukuran pas yang empuk.\n"
                . "7. Jika timbul luka sekecil apapun, bersihkan dengan antiseptik povidone iodine, tutup kasa steril, dan segera periksakan ke dokter klinik luka."
        ],

        'diet_nutrition_plate' => [
            'topic' => 'Panduan Pola Makan, Konsep Piring Sehat T, dan Pantangan Makanan',
            'reference' => 'Kementerian Kesehatan RI & Perkumpulan Endokrinologi Indonesia (PERKENI)',
            'content' => "Kunci utama kestabilan gula darah adalah pemilihan jenis dan porsi karbohidrat:\n\n"
                . "**1. Konsep Piring Model T (Setiap Sesi Makan Utama):**\n"
                . "• **1/2 Piring:** Sayuran non-tepung tinggi serat (bayam, kangkung, brokoli, sawi, buncis, timun, tomat) yang memperlambat penyerapan glukosa.\n"
                . "• **1/4 Piring:** Karbohidrat kompleks berserat tinggi (nasi merah, nasi jagung, beras shirataki, oat, kentang rebus dengan kulit, ubi jalar rebus).\n"
                . "• **1/4 Piring:** Sumber protein rendah lemak (ikan laut, dada ayam tanpa kulit, tahu, tempe, telur rebus, daging sapi tanpa lemak).\n\n"
                . "**2. Makanan & Minuman Pantangan Utama:**\n"
                . "• Minuman manis kemasan: Teh manis botol, kopi saset bergula/krimer kental manis, soda, minuman boba (1 gelas boba bisa setara 15-20 sendok teh gula!).\n"
                . "• Karbohidrat olahan sederhana: Nasi putih porsi jumbo, tepung terigu, roti manis, lontong/ketan berlebih, mie instan campur nasi.\n"
                . "• Gorengan berminyak kelapa sawit daur ulang (lemak trans memicu resistensi insulin pada dinding sel).\n\n"
                . "**3. Pemanis yang Aman:**\n"
                . "• Daun Stevia alami, Sukralosa, atau Eritritol tidak menaikkan kadar gula darah dan aman dikonsumsi.\n"
                . "• *Catatan Madu:* Madu asli tetap mengandung 80% fruktosa dan glukosa sederhana yang langsung menaikkan gula darah, sehingga tidak boleh diminum berlebihan."
        ],

        'fasting_ramadan' => [
            'topic' => 'Panduan Puasa Ramadhan bagi Pasien Diabetes',
            'reference' => 'IDF-DAR Practical Guidelines: Diabetes and Ramadan 2021',
            'content' => "Pasien diabetes yang ingin berpuasa Ramadhan wajib memahami aturan penyesuaian klinis:\n\n"
                . "**Kapan Harus Segera Membatalkan Puasa (Wajib Medis):**\n"
                . "1. Kadar glukosa darah < 70 mg/dL kapan saja selama berpuasa.\n"
                . "2. Kadar glukosa darah > 300 mg/dL (waspada dehidrasi dan ketoasidosis).\n"
                . "3. Muncul gejala lemas hebat, gemetar, keringat dingin, pusing melayang, atau bingung mental.\n\n"
                . "**Jadwal Penyesuaian Obat:**\n"
                . "• *Metformin 1x sehari:* Diminum saat berbuka puasa.\n"
                . "• *Metformin 2x sehari:* 1 tablet saat berbuka dan 1 tablet saat sahur.\n"
                . "• *Sulfonilurea (Glimepiride):* Dosis utama diminum saat berbuka puasa. Dosis saat sahur dikurangi separuhnya atas rekomendasi dokter untuk mencegah gula drop di siang hari.\n"
                . "• *Insulin Basal (Lantus):* Disuntikkan saat berbuka puasa dengan pengurangan dosis 15-20%."
        ],

        'remission_cure' => [
            'topic' => 'Apakah Diabetes Bisa Sembuh Total? Penjelasan Fakta Remisi Medis',
            'reference' => 'Consensus Report: Definition and Interpretation of Remission in Type 2 Diabetes (Diabetes Care 2021)',
            'content' => "Pertanyaan paling sering: *'Apakah diabetes bisa sembuh total?'*\n\n"
                . "Secara terminologi medis klinis resmi, kata yang tepat adalah **REMISI DIABETES**, bukan sembuh permanen seperti flu.\n"
                . "• **Definisi Remisi:** Kondisi di mana kadar HbA1c bertahan stabil di bawah 6.5% selama minimal 3 bulan berturut-turut TANPA mengonsumsi obat antidiabetes sama sekali.\n"
                . "• **Bagaimana Cara Mencapainya?** Studi klinis landmark (DiRECT Trial) membuktikan bahwa penurunan berat badan intensif sebesar 10–15 kg pada diabetisi tipe 2 yang baru terdiagnosis (< 5 tahun) berhasil mengosongkan timbunan lemak berlebih di organ hati dan pankreas, sehingga sel beta pankreas pulih dan kembali mampu memproduksi insulin dengan normal.\n"
                . "• **Peringatan Penting:** Jika setelah remisi pasien kembali ke pola hidup lama (makan manis, berat badan melonjak naik, kurang gerak), resistensi insulin akan kambuh kembali. Oleh karena itu, kontrol gaya hidup harus dipertahankan seumur hidup."
        ],

        'exercise_guidelines' => [
            'topic' => 'Panduan Olahraga & Aktivitas Fisik untuk Menurunkan Gula Darah',
            'reference' => 'American College of Sports Medicine (ACSM) & ADA Guidelines',
            'content' => "Saat otot berkontraksi saat berolahraga, pintu transporter glukosa (GLUT-4) terbuka secara mandiri menyerap gula darah langsung tanpa memerlukan insulin. Efek sensitivitas ini bertahan hingga 48 jam pasca olahraga.\n\n"
                . "• **Target Mingguan:** Minimal **150 menit per minggu** latihan aerobik intensitas sedang (contoh: jalan cepat, senam diabetes, bersepeda statis, berenang) dibagi 3-5 hari per minggu (tidak boleh bolong lebih dari 2 hari berturut-turut).\n"
                . "• **Latihan Beban:** Lakukan latihan penguatan otot 2-3 kali seminggu (squat, push up dinding, angkat dumbbell ringan) karena massa otot adalah tempat pembakaran glukosa terbesar di tubuh.\n"
                . "• **Waktu Terbaik:** 30–60 menit setelah makan utama, saat kadar glukosa darah mencapai puncak sirkulasi.\n"
                . "• **Kapan Dilarang Olahraga:** Jika gula darah > 250 mg/dL dengan keton dalam urine, atau jika gula darah < 100 mg/dL (wajib makan biskuit gandum atau camilan sehat terlebih dahulu)."
        ],

        'website_features_guide' => [
            'topic' => 'Panduan Fitur & Cara Penggunaan Website Diabetes Uji Coba',
            'reference' => 'Sistem Skrining Cerdas Diabetes Uji Coba (Berbasis Machine Learning XGBoost)',
            'content' => "Website **Diabetes Uji Coba** menyediakan fitur skrining mandiri dan edukasi kesehatan:\n\n"
                . "1. **Kuesioner Cek Risiko (Fitur Utama):**\n"
                . "   • *Langkah 1 (Jenis Kelamin):* Memilih jenis kelamin biologis untuk kalibrasi dasar.\n"
                . "   • *Langkah 2 (Data Fisik):* Memasukkan usia, tinggi badan, dan berat badan untuk menghitung Indeks Massa Tubuh (BMI).\n"
                . "   • *Langkah 3 (Klinis & Gaya Hidup):* Menjawab pertanyaan seputar tekanan darah tinggi, riwayat keluarga, aktivitas fisik, kolesterol, dan faktor terkait.\n"
                . "   • *Hasil Prediksi:* Model Machine Learning XGBoost mengevaluasi 21 indikator CDC BRFSS dan mengelompokkan risiko ke dalam kategori **Rendah**, **Sedang**, atau **Tinggi**.\n\n"
                . "2. **Agentic AI Health Co-Pilot (Tombol AI di Kanan Bawah):** Asisten interaktif cerdas untuk menjawab pertanyaan seputar diabetes, obat, makanan sehat, dan cara membaca hasil pemeriksaan.\n"
                . "3. **5 Pilar Pengendalian & Kalkulator Kalori:** Alat interaktif di Beranda untuk simulasi kebutuhan energi harian dan panduan gaya hidup.\n"
                . "4. **Pusat Berita & Artikel Edukasi:** Kumpulan referensi klinis seputar pola hidup dan pencegahan komplikasi.\n\n"
                . "⚠️ *Penting:* Hasil skrining adalah estimasi risiko awal berbasis kecerdasan buatan dan bukan vonis diagnosis klinis dari dokter."
        ],

        'risk_factors_triggers' => [
            'topic' => 'Faktor Pemicu & Strategi Pencegahan Diabetes Melitus',
            'reference' => 'Konsensus Pencegahan Diabetes PERKENI & World Health Organization (WHO)',
            'content' => "Risiko diabetes timbul dari interaksi antara faktor yang dapat dimodifikasi dan faktor genetik:\n\n"
                . "1. **Faktor Pemicu yang Dapat Diubah (Modifiable):**\n"
                . "   • *Pola Makan Tinggi Gula & Olahan:* Konsumsi gula berlebih memicu lonjakan glukosa darah dan membebani kerja pankreas.\n"
                . "   • *Kurang Aktivitas Fisik:* Gaya hidup sedenter (banyak duduk) menurunkan sensitivitas reseptor insulin di otot.\n"
                . "   • *Berat Badan Berlebih & Obesitas Sentral:* Lemak viseral di sekitar rongga perut melepaskan zat pro-inflamasi yang menyebabkan resistensi insulin.\n"
                . "   • *Hipertensi & Dislipidemia:* Tekanan darah tinggi dan kolesterol tinggi mempercepat peradangan pembuluh darah.\n\n"
                . "2. **Faktor yang Tidak Dapat Diubah (Non-Modifiable):**\n"
                . "   • *Riwayat Keluarga:* Memiliki orang tua atau saudara kandung dengan diabetes meningkatkan kecenderungan genetik.\n"
                . "   • *Usia:* Risiko meningkat seiring bertambahnya usia, terutama di atas 35 tahun.\n\n"
                . "3. **Langkah Pencegahan Utama:** Menerapkan diet seimbang model piring T, aktif bergerak 150 menit per minggu, menjaga berat badan ideal, dan rutin melakukan cek gula darah puasa minimal 1 tahun sekali."
        ]
    ];

    /**
     * Mengambil entri pengetahuan medis yang cocok dengan kueri pengguna.
     */
    public function execute(array $params): array
    {
        $rawQuery = $params['topic'] ?? $params['query'] ?? '';
        $query = strtolower(trim($rawQuery));
        $matchedEntries = [];

        // 1. Pemetaan Kata Kunci Cerdas Multi-Kategori
        $keywordMap = [
            'metformin' => ['metformin', 'glucophage', 'formin', 'nevox', 'biguanid', 'lini pertama', 'obat lini pertama'],
            'sulfonylurea' => ['glimepirid', 'glimepiride', 'amaryl', 'glibenklamid', 'glibenclamide', 'daonil', 'diamicron', 'gliclazid', 'gliclazide', 'sulfonilurea'],
            'sglt2_inhibitor' => ['jardiance', 'empagliflozin', 'forxiga', 'dapagliflozin', 'sglt', 'sglt2', 'kencing manis buang gula', 'urine manis'],
            'dpp4_inhibitor' => ['januvia', 'sitagliptin', 'galvus', 'vildagliptin', 'trajenta', 'linagliptin', 'dpp4', 'dpp-4', 'inkretin'],
            'glp1_agonist' => ['ozempic', 'semaglutide', 'semaglutid', 'victoza', 'liraglutide', 'liraglutid', 'rybelsus', 'glp1', 'glp-1', 'suntik kurus'],
            'acarbose_tzd' => ['acarbose', 'akarbosa', 'glucobay', 'pioglitazone', 'pioglitazon', 'actos', 'tzd'],
            'insulin_therapy' => ['insulin', 'suntik', 'lantus', 'novorapid', 'humalog', 'levemir', 'tresiba', 'apidra', 'novomix', 'jarum', 'pen insulin', 'cara suntik', 'simpan insulin'],
            'comorbidity_drugs' => ['statin', 'atorvastatin', 'simvastatin', 'rosuvastatin', 'candesartan', 'captopril', 'ramipril', 'telmisartan', 'aspirin', 'kolesterol', 'tensi', 'darah tinggi', 'hipertensi'],
            'herbal_supplements' => ['herbal', 'jamu', 'pare', 'kayu manis', 'cinnamon', 'daun salam', 'daun kelor', 'bawang hitam', 'sirsak', 'alpha lipoic', 'suplemen', 'alami'],
            'symptoms_diagnosis' => ['gejala', 'tanda', 'ciri', '3p', 'poliuria', 'polidipsia', 'polifagia', 'kencing', 'haus', 'lapar', 'kebas', 'kesemutan', 'buram', 'mata kabur'],
            'lab_markers' => ['hba1c', 'gdp', 'gds', 'gd2pp', 'lab', 'puasa', 'angka normal', 'rentang normal', 'kadar gula', 'cek darah', 'berapa normal'],
            'acute_hypo_hyper' => ['hipoglikemia', 'hipo', 'drop', 'keringat dingin', 'gemetar', 'lemas', 'aturan 15', 'kad', 'ketoasidosis', 'gawat darurat', 'pingsan'],
            'foot_care_neuropathy' => ['kaki', 'ulkus', 'borok', 'amputasi', 'cantengan', 'lecet', 'neuropati', 'kebas', 'sepatu', 'kaos kaki', 'luka diabetes', 'luka bernanah'],
            'diet_nutrition_plate' => ['makan', 'makanan', 'menu', 'diet', 'pantangan', 'piring t', 'sayur', 'buah', 'nasi merah', 'karbohidrat', 'boba', 'minuman manis', 'madu', 'stevia', 'pemanis', 'kopi', 'teh'],
            'fasting_ramadan' => ['puasa', 'ramadan', 'ramadhan', 'sahur', 'berbuka', 'batal puasa', 'aturan minum obat saat puasa'],
            'remission_cure' => ['sembuh', 'remisi', 'bisa sembuh', 'permanen', 'hilang', 'sembuh total'],
            'exercise_guidelines' => ['olahraga', 'senam', 'jalan', 'fitness', 'aerobik', 'lari', 'aktivitas fisik', 'beban'],
            'diabetes_basics_types' => ['tipe 1', 'tipe 2', 'gestasional', 'prediabetes', 'hamil', 'apa itu diabetes', 'penyebab diabetes', 'ibu hamil'],
            'website_features_guide' => ['fitur', 'website', 'aplikasi', 'cara cek', 'cara pakai', 'kuesioner', 'skrining', 'xgboost', 'cara menggunakan', 'baca hasil', 'arti skor', 'skor saya', 'prediksi saya'],
            'risk_factors_triggers' => ['pemicu', 'faktor risiko', 'faktor pemicu', 'faktor apa', 'kenapa kena', 'mencegah', 'pencegahan', 'cegah diabetes', 'kenapa bisa']
        ];

        foreach ($keywordMap as $key => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($query, $kw)) {
                    if (isset($this->knowledgeBase[$key]) && !in_array($this->knowledgeBase[$key], $matchedEntries)) {
                        $matchedEntries[] = $this->knowledgeBase[$key];
                    }
                    break;
                }
            }
        }

        // 2. Kueri umum tentang obat / farmakoterapi diabetes
        if (str_contains($query, 'obat') || str_contains($query, 'pengobatan') || str_contains($query, 'farmasi') || str_contains($query, 'resep') || str_contains($query, 'medikasi')) {
            $drugKeys = ['metformin', 'sulfonylurea', 'sglt2_inhibitor', 'dpp4_inhibitor', 'insulin_therapy'];
            foreach ($drugKeys as $dk) {
                if (isset($this->knowledgeBase[$dk]) && !in_array($this->knowledgeBase[$dk], $matchedEntries)) {
                    $matchedEntries[] = $this->knowledgeBase[$dk];
                }
            }
        }

        // 3. Pencarian Teks Bebas jika belum menemukan
        if (empty($matchedEntries)) {
            foreach ($this->knowledgeBase as $key => $entry) {
                if (str_contains(strtolower($entry['topic']), $query) || str_contains(strtolower($entry['content']), $query)) {
                    $matchedEntries[] = $entry;
                }
            }
        }

        // 4. Fallback Cerdas PERKENI
        if (empty($matchedEntries)) {
            $matchedEntries[] = $this->knowledgeBase['metformin'];
            $matchedEntries[] = $this->knowledgeBase['symptoms_diagnosis'];
            $matchedEntries[] = $this->knowledgeBase['diet_nutrition_plate'];
        }

        return [
            'success' => true,
            'tool' => 'MedicalKnowledgeRetrievalTool',
            'data' => [
                'query' => $query,
                'entries' => $matchedEntries
            ]
        ];
    }
}

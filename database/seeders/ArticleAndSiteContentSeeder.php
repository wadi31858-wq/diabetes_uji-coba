<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\SiteContent;

class ArticleAndSiteContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Initial Articles jika masih kosong
        if (Article::count() === 0) {
            $articles = [
                [
                    'slug' => 'kaki-diabetes-ciri-ciri-penyakit-gula-pada-kaki',
                    'title' => 'Kaki Diabetes : Ciri-ciri Penyakit Gula pada Kaki',
                    'category' => 'Diabetes',
                    'date' => '7/11/2024',
                    'updated_at_display' => '23 Jul 2024',
                    'image' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=1000&auto=format&fit=crop&q=80',
                    'summary' => 'Kenali 7 komplikasi klinis pada saraf dan sirkulasi kaki (ulkus diabetikum) sejak dini guna mencegah risiko amputasi.',
                    'content' => '<p>Diabetes adalah penyakit yang dikenal sebagai "ibu dari segala penyakit" karena dapat menyebabkan berbagai komplikasi serius jika tidak dikendalikan dengan baik.</p><p>Salah satu komplikasi yang perlu diwaspadai adalah gangguan pada kaki, yang bisa berujung pada amputasi jika tidak segera ditangani.</p><h2 class="text-xl font-bold mt-4 mb-2">Mengenal Kaki Diabetes</h2><p>Kerusakan saraf akibat gula darah tinggi (neuropati diabetik) dapat menyebabkan mati rasa dan borok (ulkus) yang sulit sembuh.</p>',
                    'author' => 'Tim Medis Diabetes Uji Coba',
                    'is_published' => true,
                ],
                [
                    'slug' => '4-jenis-aktivitas-yang-cocok-bagi-pengidap-diabetes',
                    'title' => '4 Jenis Aktivitas yang Cocok bagi Pengidap Diabetes dan Manfaatnya',
                    'category' => 'Aktivitas',
                    'date' => '23/12/2020',
                    'updated_at_display' => '28 Des 2020',
                    'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1000&auto=format&fit=crop&q=80',
                    'summary' => 'Latihan aerobik rutin seperti jalan santai, tai chi, menari, dan renang terbukti melancarkan sirkulasi serta menjaga gula darah.',
                    'content' => '<p>Selain menjaga pola makan, olahraga juga sangat dibutuhkan sebagai dasar hidup sehat, tak terkecuali bagi para diabetesi. Olahraga adalah salah satu kunci sukses menjaga kadar gula darah dan menjaga berat badan.</p><h3 class="text-lg font-bold mt-3 mb-1">1. Berjalan Kaki</h3><p>Rutin 30 menit sehari menjaga sensitivitas insulin sel otot.</p><h3 class="text-lg font-bold mt-3 mb-1">2. Berenang</h3><p>Melancarkan sirkulasi tanpa membebani sendi kaki.</p>',
                    'author' => 'Tim Medis Diabetes Uji Coba',
                    'is_published' => true,
                ],
                [
                    'slug' => 'tips-berolahraga-dengan-aktivitas-sehari-hari-menjadi-tetap-bugar',
                    'title' => 'Tips Berolahraga dengan Aktivitas Sehari-hari Menjadi Tetap Bugar',
                    'category' => 'Gaya Hidup',
                    'date' => '15/01/2021',
                    'updated_at_display' => '18 Jan 2021',
                    'image' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=1000&auto=format&fit=crop&q=80',
                    'summary' => 'Manfaatkan tangga kantor dan aktivitas fisik harian untuk menjaga metabolisme tubuh tanpa perlu alat olahraga khusus.',
                    'content' => '<p>Mendapatkan tubuh bugar tidak selalu menuntut Anda harus mendaftar ke gym mewah. Kunci hidup bugar adalah konsistensi bergerak dalam rutinitas harian Anda.</p><p>Gunakan tangga kantor, perbanyak jalan kaki, dan regangkan otot secara teratur.</p>',
                    'author' => 'Tim Medis Diabetes Uji Coba',
                    'is_published' => true,
                ],
                [
                    'slug' => 'pentingnya-olahraga-bagi-penderita-diabetes',
                    'title' => 'Pentingnya Olahraga bagi Penderita Diabetes',
                    'category' => 'Klinis',
                    'date' => '05/02/2021',
                    'updated_at_display' => '10 Feb 2021',
                    'image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=1000&auto=format&fit=crop&q=80',
                    'summary' => 'Saat otot berkontraksi, molekul GLUT-4 aktif menyerap glukosa darah tanpa insulin hingga 48 jam.',
                    'content' => '<p>Bagi penyandang diabetes melitus, olahraga bukan sekadar opsi pelengkap melainkan salah satu pilar terapi medis primer yang setara dengan obat-obatan oral dan insulin.</p>',
                    'author' => 'Tim Medis Diabetes Uji Coba',
                    'is_published' => true,
                ],
                [
                    'slug' => '10-pilihan-buah-untuk-penderita-diabetes-yang-aman-dan-sehat',
                    'title' => '10 Pilihan Buah untuk Penderita Diabetes yang Aman dan Sehat',
                    'category' => 'Nutrisi',
                    'date' => '10/03/2021',
                    'updated_at_display' => '15 Mar 2021',
                    'image' => 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=1000&auto=format&fit=crop&q=80',
                    'summary' => 'Buah dengan indeks glikemik rendah seperti apel, pir, dan stroberi aman dikonsumsi asalkan dalam bentuk utuh.',
                    'content' => '<p>Banyak pengidap diabetes takut mengonsumsi buah karena khawatir gula darah melonjak. Padahal, buah utuh kaya akan antioksidan, vitamin esensial, dan serat larut yang justru memperlambat penyerapan karbohidrat.</p>',
                    'author' => 'Tim Medis Diabetes Uji Coba',
                    'is_published' => true,
                ],
                [
                    'slug' => 'diabetesi-harus-memenuhi-5-nutrisi-penting-ini',
                    'title' => 'Diabetesi Harus Memenuhi 5 Nutrisi Penting Ini',
                    'category' => 'Nutrisi',
                    'date' => '22/03/2021',
                    'updated_at_display' => '25 Mar 2021',
                    'image' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=1000&auto=format&fit=crop&q=80',
                    'summary' => 'Serat tinggi, asam lemak omega 3, kromium, magnesium, dan vitamin D sangat penting untuk kestabilan glukosa.',
                    'content' => '<p>Kebutuhan mikronutrien bagi penyandang diabetes sangat esensial untuk mendukung kinerja insulin dan mencegah peradangan seluler kronis.</p>',
                    'author' => 'Tim Medis Diabetes Uji Coba',
                    'is_published' => true,
                ]
            ];

            foreach ($articles as $art) {
                Article::create($art);
            }
        }

        // 2. Seed Default Site Contents untuk Pengelolaan Tampilan Beranda & Website
        $defaultContents = [
            'hero_pill' => [
                'group' => 'hero',
                'title' => 'Pill Tag Banner Hero',
                'content' => 'Deteksi Dini Diabetes Uji Coba',
            ],
            'hero_title' => [
                'group' => 'hero',
                'title' => 'Judul Utama Hero Banner',
                'content' => 'Kebiasaan Sehat Baru, Kualitas Hidup Lebih Baik',
            ],
            'hero_description' => [
                'group' => 'hero',
                'title' => 'Deskripsi Hero Banner',
                'content' => 'Skrining risiko diabetes mandiri dalam 1 menit menggunakan algoritma Machine Learning XGBoost tervalidasi CDC BRFSS.',
            ],
            'hero_cta_text' => [
                'group' => 'hero',
                'title' => 'Teks Tombol CTA Hero Utama',
                'content' => 'Cek Risiko Sekarang',
            ],
            'hero_cta2_text' => [
                'group' => 'hero',
                'title' => 'Teks Tombol CTA Hero Kedua',
                'content' => 'Explore 5 Pilar ↓',
            ],
            'pemicu_title' => [
                'group' => 'pemicu',
                'title' => 'Judul Bagian Pemicu',
                'content' => 'Faktor Risiko & Pemicu Diabetes Melitus',
            ],
            'pemicu_subtitle' => [
                'group' => 'pemicu',
                'title' => 'Subjudul Bagian Pemicu',
                'content' => 'Memahami pemicu membantu mendeteksi risiko sejak dini. Risiko diabetes dipengaruhi oleh kombinasi faktor gaya hidup (dapat diubah) dan faktor biologis (tidak dapat diubah).',
            ],
            'pemicu_note' => [
                'group' => 'pemicu',
                'title' => 'Catatan Edukasi Pemicu',
                'content' => 'Adanya satu atau lebih faktor pemicu di atas bukan berarti Anda pasti terkena diabetes. Faktor tersebut merupakan indikator probabilitas. Pencegahan melalui kebiasaan sehat harian terbukti secara klinis mampu menurunkan risiko hingga lebih dari 58%.',
            ],
            'pilar_title' => [
                'group' => 'pilar',
                'title' => 'Judul Bagian 5 Pilar',
                'content' => '5 Pilar Utama Pengendalian & Pencegahan Diabetes',
            ],
            'pilar_subtitle' => [
                'group' => 'pilar',
                'title' => 'Subjudul Bagian 5 Pilar',
                'content' => 'Panduan komprehensif gaya hidup sehat rekomendasi Konsensus Nasional PERKENI & World Health Organization.',
            ],
            'pilar_disclaimer' => [
                'group' => 'pilar',
                'title' => 'Catatan Pencegahan 5 Pilar',
                'content' => 'Penerapan 5 pilar kebiasaan sehat di atas terbukti klinis dapat menurunkan risiko diabetes dan komplikasi metabolik secara signifikan. Langkah ini adalah ikhtiar pencegahan dan bukan jaminan mutlak 100% bebas diabetes, karena faktor keturunan dan biologis tetap memiliki peranan. Konsultasikan status kesehatan Anda secara teratur kepada tenaga medis.',
            ],
            'banner_cta_title' => [
                'group' => 'cta',
                'title' => 'Judul Banner CTA Bawah',
                'content' => 'Ketahui Tingkat Risiko Anda Sekarang',
            ],
            'banner_cta_desc' => [
                'group' => 'cta',
                'title' => 'Deskripsi Banner CTA Bawah',
                'content' => 'Hanya butuh 1 menit dengan instrumen tervalidasi CDC & Machine Learning XGBoost.',
            ],
            'banner_cta_button' => [
                'group' => 'cta',
                'title' => 'Teks Tombol Banner CTA Bawah',
                'content' => 'Mulai Skrining Mandiri →',
            ],
            'site_name' => [
                'group' => 'general',
                'title' => 'Nama Website',
                'content' => 'Diabetes Uji Coba',
            ],
            'site_tagline' => [
                'group' => 'general',
                'title' => 'Tagline / Slogan Website',
                'content' => 'Deteksi Dini Diabetes, Jaga Kesehatan Anda',
            ],
            'footer_desc' => [
                'group' => 'footer',
                'title' => 'Deskripsi Platform di Footer',
                'content' => 'Platform edukasi dan skrining dini diabetes mandiri berbasis algoritma Machine Learning XGBoost.',
            ],
            'footer_copyright' => [
                'group' => 'footer',
                'title' => 'Teks Hak Cipta di Footer',
                'content' => 'Diabetes Uji Coba • Sistem Skrining Dini Diabetes Berbasis Machine Learning XGBoost.',
            ],
            'contact_email' => [
                'group' => 'contact',
                'title' => 'Email Bantuan',
                'content' => 'bantuan@diabetesujicoba.com',
            ],
            'contact_phone' => [
                'group' => 'contact',
                'title' => 'Nomor WhatsApp Layanan',
                'content' => '0812-3456-7890',
            ],
            'quiz_subtitle' => [
                'group' => 'quiz',
                'title' => 'Petunjuk Singkat Pengisian Kuesioner',
                'content' => 'Jawab kuesioner indikator klinis gaya hidup untuk mendeteksi risiko diabetes secara instan.',
            ],
            'result_disclaimer' => [
                'group' => 'result',
                'title' => 'Disclaimer Medis di Halaman Hasil',
                'content' => 'Hasil analisis ini merupakan estimasi skrining berbasis data statistik probabilitas XGBoost, bukan diagnosis medis final. Disarankan untuk berkonsultasi langsung dengan dokter spesialis atau fasilitas kesehatan terdekat.',
            ],
        ];

        foreach ($defaultContents as $key => $item) {
            SiteContent::firstOrCreate(
                ['key' => $key],
                [
                    'group' => $item['group'],
                    'title' => $item['title'],
                    'content' => $item['content'],
                ]
            );
        }
    }
}

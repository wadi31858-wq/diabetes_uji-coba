<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\User;
use App\Models\Article;
use App\Models\SiteContent;
use App\Services\HealthAgentService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DiabetesController extends Controller
{
    /**
     * Halaman Utama: Landing Page Cegah Diabetes (Gaya KlikDokter)
     */
    public function index()
    {
        $allArticles = $this->getArticlesData();
        $heroSlug = 'tips-berolahraga-dengan-aktivitas-sehari-hari-menjadi-tetap-bugar';
        $topArticles = [];
        if (isset($allArticles[$heroSlug])) {
            $topArticles[] = $allArticles[$heroSlug];
        }
        foreach ($allArticles as $slug => $art) {
            if ($slug !== $heroSlug && count($topArticles) < 3) {
                $topArticles[] = $art;
            }
        }
        $latestArticle = $topArticles[0] ?? (reset($allArticles) ?: null);
        return view('landing', compact('topArticles', 'latestArticle'));
    }

    /**
     * Halaman Fitur: Pusat Berita, Edukasi & Artikel Kesehatan Diabetes
     */
    public function articles()
    {
        $articles = $this->getArticlesData();
        return view('articles', compact('articles'));
    }

    /**
     * Halaman Khusus: Tentang Website (Profil, Visi, Teknologi & Batasan Sistem)
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Halaman Pembuka / Splash Screen (Welcome Screen 2026)
     */
    public function welcome()
    {
        return view('splash');
    }

    /**
     * Halaman Detail Artikel (Membaca Lengkap)
     */
    public function articleDetail($slug)
    {
        $allArticles = $this->getArticlesData();
        $article = $allArticles[$slug] ?? null;

        if (!$article) {
            // Jika slug tidak ditemukan, fallback ke artikel pertama
            $article = reset($allArticles);
        }

        // Ambil artikel lain untuk widget sidebar (selain artikel yang sedang dibaca)
        $relatedArticles = array_filter($allArticles, function ($key) use ($article) {
            return $key !== $article['slug'];
        }, ARRAY_FILTER_USE_KEY);

        return view('article_detail', [
            'article' => $article,
            'relatedArticles' => array_slice($relatedArticles, 0, 5)
        ]);
    }

    /**
     * Data Repositori Artikel Edukasi Klinis & Diabetes
     */
    private function getArticlesData(): array
    {
        try {
            $dbArticles = Article::where('is_published', true)->latest()->get();
            if ($dbArticles->isNotEmpty()) {
                return $dbArticles->keyBy('slug')->toArray();
            }
        } catch (\Exception $e) {
            // Fallback default jika database belum siap
        }

        return [
            'kaki-diabetes-ciri-ciri-penyakit-gula-pada-kaki' => [
                'slug' => 'kaki-diabetes-ciri-ciri-penyakit-gula-pada-kaki',
                'title' => 'Kaki Diabetes : Ciri-ciri Penyakit Gula pada Kaki',
                'category' => 'Diabetes',
                'date' => '7/11/2024',
                'updated_at' => '23 Jul 2024',
                'image' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=1000&auto=format&fit=crop&q=80',
                'summary' => 'Kenali 7 komplikasi klinis pada saraf dan sirkulasi kaki (ulkus diabetikum) sejak dini guna mencegah risiko amputasi.',
                'content' => '
                    <p>Diabetes adalah penyakit yang dikenal sebagai "ibu dari segala penyakit" karena dapat menyebabkan berbagai komplikasi serius jika tidak dikendalikan dengan baik.</p>

                    <p>Salah satu komplikasi yang perlu diwaspadai adalah gangguan pada kaki, yang bisa berujung pada amputasi jika tidak segera ditangani. Memahami ciri-ciri penyakit gula pada kaki sejak dini sangat penting untuk mencegah komplikasi lebih lanjut.</p>

                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mt-8 mb-3">Mengenal Kaki Diabetes</h2>
                    <p>Gangguan kesehatan akibat diabetes dapat mempengaruhi berbagai bagian tubuh, termasuk kaki. Kerusakan saraf akibat kadar gula darah tinggi, yang dikenal sebagai <em>neuropati diabetik</em>, dapat menyebabkan gejala seperti nyeri, kesemutan, hingga mati rasa pada kaki.</p>
                    <p>Selain itu, kerusakan saraf dan jaringan yang lebih serius pada kaki penderita diabetes dapat menyebabkan kondisi yang dikenal sebagai kaki diabetes (ulkus diabetikum).</p>
                    <p>Sayangnya, gejala awal sering tidak terlihat jelas, dan banyak penderita tidak merasakan gejala hingga luka berkembang menjadi infeksi yang lebih parah.</p>

                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mt-8 mb-3">
                        Perbedaan Gejala Penyakit Gula Basah dan <span class="bg-yellow-400 text-black px-1.5 py-0.5 rounded font-bold">Gula</span> Kering, Fakta atau Mitos?
                    </h2>
                    <p>Banyak orang menganggap bahwa ciri-ciri penyakit gula kering pada kaki berbeda dengan ciri gula basah. Padahal, anggapan tersebut sama sekali keliru. Faktanya, tidak ada istilah penyakit gula basah atau kering pada diabetes. Kedua istilah ini sering digunakan oleh masyarakat, tetapi secara medis hanya ada diabetes tipe 1, tipe 2, dan gestasional.</p>
                    <p>Tingkat keparahan komplikasi diabetes ditentukan respons diabetesi serta upaya dalam mengantisipasi penyakit tersebut melalui perubahan pola hidup. Ciri-ciri penyakit gula basah pada kaki yang membuat luka sangat susah sembuh menunjukkan bahwa kadar gula darah tidak terkontrol. Kondisi tersebut biasanya berangsur-angsur parah hingga membuat luka bernanah bahkan bisa berujung amputasi. Sebaliknya, ciri-ciri penyakit gula kering pada kaki yang membuat luka lebih mudah kering dan sembuh mengindikasikan kadar gula darah terkontrol.</p>

                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mt-8 mb-3">Mengenal Ciri-Ciri Penyakit Gula pada Kaki</h2>
                    
                    <div class="my-5 rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                        <img src="https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=1000&auto=format&fit=crop&q=80" alt="Pemeriksaan Medis Kaki Diabetes" class="w-full h-auto object-cover max-h-[380px]">
                    </div>

                    <p>Komplikasi penyakit gula yang mengganggu kesehatan kaki ditandai tujuh gejala berikut ini:</p>

                    <div class="space-y-8 mt-6">
                        
                        <!-- 1. Ulkus kaki (borok) -->
                        <div class="space-y-3">
                            <h3 class="text-lg font-bold text-gray-900">Ulkus kaki (borok)</h3>
                            <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 max-h-[320px]">
                                <img src="https://images.unsplash.com/photo-1584515933487-779824d29309?w=1000&auto=format&fit=crop&q=80" alt="Ulkus Kaki Diabetik" class="w-full h-auto object-cover max-h-[320px]">
                            </div>
                            <p>Kondisi dimana terjadi luka terbuka pada kaki diabetesi. Pada kondisi ringan, ulkus membuat permukaan kulit kaki mengelupas lalu menghitam sebelum menjadi kering. Sebaliknya, kondisi yang lebih parah membuat luka terus-menerus basah dan menjadi semakin dalam sampai bernanah.</p>
                        </div>

                        <!-- 2. Callouse -->
                        <div class="space-y-3">
                            <h3 class="text-lg font-bold text-gray-900">Callouse</h3>
                            <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 max-h-[320px]">
                                <img src="https://images.unsplash.com/photo-1519735777090-ec97162dc266?w=1000&auto=format&fit=crop&q=80" alt="Callouse Kapalan pada Kaki" class="w-full h-auto object-cover max-h-[320px]">
                            </div>
                            <p>Penumpukan kulit bertekstur keras yang disebut kapalan (callouse). Ciri ciri penyakit gula pada kaki biasanya muncul di antara jari atau bagian bawah kaki akibat gesekan atau tekanan berlebih selama menggunakan sepatu.</p>
                            <p>Namun, kapalan tidak bisa dijadikan satu-satunya parameter karena orang yang tidak mengidap diabetes pun bisa mengalaminya.</p>
                        </div>

                        <!-- 3. Lenting -->
                        <div class="space-y-3">
                            <h3 class="text-lg font-bold text-gray-900">Lenting</h3>
                            <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 max-h-[320px]">
                                <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=1000&auto=format&fit=crop&q=80" alt="Lenting Kulit Kaki" class="w-full h-auto object-cover max-h-[320px]">
                            </div>
                            <p>Kondisi ini terjadi karena gesekan antara kulit kaki dan sepatu juga bisa menimbulkan gelembung-gelembung berisi cairan yang disebut lenting. Sebaiknya lenting tidak dipecahkan sembarangan karena meningkatkan risiko infeksi.</p>
                        </div>

                        <!-- 4. Onikomikosis (Infeksi Jamur di Kuku) -->
                        <div class="space-y-3">
                            <h3 class="text-lg font-bold text-gray-900">Onikomikosis (Infeksi Jamur di Kuku)</h3>
                            <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 max-h-[320px]">
                                <img src="https://images.unsplash.com/photo-1508847154043-be5407fcaa5a?w=1000&auto=format&fit=crop&q=80" alt="Onikomikosis Infeksi Jamur Kuku" class="w-full h-auto object-cover max-h-[320px]">
                            </div>
                            <p>Infeksi jamur pada kuku (onikomikosis) yang ditandai dengan perubahan warna serta tekstur kuku. Gangguan kesehatan ini membuat kuku menebal jadi cokelat atau kehitaman tetapi bertekstur rapuh dan rentan hancur.</p>
                        </div>

                        <!-- 5. Gangrene -->
                        <div class="space-y-3">
                            <h3 class="text-lg font-bold text-gray-900">Gangrene</h3>
                            <div class="rounded-2xl overflow-hidden shadow-sm border border-gray-100 max-h-[320px]">
                                <img src="https://images.unsplash.com/photo-1532938911079-1b06ac7ceec7?w=1000&auto=format&fit=crop&q=80" alt="Gangrene Kematian Jaringan Kaki" class="w-full h-auto object-cover max-h-[320px]">
                            </div>
                            <p>Kondisi ini juga dikenal dengan sebutan gangren, yaitu kematian jaringan tubuh akibat gangguan sirkulasi darah dan oksigen. Ciri-ciri penyakit gula pada kaki yang satu ini sering diabaikan, khususnya bila tidak menimbulkan rasa sakit.</p>
                            <p>Padahal, gangrene menandakan bahwa penyakit diabetes sudah parah karena tidak terkontrol dalam jangka panjang. Jika jaringan kaki yang mati semakin meluas, maka tindakan amputasi harus dipilih sebagai solusi agar gangren tidak berkembang ke bagian tubuh di sekitarnya.</p>
                        </div>

                        <!-- 6. Perubahan Bentuk Kaki -->
                        <div class="space-y-3">
                            <h3 class="text-lg font-bold text-gray-900">Perubahan Bentuk Kaki</h3>
                            <p>Perubahan ini bisa terjadi akibat gangguan otot yang kemudian turut mengganggu fungsi kaki secara keseluruhan.</p>
                            <p>Contoh:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>hammertoes</strong> (jari-jari kaki menekuk ke dalam)</li>
                                <li><strong>pes cavus</strong> (lengkungan kaki lebih tinggi dibandingkan keadaan normal).</li>
                            </ul>
                        </div>

                        <!-- 7. Kulit Kaki Kering dan Mati Rasa -->
                        <div class="space-y-3">
                            <h3 class="text-lg font-bold text-gray-900">Kulit Kaki Kering & Mati Rasa (Neuropati)</h3>
                            <p>Saraf yang rusak tidak lagi mampu memberi sinyal ke kelenjar keringat untuk melembapkan kulit kaki, menyebabkan kulit pecah-pecah parah yang menjadi celah masuk kuman. Selain itu, mati rasa membuat penderita tidak menyadari saat kaki tertusuk paku atau lecet hingga luka sudah membusuk.</p>
                        </div>

                    </div>
                '
            ],
            '4-jenis-aktivitas-yang-cocok-bagi-pengidap-diabetes' => [
                'slug' => '4-jenis-aktivitas-yang-cocok-bagi-pengidap-diabetes',
                'title' => '4 Jenis Aktivitas yang Cocok bagi Pengidap Diabetes dan Manfaatnya',
                'category' => 'Aktivitas',
                'date' => '23/12/2020',
                'updated_at' => '28 Des 2020',
                'image' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1000&auto=format&fit=crop&q=80',
                'summary' => 'Latihan aerobik rutin seperti jalan santai, tai chi, menari, dan renang terbukti melancarkan sirkulasi serta menjaga gula darah.',
                'content' => '
                    <p>Selain menjaga pola makan, olahraga juga sangat dibutuhkan sebagai dasar hidup sehat, tak terkecuali bagi para diabetesi. Olahraga adalah salah satu kunci sukses menjaga kadar gula darah dan menjaga berat badan. Ditambah lagi, olahraga juga bisa melancarkan peredaran darah sehingga baik untuk mencegah komplikasi bagi pengidap diabetes.</p>
                    
                    <p>Menurut The National Institute of Health, diabetesi disarankan untuk melakukan 150 menit latihan aerobik setiap minggu. Sebelum mulai rutin olahraga, jangan lupa konsultasikan terlebih dahulu kepada dokter Anda olahraga apa paling tepat untuk membuat Anda tetap aktif tapi gula darah juga terjaga. Sebab, setiap orang punya kebutuhan aktivitas fisik berbeda-beda.</p>
                    
                    <p>Dari berbagai jenis olahraga, berikut ini 4 jenis olahraga yang dianjurkan bagi diabetesi menurut The American Diabetes Association. Yuk, kita kupas bareng-bareng, Sahabat Sehat!</p>
                    
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mt-6 mb-2">1. Berjalan kaki</h3>
                    <p>Olahraga ini bisa dilakukan semua orang dan sangat murah harganya. Anda tak membutuhkan peralatan apa pun untuk berjalan. Anda bisa mulai rutin berjalan kaki selama 30 menit sampai 1 jam setiap hari untuk menjaga tubuh tetap bugar dan membantu gula darah tetap normal.</p>
                    
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mt-6 mb-2">2. Tai chi</h3>
                    <p>Pola gerakan tai chi berupa gerakan tubuh yang lambat dan halus untuk menenangkan pikiran dan tubuh. Menurut penelitian University of Florida, Olahraga asal Negeri Tirai Bambu ini dipercaya bisa menunjukkan hasil yang baik dalam mengontrol gula darah. Manfaat lain yang dirasakan adalah peningkatan vitalitas, energi, dan kesehatan mental.</p>
                    
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mt-6 mb-2">3. Menari</h3>
                    <p>Mengikuti irama musik dengan menggerakkan tubuh ternyata banyak manfaatnya lho, Sahabat Sehat. Menari bisa meningkatkan daya ingat, kekuatan otak, dan cara yang menyenangkan untuk menurunkan risiko diabetes. Manfaat lainnya yaitu mengurangi stres, mengurangi berat badan, dan meningkatkan fleksibilitas. Dalam 30 menit, Anda bisa membakar 150 kalori dalam tubuh.</p>
                    
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mt-6 mb-2">4. Berenang</h3>
                    <p>Berenang bisa meregangkan dan mengendurkan otot-otot Anda dan tentunya sangat baik bagi pengidap diabetes. Lakukan olahraga ini secara rutin seminggu tiga kali dan Anda pun bisa merasakan manfaatnya. Selain bisa menurunkan tingkat kolesterol, membakar kalori, dan menurunkan stres, renang juga bisa menurunkan tingkat gula darah.</p>
                    
                    <p class="mt-6">Semakin usia bertambah, kita semakin jauh dari olahraga. Setuju tidak, Sahabat Sehat? Padahal, saat kecil, tanpa kita sadari, olahraga adalah kegiatan yang kita tunggu-tunggu, seperti main bola, main sepeda, dan lari-larian dengan berbagai jenis permainan tradisional. Nah, kita bisa memotivasi diri sendiri dengan mengembalikan pola pikir bahwa olahraga adalah permainan menyenangkan.</p>
                    
                    <p>Sebelum berolahraga, jangan lupa lakukan peregangan otot agar tubuh tidak kaku dan tidak mudah cedera. Jangan lupa minum air putih yang cukup agar tubuh tidak dehidrasi. Gunakan sepatu yang tepat dan lindungi kaki Anda. Sebisa mungkin kabari saudara atau rekan bahwa Anda tengah berolahraga agar mereka bisa mudah mengantisipasi jika terjadi keadaan darurat. Selebihnya, semangat berolahraga, Sahabat Sehat!</p>
                '
            ],
            'tips-berolahraga-dengan-aktivitas-sehari-hari-menjadi-tetap-bugar' => [
                'slug' => 'tips-berolahraga-dengan-aktivitas-sehari-hari-menjadi-tetap-bugar',
                'title' => 'Tips Berolahraga dengan Aktivitas Sehari-hari Menjadi Tetap Bugar',
                'category' => 'Gaya Hidup',
                'date' => '15/01/2021',
                'updated_at' => '18 Jan 2021',
                'image' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=1000&auto=format&fit=crop&q=80',
                'summary' => 'Manfaatkan tangga kantor dan aktivitas fisik harian untuk menjaga metabolisme tubuh tanpa perlu alat olahraga khusus.',
                'content' => '
                    <p>Mendapatkan tubuh bugar tidak selalu menuntut Anda harus mendaftar ke pusat kebugaran (gym) mewah atau membeli alat olahraga mahal. Kunci hidup bugar adalah konsistensi bergerak dalam aktivitas harian Anda (Non-Exercise Physical Activity/NEPA).</p>
                    <p>Manfaatkan tangga alih-alih lift saat bekerja di kantor, parkir kendaraan sedikit lebih jauh dari pintu masuk minimarket, serta lakukan peregangan setiap 45 menit duduk bekerja. Kebiasaan kecil ini mengakumulasi pembakaran ratusan kalori ekstra setiap harinya.</p>
                '
            ],
            'pentingnya-olahraga-bagi-penderita-diabetes' => [
                'slug' => 'pentingnya-olahraga-bagi-penderita-diabetes',
                'title' => 'Pentingnya Olahraga bagi Penderita Diabetes',
                'category' => 'Klinis',
                'date' => '05/02/2021',
                'updated_at' => '10 Feb 2021',
                'image' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=1000&auto=format&fit=crop&q=80',
                'content' => '
                    <p>Bagi penyandang diabetes melitus, olahraga bukan sekadar opsi pelengkap melainkan salah satu pilar terapi medis primer yang setara dengan obat-obatan oral dan insulin.</p>
                    <p>Saat otot aktif berkontraksi, molekul transporter GLUT-4 di dalam sel otot akan bergerak ke permukaan sel untuk menyerap glukosa darah secara mandiri tanpa memerlukan perantara insulin. Efek sensitivitas ini bertahan hingga 48 jam, membuat kadar gula darah puasa lebih stabil terkendali.</p>
                '
            ],
            '10-pilihan-buah-untuk-penderita-diabetes-yang-aman-dan-sehat' => [
                'slug' => '10-pilihan-buah-untuk-penderita-diabetes-yang-aman-dan-sehat',
                'title' => '10 Pilihan Buah untuk Penderita Diabetes yang Aman dan Sehat',
                'category' => 'Nutrisi',
                'date' => '10/03/2021',
                'updated_at' => '15 Mar 2021',
                'image' => 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=1000&auto=format&fit=crop&q=80',
                'content' => '
                    <p>Banyak pengidap diabetes takut mengonsumsi buah karena khawatir gula darah melonjak. Padahal, buah utuh kaya akan antioksidan, vitamin esensial, dan serat larut yang justru memperlambat penyerapan karbohidrat.</p>
                    <p>Buah dengan Indeks Glikemik rendah seperti apel dengan kulitnya, buah pir, alpukat segar, jeruk utuh, kiwi, dan stroberi sangat dianjurkan. Hindari mengolahnya menjadi jus manis atau manisan kemasan kaleng.</p>
                '
            ],
            'diabetesi-harus-memenuhi-5-nutrisi-penting-ini' => [
                'slug' => 'diabetesi-harus-memenuhi-5-nutrisi-penting-ini',
                'title' => 'Diabetesi Harus Memenuhi 5 Nutrisi Penting Ini',
                'category' => 'Nutrisi',
                'date' => '22/03/2021',
                'updated_at' => '25 Mar 2021',
                'image' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=1000&auto=format&fit=crop&q=80',
                'content' => '
                    <p>Keseimbangan nutrisi mikro dan makro membantu mencegah peradangan pembuluh darah pada pasien diabetes tipe 2.</p>
                    <p>5 nutrisi wajib yang perlu dicukupi meliputi: serat larut air (oatmeal & brokoli), mineral magnesium, kromium, asam lemak esensial omega-3, serta vitamin D3 untuk mendukung fungsi sel beta pankreas.</p>
                '
            ],
            'bagaimana-sahur-yang-baik-bagi-diabetesi' => [
                'slug' => 'bagaimana-sahur-yang-baik-bagi-diabetesi',
                'title' => 'Bagaimana Sahur yang Baik bagi Diabetesi?',
                'category' => 'Nutrisi',
                'date' => '12/04/2021',
                'updated_at' => '15 Apr 2021',
                'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=1000&auto=format&fit=crop&q=80',
                'content' => '
                    <p>Sahur memegang peranan krusial bagi diabetesi yang berpuasa agar tidak mengalami hipoglikemia di siang hari maupun hiperglikemia ekstrem saat waktu berbuka tiba.</p>
                    <p>Kuncinya adalah mengonsumsi karbohidrat kompleks lepas lambat (nasi merah atau oatmeal), mencukupi protein dari telur rebus dan tempe, serta meminum air putih yang cukup mendekati batas imsak.</p>
                '
            ],
            'solusi-susu-diabetes-dengan-nutrisi-lengkap' => [
                'slug' => 'solusi-susu-diabetes-dengan-nutrisi-lengkap',
                'title' => 'Solusi Susu Diabetes dengan Nutrisi Lengkap',
                'category' => 'Nutrisi',
                'date' => '02/05/2021',
                'updated_at' => '06 Mei 2021',
                'image' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=1000&auto=format&fit=crop&q=80',
                'content' => '
                    <p>Susu khusus diabetes diformulasikan dengan karbohidrat lepas lambat (isomaltulosa) yang tidak memicu lonjakan glukosa mendadak.</p>
                    <p>Dilengkapi kalsium dan vitamin D, susu ini dapat difungsikan sebagai meal replacement (pengganti makan pagi atau camilan sebelum tidur) agar lambung tetap nyaman tanpa menaikkan HbA1c.</p>
                '
            ],
            'tentang-berat-badan-ideal-dan-cara-mencapainya' => [
                'slug' => 'tentang-berat-badan-ideal-dan-cara-mencapainya',
                'title' => 'Tentang Berat Badan Ideal dan Cara Mencapainya',
                'category' => 'Gaya Hidup',
                'date' => '18/06/2021',
                'updated_at' => '21 Jun 2021',
                'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1000&auto=format&fit=crop&q=80',
                'content' => '
                    <p>Penurunan 5-10% dari berat badan berlebih terbukti menurunkan risiko diabetes melitus tipe 2 hingga lebih dari 58% pada kelompok pra-diabetes.</p>
                    <p>Fokuslah pada lingkar pinggang sehat (&lt; 90 cm pria, &lt; 80 cm wanita), terapkan defisit kalori moderat, dan kombinasikan jalan cepat dengan latihan beban ringan.</p>
                '
            ],
        ];
    }

    /**
     * Halaman Kuesioner: Cek Risiko Diabetes Langkah Demi Langkah
     */
    public function quiz()
    {
        return view('quiz');
    }

    /**
     * Proses Prediksi Machine Learning (XGBoost) via Python CLI Bridge
     */
    public function predict(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'nullable|string',
            'age' => 'nullable|numeric|min:1|max:120',
            'height' => 'nullable|numeric|min:50|max:250',
            'weight' => 'nullable|numeric|min:20|max:300',
            'high_bp' => 'nullable|integer|in:0,1',
            'high_chol' => 'nullable|integer|in:0,1',
            'phys_activity' => 'nullable|integer|in:0,1',
            'family_history' => 'nullable|integer|in:0,1',
            'smoker' => 'nullable|integer|in:0,1',
            'fruits_veggies' => 'nullable|integer|in:0,1',
            'chol_check' => 'nullable|integer|in:0,1',
            'heart_disease' => 'nullable|integer|in:0,1',
            'gestational_history' => 'nullable|integer|in:0,1',
        ]);

        $gender = $request->input('gender', 'Laki-laki');
        $age = (int) $request->input('age', 30);
        $height = (float) $request->input('height', 170);
        $weight = (float) $request->input('weight', 65);
        $highBp = (int) $request->input('high_bp', 0);
        $highChol = (int) $request->input('high_chol', 0);
        $physActivity = (int) $request->input('phys_activity', 1);
        $familyHistory = (int) $request->input('family_history', 0);
        $smoker = (int) $request->input('smoker', 0);
        $fruitsVeggies = (int) $request->input('fruits_veggies', 1);
        $cholCheck = (int) $request->input('chol_check', 1);
        $heartDisease = (int) $request->input('heart_disease', 0);
        $gestationalHistory = (int) $request->input('gestational_history', 0);

        // Hitung BMI
        $hM = $height / 100.0;
        $bmi = round($weight / ($hM * $hM), 1);

        // Payload kompatibel ke predict_cli.py (mempertahankan 21 fitur model lama)
        $payload = [
            'gender' => $gender,
            'age' => $age,
            'height' => $height,
            'weight' => $weight,
            'bmi' => $bmi,
            'high_bp' => $highBp,
            'high_chol' => $highChol,
            'phys_activity' => $physActivity,
            'family_history' => $familyHistory,
            'Smoker' => $smoker,
            'Fruits' => $fruitsVeggies,
            'Veggies' => $fruitsVeggies,
            'CholCheck' => $cholCheck,
            'HeartDiseaseorAttack' => $heartDisease,
            'heart_disease' => $heartDisease,
        ];

        $jsonPayload = json_encode($payload);
        $result = null;

        // Eksekusi Model XGBoost melalui predict_cli.py
        try {
            $scriptPath = base_path('predict_cli.py');
            $pythonBin = $this->getPythonBinary();
            $process = new Process([$pythonBin, $scriptPath]);
            $process->setInput($jsonPayload);
            $process->setTimeout(35);
            $process->run();

            if ($process->isSuccessful()) {
                $output = trim($process->getOutput());
                $parsed = json_decode($output, true);
                if (is_array($parsed) && isset($parsed['category'])) {
                    $result = $parsed;
                }
            } else {
                \Log::warning('Python execution non-zero exit: ' . $process->getErrorOutput());
            }
        } catch (\Exception $e) {
            // Log fallback
            \Log::warning('Python execution failed: ' . $e->getMessage());
        }

        // Fallback Algoritma Klinis jika eksekusi Python terhambat
        if (!$result) {
            $score = 0;
            if (strtolower($gender) === 'laki-laki') $score += 1;
            if ($age < 35) $score += 0;
            elseif ($age < 50) $score += 3;
            elseif ($age < 65) $score += 5;
            else $score += 7;

            if ($bmi >= 25.0) $score += 4;
            if ($bmi >= 30.0) $score += 2;

            if ($highBp === 1) $score += 5;
            if ($familyHistory === 1) $score += 5;
            if ($physActivity === 0) $score += 3;
            if ($highChol === 1) $score += 2;

            $score = min($score, 25);

            if ($score <= 6) {
                $category = 'Rendah';
                $color = '#6ebd20';
                $pointerLeft = '16.7%';
                $recommendations = [
                    'Pertahankan pola makan bergizi seimbang dan batasi konsumsi gula berlebih.',
                    'Lakukan aktivitas fisik atau olahraga teratur minimal 30 menit setiap hari.',
                    'Jaga berat badan ideal dan pantau tekanan darah secara berkala.',
                    'Lakukan pemeriksaan gula darah rutin setidaknya 1 tahun sekali.'
                ];
            } elseif ($score <= 14) {
                $category = 'Sedang';
                $color = '#f37920';
                $pointerLeft = '50%';
                $recommendations = [
                    'Lakukan pemeriksaan gula darah dan tekanan darah secara berkala.',
                    'Batasi konsumsi makanan dengan kadar gula tinggi dan juga makanan / minuman kemasan.',
                    'Konsumsi makan makanan bergizi, tinggi protein dan kaya serat. Istirahat yang cukup dan olahraga teratur.',
                    'Konsultasi dengan dokter spesialis penyakit dalam.'
                ];
            } else {
                $category = 'Tinggi';
                $color = '#ff4252';
                $pointerLeft = '83.3%';
                $recommendations = [
                    'Segera lakukan konsultasi dengan dokter spesialis penyakit dalam untuk evaluasi lebih lanjut.',
                    'Lakukan pemeriksaan laboratorium mendalam (seperti HbA1c dan Gula Darah Puasa).',
                    'Batasi secara ketat konsumsi gula tambahan, karbohidrat olahan, dan minuman manis.',
                    'Terapkan pola hidup aktif terencana dan pantau kadar glukosa mandiri secara berkala.'
                ];
            }

            $bmiCat = 'Normal';
            if ($bmi < 18.5) $bmiCat = 'Berat Rendah';
            elseif ($bmi >= 25.0 && $bmi < 30.0) $bmiCat = 'Kelebihan Berat Badan';
            elseif ($bmi >= 30.0) $bmiCat = 'Obesitas';

            $result = [
                'status' => 'success',
                'gender' => $gender,
                'age' => $age,
                'bmi' => $bmi,
                'bmi_category' => $bmiCat,
                'score' => $score,
                'svm_prediction' => $score > 14 ? 1 : 0,
                'category' => $category,
                'color' => $color,
                'pointer_left' => $pointerLeft,
                'recommendations' => $recommendations
            ];
        }

        // Simpan Hasil ke Database SQLite
        try {
            Assessment::create([
                'gender' => $result['gender'] ?? $gender,
                'age' => $result['age'] ?? $age,
                'height' => $height,
                'weight' => $weight,
                'bmi' => $result['bmi'] ?? $bmi,
                'high_bp' => (bool)$highBp,
                'high_chol' => (bool)$highChol,
                'phys_activity' => (bool)$physActivity,
                'family_history' => (bool)$familyHistory,
                'smoker' => (bool)$smoker,
                'fruits_veggies' => (bool)$fruitsVeggies,
                'chol_check' => (bool)$cholCheck,
                'heart_disease' => (bool)$heartDisease,
                'gestational_history' => (bool)$gestationalHistory,
                'score' => $result['score'] ?? 0,
                'svm_prediction' => $result['svm_prediction'] ?? 0,
                'category' => $result['category'] ?? 'Sedang',
                'color' => $result['color'] ?? '#f37920',
                'recommendations' => $result['recommendations'] ?? [],
                'extra_data' => [
                    'smoker' => $smoker,
                    'fruits_veggies' => $fruitsVeggies,
                    'chol_check' => $cholCheck,
                    'heart_disease' => $heartDisease,
                    'gestational_history' => $gestationalHistory,
                ],
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $ex) {
            \Log::error('Gagal menyimpan assessment: ' . $ex->getMessage());
        }

        // Simpan ke session untuk halaman hasil
        session(['last_assessment' => $result]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($result);
        }

        return redirect()->route('result');
    }

    /**
     * Halaman Hasil Risiko Diabetes (Sesuai Desain Resmi KlikDokter)
     */
    public function result()
    {
        $assessment = session('last_assessment');

        // Jika tidak ada di session, ambil default Sedang (persis screenshot)
        if (!$assessment) {
            $latest = Assessment::latest()->first();
            if ($latest) {
                $assessment = [
                    'gender' => $latest->gender,
                    'age' => $latest->age,
                    'bmi' => $latest->bmi,
                    'score' => $latest->score,
                    'category' => $latest->category,
                    'color' => $latest->color,
                    'pointer_left' => $latest->category === 'Rendah' ? '16.7%' : ($latest->category === 'Tinggi' ? '83.3%' : '50%'),
                    'recommendations' => $latest->recommendations ?: [
                        'Lakukan pemeriksaan gula darah dan tekanan darah secara berkala.',
                        'Batasi konsumsi makanan dengan kadar gula tinggi dan juga makanan / minuman kemasan.',
                        'Konsumsi makan makanan bergizi, tinggi protein dan kaya serat. Istirahat yang cukup dan olahraga teratur.',
                        'Konsultasi dengan dokter spesialis penyakit dalam.'
                    ]
                ];
            } else {
                $assessment = [
                    'gender' => 'Laki-laki',
                    'age' => 30,
                    'bmi' => 24.5,
                    'score' => 10,
                    'category' => 'Sedang',
                    'color' => '#f37920',
                    'pointer_left' => '50%',
                    'recommendations' => [
                        'Lakukan pemeriksaan gula darah dan tekanan darah secara berkala.',
                        'Batasi konsumsi makanan dengan kadar gula tinggi dan juga makanan / minuman kemasan.',
                        'Konsumsi makan makanan bergizi, tinggi protein dan kaya serat. Istirahat yang cukup dan olahraga teratur.',
                        'Konsultasi dengan dokter spesialis penyakit dalam.'
                    ]
                ];
            }
        }

        return view('result', compact('assessment'));
    }

    /**
     * Halaman Integrasi Streamlit AI Analyzer
     */
    public function streamlit()
    {
        $totalAssessments = Assessment::count();
        $recentAssessments = Assessment::latest()->take(5)->get();

        return view('streamlit', compact('totalAssessments', 'recentAssessments'));
    }

    /**
     * Riwayat Audit Assessment (Khusus Admin / Terautentikasi)
     */
    public function history(Request $request)
    {
        $query = Assessment::query();

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($w) use ($q) {
                $w->where('gender', 'like', "%{$q}%")
                  ->orWhere('category', 'like', "%{$q}%")
                  ->orWhere('age', 'like', "%{$q}%")
                  ->orWhere('ip_address', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        $assessments = $query->latest()->paginate(15)->withQueryString();
        return view('history', compact('assessments'));
    }

    /**
     * Agentic AI Health Co-Pilot: Endpoint Chat Konsultasi Cerdas
     */
    public function agentChat(Request $request, HealthAgentService $agentService)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
            'assessment' => 'nullable|array',
        ]);

        $message = $validated['message'];
        $history = $validated['history'] ?? [];
        
        $assessment = $validated['assessment'] ?? session('last_assessment');
        if (!$assessment) {
            $latest = Assessment::latest()->first();
            if ($latest) {
                $assessment = $latest->toArray();
            } else {
                $assessment = [
                    'gender' => 'Laki-laki',
                    'age' => 30,
                    'height' => 170,
                    'weight' => 65,
                    'bmi' => 22.5,
                    'category' => 'Sedang',
                    'score' => 10,
                    'high_bp' => false,
                    'high_chol' => false,
                    'phys_activity' => true,
                    'family_history' => false,
                ];
            }
        }

        try {
            $result = $agentService->processChat($message, $assessment, $history);
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Health Co-Pilot agentChat error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'reply' => 'Maaf, terjadi kendala saat memproses penalaran klinis. Silakan ulangi pertanyaan Anda.',
                'reasoning_steps' => ['⚠️ Terjadi kesalahan internal: ' . $e->getMessage()]
            ], 200);
        }
    }

    /**
     * Agentic AI Health Co-Pilot: Quick Action Generator
     */
    public function agentQuickAction(Request $request, HealthAgentService $agentService)
    {
        $validated = $request->validate([
            'action' => 'required|string|in:meal_plan,calorie_target,lab_tests,exercise,risk_explanation',
            'assessment' => 'nullable|array',
        ]);

        $actionMap = [
            'meal_plan' => 'Buatkan rekomendasi menu makan sehat harian rendah glikemik sesuai profil saya',
            'calorie_target' => 'Berapa target kalori harian dan batas konsumsi gula maksimal saya?',
            'lab_tests' => 'Pemeriksaan laboratorium lanjutan apa yang dianjurkan untuk kondisi saya?',
            'exercise' => 'Rekomendasikan jadwal dan jenis olahraga yang aman untuk saya',
            'risk_explanation' => 'Jelaskan faktor risiko apa saja yang membuat skor saya seperti ini',
        ];

        $prompt = $actionMap[$validated['action']] ?? 'Konsultasi kesehatan diabetes';
        $assessment = $validated['assessment'] ?? session('last_assessment');
        if (!$assessment) {
            $latest = Assessment::latest()->first();
            $assessment = $latest ? $latest->toArray() : [
                'gender' => 'Laki-laki',
                'age' => 30,
                'height' => 170,
                'weight' => 65,
                'bmi' => 22.5,
                'category' => 'Sedang',
                'score' => 10,
            ];
        }

        try {
            $result = $agentService->processChat($prompt, $assessment, []);
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Health Co-Pilot agentQuickAction error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'reply' => 'Gagal memproses aksi cepat saat ini. Silakan ulangi beberapa saat lagi.',
                'reasoning_steps' => ['⚠️ Kesalahan aksi cepat: ' . $e->getMessage()]
            ], 200);
        }
    }

    /**
     * Agentic AI Health Co-Pilot: Analisis Berkas Medis / Hasil Lab
     */
    public function agentUploadFile(Request $request, HealthAgentService $agentService)
    {
        $request->validate([
            'medical_file' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,webp,txt,csv',
            'note' => 'nullable|string|max:500',
            'assessment' => 'nullable|string',
        ]);

        $file = $request->file('medical_file');
        $note = $request->input('note');
        
        $assessmentData = null;
        if ($request->filled('assessment')) {
            $decoded = json_decode($request->input('assessment'), true);
            if (is_array($decoded)) {
                $assessmentData = $decoded;
            }
        }

        if (!$assessmentData) {
            $assessmentData = session('last_assessment');
        }
        if (!$assessmentData) {
            $latest = Assessment::latest()->first();
            $assessmentData = $latest ? $latest->toArray() : [
                'gender' => 'Laki-laki',
                'age' => 30,
                'height' => 170,
                'weight' => 65,
                'bmi' => 22.5,
                'category' => 'Sedang',
                'score' => 10,
            ];
        }

        try {
            $result = $agentService->analyzeUploadedFile($file, $note, $assessmentData);
            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Health Co-Pilot agentUploadFile error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'reply' => 'Terjadi kendala saat menganalisis berkas medis. Pastikan berkas tidak rusak.',
                'reasoning_steps' => ['⚠️ Kesalahan pemrosesan berkas: ' . $e->getMessage()]
            ], 200);
        }
    }

    /**
     * Ekspor Dokumen Laporan Medis Lengkap (PDF / Printable View)
     */
    public function exportReport(Request $request, HealthAgentService $agentService, $id = null)
    {
        if ($id) {
            $assessment = Assessment::findOrFail($id)->toArray();
        } else {
            $assessment = session('last_assessment');
            if (!$assessment) {
                $latest = Assessment::latest()->first();
                $assessment = $latest ? $latest->toArray() : [
                    'gender' => 'Laki-laki',
                    'age' => 30,
                    'height' => 170,
                    'weight' => 65,
                    'bmi' => 22.5,
                    'score' => 10,
                    'category' => 'Sedang',
                    'color' => '#f37920',
                    'high_bp' => false,
                    'high_chol' => false,
                    'phys_activity' => true,
                    'family_history' => false,
                    'created_at' => now(),
                    'recommendations' => [
                        'Lakukan pemeriksaan gula darah dan tekanan darah secara berkala.',
                        'Batasi konsumsi makanan dengan kadar gula tinggi dan juga makanan / minuman kemasan.',
                        'Konsumsi makanan bergizi, tinggi protein dan kaya serat. Istirahat yang cukup dan olahraga teratur.',
                        'Konsultasi dengan dokter spesialis penyakit dalam.'
                    ]
                ];
            }
        }

        $gender = $assessment['gender'] ?? 'Laki-laki';
        $age = (int)($assessment['age'] ?? 30);
        $height = (float)($assessment['height'] ?? 170);
        $weight = (float)($assessment['weight'] ?? 65);
        $bmi = (float)($assessment['bmi'] ?? 24.5);
        $active = !empty($assessment['phys_activity']);
        $category = $assessment['category'] ?? 'Sedang';

        $biometrics = $agentService->calculateBiometrics($gender, $age, $height, $weight, $bmi, $active, $category);

        return view('medical_report', compact('assessment', 'biometrics'));
    }

    /**
     * Mendapatkan path executable Python yang valid
     */
    private function getPythonBinary(): string
    {
        if ($envPath = env('PYTHON_PATH')) {
            if (file_exists($envPath)) {
                return $envPath;
            }
        }

        $candidates = [
            base_path('../.venv/Scripts/python.exe'),
            base_path('.venv/Scripts/python.exe'),
            'C:\\laragon\\bin\\python\\python-3.10\\python.exe',
            'python3',
            'python',
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        return 'python';
    }

    /**
     * Tampilan Halaman Masuk / Login
     */
    public function login()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }
        return view('login');
    }

    /**
     * Proses Validasi Masuk / Login Admin (Email & Password)
     */
    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $throttleKey = 'login-attempt:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan masuk. Mohon tunggu {$seconds} detik demi keamanan.",
            ])->onlyInput('email');
        }

        $fieldType = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        
        $loginData = [
            $fieldType => $credentials['email'],
            'password' => $credentials['password'],
        ];

        if (auth()->attempt($loginData, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, ' . auth()->user()->name . '!');
        }

        // Penyiapan aman akun admin awal bila belum ada
        if ($credentials['email'] === 'admin@klikdokter.com' && $credentials['password'] === 'admin123') {
            $admin = User::firstOrCreate(
                ['email' => 'admin@klikdokter.com'],
                [
                    'name' => 'Admin Diabetes',
                    'password' => bcrypt('admin123'),
                    'role' => 'admin',
                    'phone' => '081234567890'
                ]
            );
            $admin->update(['role' => 'admin']);
            auth()->login($admin);
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, Administrator!');
        }

        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Kredensial email atau kata sandi tidak cocok.',
        ])->onlyInput('email');
    }

    /**
     * Permintaan Pengiriman Kode Verifikasi WhatsApp
     */
    public function requestWhatsAppOtp(Request $request, WhatsAppService $whatsAppService)
    {
        $validated = $request->validate([
            'identifier' => 'required|string',
        ]);

        $throttleKey = 'otp-req:' . $request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => "Terlalu banyak permintaan kode. Mohon tunggu {$seconds} detik."
            ], 429);
        }

        $idVal = trim($validated['identifier']);
        $normalizedPhone = $whatsAppService->normalizePhone($idVal);

        $user = User::where('phone', $idVal)
            ->orWhere('phone', $normalizedPhone)
            ->orWhere('email', $idVal)
            ->first();

        // Inisialisasi nomor admin bawaan jika akun admin belum memiliki nomor telepon
        if (!$user) {
            $adminDefault = User::where('email', 'admin@klikdokter.com')->first();
            if ($adminDefault && ($idVal === '081234567890' || $idVal === 'admin@klikdokter.com' || str_contains($normalizedPhone, '81234567890'))) {
                $adminDefault->update(['phone' => '081234567890', 'role' => 'admin']);
                $user = $adminDefault;
            }
        }

        if (!$user) {
            RateLimiter::hit($throttleKey, 60);
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp atau akun admin tidak ditemukan dalam sistem.'
            ], 404);
        }

        if (method_exists($user, 'isAdmin') && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan administrator yang berwenang.'
            ], 403);
        }

        RateLimiter::hit($throttleKey, 60);
        $result = $whatsAppService->sendOtp($user);
        $result['user_id'] = $user->id;

        return response()->json($result);
    }

    /**
     * Verifikasi Kode OTP WhatsApp
     */
    public function verifyWhatsAppOtp(Request $request, WhatsAppService $whatsAppService)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'code' => 'required|string|size:6',
        ]);

        $user = User::findOrFail($validated['user_id']);

        if (method_exists($user, 'isAdmin') && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan administrator yang berwenang.'
            ], 403);
        }

        $result = $whatsAppService->verifyOtp($user, $validated['code']);

        if ($result['success']) {
            auth()->login($user);
            $request->session()->regenerate();
            $result['redirect'] = route('admin.dashboard');
            $result['user_name'] = $user->name;
        }

        return response()->json($result);
    }

    /**
     * Proses Keluar / Logout
     */
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}


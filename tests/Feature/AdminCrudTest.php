<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\SiteContent;
use App\Models\Assessment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::firstOrCreate(
            ['email' => 'admin@klikdokter.com'],
            [
                'name' => 'Administrator Diabetes Uji Coba',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567890'
            ]
        );
        $this->admin->role = 'admin';
        $this->admin->save();
    }

    /**
     * 1. Pengunjung tanpa login tidak bisa mengakses panel admin
     */
    public function test_guest_cannot_access_admin_panel(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/articles')->assertRedirect('/login');
        $this->get('/admin/content')->assertRedirect('/login');
    }

    /**
     * 2. Admin dapat melihat dashboard dan daftar artikel
     */
    public function test_admin_can_view_dashboard_and_articles_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Panel Kendali Administrator');

        $responseArticles = $this->actingAs($this->admin)->get('/admin/articles');
        $responseArticles->assertStatus(200);
        $responseArticles->assertSee('Kelola Berita');
        $responseArticles->assertDontSee('Cek Risiko');
    }

    /**
     * 3. Admin dapat MENAMBAH (Create) artikel baru
     */
    public function test_admin_can_create_article(): void
    {
        $payload = [
            'title' => 'Judul Artikel Pengujian CRUD',
            'category' => 'Edukasi Khusus',
            'summary' => 'Ringkasan artikel pengujian penambahan berita oleh admin.',
            'content' => '<p>Konten artikel pengujian berhasil dipublikasikan oleh administrator.</p>',
            'author' => 'Dr. Uji Coba',
            'is_published' => 1,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/articles', $payload);
        $response->assertRedirect('/admin/articles');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('articles', [
            'title' => 'Judul Artikel Pengujian CRUD',
            'category' => 'Edukasi Khusus',
        ]);

        // Verifikasi langsung tayang di halaman publik berita
        $responsePublic = $this->get('/berita-artikel');
        $responsePublic->assertStatus(200);
        $responsePublic->assertSee('Judul Artikel Pengujian CRUD');
    }

    /**
     * 4. Admin dapat MENGEDIT (Update) artikel yang sudah ada
     */
    public function test_admin_can_edit_article(): void
    {
        $article = Article::create([
            'title' => 'Judul Artikel Awal',
            'category' => 'Edukasi',
            'summary' => 'Ringkasan awal sebelum diedit.',
            'content' => '<p>Konten awal sebelum revisi.</p>',
            'is_published' => 1,
        ]);
        $this->assertNotNull($article);

        $editPayload = [
            'title' => 'Judul Artikel Setelah Diedit Admin',
            'category' => 'Revisi Medis',
            'summary' => 'Ringkasan yang sudah diperbarui oleh admin.',
            'content' => '<p>Isi artikel ini telah disunting melalui panel admin.</p>',
            'is_published' => 1,
        ];

        $response = $this->actingAs($this->admin)->put("/admin/articles/{$article->id}", $editPayload);
        $response->assertRedirect('/admin/articles');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Judul Artikel Setelah Diedit Admin',
            'category' => 'Revisi Medis',
        ]);
    }

    /**
     * 5. Admin dapat MENGHAPUS (Delete) artikel
     */
    public function test_admin_can_delete_article(): void
    {
        $article = Article::create([
            'title' => 'Artikel Sementara untuk Dihapus',
            'category' => 'Testing',
            'summary' => 'Ringkasan sementara.',
            'content' => '<p>Akan segera dihapus.</p>',
            'is_published' => true,
        ]);

        $this->assertDatabaseHas('articles', ['id' => $article->id]);

        $response = $this->actingAs($this->admin)->delete("/admin/articles/{$article->id}");
        $response->assertRedirect('/admin/articles');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    /**
     * 6. Admin dapat MENGUBAH TAMPILAN BERANDA & SELURUH HALAMAN (CMS SiteContent)
     */
    public function test_admin_can_edit_landing_content(): void
    {
        $payload = [
            'contents' => [
                'hero_pill' => 'Program Khusus Edukasi 2026',
                'hero_title' => 'Judul Hero Baru dari Admin',
                'hero_cta_text' => 'Cek Skrining Kilat',
                'pemicu_title' => 'Pemicu Risiko Terbaru',
                'pilar_title' => 'Pilar Hidup Sehat 2026',
                'banner_cta_title' => 'Mulai Sekarang Tanpa Biaya',
                'contact_phone' => '0899-8888-7777',
                'quiz_subtitle' => 'Petunjuk Baru Skrining Kilat Mandiri',
            ]
        ];

        $response = $this->actingAs($this->admin)->post('/admin/content', $payload);
        $response->assertRedirect('/admin/content');
        $response->assertSessionHas('success');

        // Pastikan nilai terupdate di DB
        $this->assertEquals('Program Khusus Edukasi 2026', SiteContent::get('hero_pill'));
        $this->assertEquals('Cek Skrining Kilat', SiteContent::get('hero_cta_text'));
        $this->assertEquals('0899-8888-7777', SiteContent::get('contact_phone'));

        // Pastikan langsung tampil di halaman depan publik (Landing Page)
        $landing = $this->get('/');
        $landing->assertStatus(200);
        $landing->assertSee('Program Khusus Edukasi 2026');
        $landing->assertSee('Judul Hero Baru dari Admin');
        $landing->assertSee('Cek Skrining Kilat');
        $landing->assertSee('Pemicu Risiko Terbaru');
        $landing->assertSee('Pilar Hidup Sehat 2026');
        $landing->assertSee('0899-8888-7777');

        // Pastikan tampil di halaman kuesioner
        $quiz = $this->get('/cek-risiko');
        $quiz->assertStatus(200);
        $quiz->assertSee('Petunjuk Baru Skrining Kilat Mandiri');
    }

    /**
     * 7. Admin dapat MENGHAPUS riwayat pemeriksaan tertentu
     */
    public function test_admin_can_delete_assessment(): void
    {
        $assessment = Assessment::create([
            'gender' => 'Laki-laki',
            'age' => 40,
            'height' => 170,
            'weight' => 70,
            'bmi' => 24.2,
            'score' => 8,
            'category' => 'Sedang',
            'color' => '#f37920',
        ]);

        $this->assertDatabaseHas('assessments', ['id' => $assessment->id]);

        $response = $this->actingAs($this->admin)->delete("/admin/assessment/{$assessment->id}");
        $response->assertRedirect('/riwayat');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('assessments', ['id' => $assessment->id]);
    }

    /**
     * 8. Admin dapat mengunggah gambar file, lampiran berkas, dan menyimpan tambahan teks 1, 2, 3, 4
     */
    public function test_admin_can_upload_image_attachment_and_extra_texts(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $imageFile = \Illuminate\Http\UploadedFile::fake()->image('gambar_klinik.png', 600, 400);
        $attachmentFile = \Illuminate\Http\UploadedFile::fake()->create('panduan_diet_lengkap.pdf', 100, 'application/pdf');

        $payload = [
            'title' => 'Edukasi Khusus Pola Makan Sehat',
            'category' => 'Nutrisi',
            'summary' => 'Ringkasan panduan pola makan diabetes.',
            'content' => '<p>Materi lengkap edukasi nutrisi klinis diabetes uji coba.</p>',
            'author' => 'dr. Gizi Klinis',
            'image_file' => $imageFile,
            'attachment_file' => $attachmentFile,
            'attachment_name' => 'Buku Panduan Pola Makan 2026.pdf',
            'extra_text_1' => 'Kurangi konsumsi karbohidrat sederhana secara bertahap.',
            'extra_text_2' => 'Perbanyak asupan serat dari sayuran hijau segar.',
            'extra_text_3' => 'Pantau kadar glukosa darah 2 jam setelah makan.',
            'extra_text_4' => 'Konsultasikan dengan dokter spesialis penyakit dalam.',
            'is_published' => 1,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/articles', $payload);
        $response->assertRedirect('/admin/articles');
        $response->assertSessionHas('success');

        $article = Article::where('title', 'Edukasi Khusus Pola Makan Sehat')->first();
        $this->assertNotNull($article);
        $this->assertEquals('Nutrisi', $article->category);
        $this->assertNotNull($article->image);
        $this->assertNotNull($article->attachment_file);
        $this->assertEquals('Buku Panduan Pola Makan 2026.pdf', $article->attachment_name);
        $this->assertEquals('Kurangi konsumsi karbohidrat sederhana secara bertahap.', $article->extra_text_1);
        $this->assertEquals('Perbanyak asupan serat dari sayuran hijau segar.', $article->extra_text_2);
        $this->assertEquals('Pantau kadar glukosa darah 2 jam setelah makan.', $article->extra_text_3);
        $this->assertEquals('Konsultasikan dengan dokter spesialis penyakit dalam.', $article->extra_text_4);

        // Uji tampilan detail artikel publik
        $detailResponse = $this->get('/berita-artikel/' . $article->slug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Kurangi konsumsi karbohidrat sederhana');
        $detailResponse->assertSee('Perbanyak asupan serat');
        $detailResponse->assertSee('Pantau kadar glukosa');
        $detailResponse->assertSee('Konsultasikan dengan dokter');
        $detailResponse->assertSee('Buku Panduan Pola Makan 2026.pdf');
    }

    /**
     * 9. Admin dapat mengedit artikel: ganti kategori (rol pemilihan), ubah teks 1, 2, 3, 4, dan remove attachment
     */
    public function test_admin_can_update_article_with_category_dropdown_and_extra_texts(): void
    {
        $article = Article::create([
            'title' => 'Artikel Sebelum Diedit Lengkap',
            'category' => 'Diabetes',
            'summary' => 'Summary sebelum diedit.',
            'content' => '<p>Konten lama.</p>',
            'attachment_file' => 'https://example.com/file_lama.pdf',
            'attachment_name' => 'File Lama.pdf',
            'extra_text_1' => 'Teks lama 1',
            'is_published' => 1,
        ]);

        $updatePayload = [
            'title' => 'Artikel Baru Setelah Diedit Lengkap',
            'category' => 'Aktivitas',
            'summary' => 'Summary baru yang sudah direvisi.',
            'content' => '<p>Konten baru hasil penyuntingan.</p>',
            'extra_text_1' => 'Teks baru 1: Olahraga 30 menit per hari.',
            'extra_text_2' => 'Teks baru 2: Jalan cepat teratur.',
            'extra_text_3' => 'Teks baru 3: Cukupi istirahat.',
            'extra_text_4' => 'Teks baru 4: Tetap terhidrasi.',
            'remove_attachment' => 1,
            'is_published' => 1,
        ];

        $response = $this->actingAs($this->admin)->put("/admin/articles/{$article->id}", $updatePayload);
        $response->assertRedirect('/admin/articles');
        $response->assertSessionHas('success');

        $updatedArticle = Article::find($article->id);
        $this->assertEquals('Artikel Baru Setelah Diedit Lengkap', $updatedArticle->title);
        $this->assertEquals('Aktivitas', $updatedArticle->category);
        $this->assertEquals('Teks baru 1: Olahraga 30 menit per hari.', $updatedArticle->extra_text_1);
        $this->assertEquals('Teks baru 2: Jalan cepat teratur.', $updatedArticle->extra_text_2);
        $this->assertEquals('Teks baru 3: Cukupi istirahat.', $updatedArticle->extra_text_3);
        $this->assertEquals('Teks baru 4: Tetap terhidrasi.', $updatedArticle->extra_text_4);
        $this->assertNull($updatedArticle->attachment_file);
        $this->assertNull($updatedArticle->attachment_name);
    }

    /**
     * 10. Admin dapat menambah poin dinamis dengan teks dan input file, mengeditnya, serta menghapus poin
     */
    public function test_admin_can_use_dynamic_extra_items_with_text_and_files(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $doc1 = \Illuminate\Http\UploadedFile::fake()->create('panduan_kalori.pdf', 50, 'application/pdf');

        // 1. Tambah artikel dengan extra_items dinamis
        $payload = [
            'title' => 'Manajemen Kalori Diabetes Modern',
            'category' => 'Nutrisi',
            'summary' => 'Panduan menghitung kalori harian.',
            'content' => '<p>Konten manajemen kalori lengkap.</p>',
            'extra_items' => [
                0 => [
                    'text' => 'Poin A: Batasi gula murni maksimal 25 gram per hari.',
                    'file' => $doc1,
                    'file_name' => 'Tabel_Kalori_2026.pdf',
                ],
                1 => [
                    'text' => 'Poin B: Perbanyak jalan kaki setelah makan malam.',
                    'file' => null,
                    'file_name' => '',
                ],
            ],
            'is_published' => 1,
        ];

        $res = $this->actingAs($this->admin)->post('/admin/articles', $payload);
        $res->assertRedirect('/admin/articles');

        $article = Article::where('title', 'Manajemen Kalori Diabetes Modern')->first();
        $this->assertNotNull($article);
        $this->assertCount(2, $article->extra_items);
        $this->assertEquals('Poin A: Batasi gula murni maksimal 25 gram per hari.', $article->extra_items[0]['text']);
        $this->assertEquals('Tabel_Kalori_2026.pdf', $article->extra_items[0]['file_name']);
        $this->assertNotNull($article->extra_items[0]['file']);
        $this->assertEquals('Poin B: Perbanyak jalan kaki setelah makan malam.', $article->extra_items[1]['text']);

        // Uji halaman create dan edit dapat diakses
        $createPage = $this->actingAs($this->admin)->get('/admin/articles/create');
        $createPage->assertStatus(200);
        $createPage->assertSee('Isi Lengkap Penjelasan Artikel (Tanpa Kode HTML)');

        $editPage = $this->actingAs($this->admin)->get("/admin/articles/{$article->id}/edit");
        $editPage->assertStatus(200);
        $editPage->assertSee('Isi Lengkap Penjelasan Artikel (Tanpa Kode HTML)');

        // 2. Edit: Edit teks poin 1, Hapus poin 2, dan Tambah poin baru 3
        $docNew = \Illuminate\Http\UploadedFile::fake()->create('jadwal_jalan.pdf', 50, 'application/pdf');
        $updatePayload = [
            'title' => 'Manajemen Kalori Diabetes Modern Terupdate',
            'category' => 'Nutrisi',
            'summary' => 'Panduan menghitung kalori harian yang direvisi.',
            'content' => '<p>Konten manajemen kalori revisi.</p>',
            'extra_items' => [
                0 => [
                    'text' => 'Poin A REVISI: Batasi gula murni maksimal 20 gram.',
                    'existing_file' => $article->extra_items[0]['file'],
                    'existing_file_name' => $article->extra_items[0]['file_name'],
                ],
                1 => [
                    'text' => 'Poin C BARU: Jadwal aktivitas mingguan.',
                    'file' => $docNew,
                    'file_name' => 'Jadwal_Mingguan.pdf',
                ],
            ],
            'is_published' => 1,
        ];

        $editRes = $this->actingAs($this->admin)->put("/admin/articles/{$article->id}", $updatePayload);
        $editRes->assertRedirect('/admin/articles');

        $article->refresh();
        $this->assertEquals('Manajemen Kalori Diabetes Modern Terupdate', $article->title);
        $this->assertCount(2, $article->extra_items);
        $this->assertEquals('Poin A REVISI: Batasi gula murni maksimal 20 gram.', $article->extra_items[0]['text']);
        $this->assertEquals('Poin C BARU: Jadwal aktivitas mingguan.', $article->extra_items[1]['text']);
        $this->assertEquals('Jadwal_Mingguan.pdf', $article->extra_items[1]['file_name']);

        // Uji detail artikel publik menampilkan poin & tombol download file
        $publicDetail = $this->get('/berita-artikel/' . $article->slug);
        $publicDetail->assertStatus(200);
        $publicDetail->assertSee('Poin A REVISI: Batasi gula murni maksimal 20 gram.');
        $publicDetail->assertSee('Poin C BARU: Jadwal aktivitas mingguan.');
        $publicDetail->assertSee('Jadwal_Mingguan.pdf');
    }

    /**
     * 11. Admin dapat mengelola logo (akses halaman, upload logo baru, reset default)
     */
    public function test_admin_can_manage_logo_and_reset(): void
    {
        // 1. Pengunjung ditolak
        $this->get('/admin/logo')->assertRedirect('/login');

        // 2. Admin dapat mengakses halaman pengaturan logo
        $response = $this->actingAs($this->admin)->get('/admin/logo');
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Logo Website');
        $response->assertSee('Simpan Perubahan Logo');

        // 3. Upload file logo baru
        $fakeLogo = \Illuminate\Http\UploadedFile::fake()->image('custom_brand_logo.png', 300, 100);
        $updateRes = $this->actingAs($this->admin)->post('/admin/logo', [
            'logo_file' => $fakeLogo,
            'site_name' => 'Diabetes Care Official'
        ]);
        $updateRes->assertRedirect('/admin/logo');
        $updateRes->assertSessionHas('success');

        $this->assertEquals('Diabetes Care Official', SiteContent::get('site_name'));
        $this->assertNotEmpty(SiteContent::get('site_logo'));

        // 4. Logo kustom muncul di navbar / beranda publik
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee(SiteContent::get('site_logo'));

        // 5. Admin dapat mereset logo ke default
        $resetRes = $this->actingAs($this->admin)->post('/admin/logo/reset');
        $resetRes->assertRedirect('/admin/logo');
        $resetRes->assertSessionHas('success');
        $this->assertEmpty(SiteContent::get('site_logo'));
    }

    /**
     * 12. Admin dapat mengubah hero beranda dan mereset ke default
     */
    public function test_admin_can_update_hero_settings_and_reset(): void
    {
        $fakeHeroBg = \Illuminate\Http\UploadedFile::fake()->image('hero_banner_hospital.jpg', 1200, 600);

        $payload = [
            'contents' => [
                'hero_title' => 'Revolusi Kesehatan Diabetes Indonesia',
                'hero_pill' => 'AI Klinis XGBoost 2026',
                'hero_description' => 'Platform skrining risiko diabetes terlengkap dengan akurasi 98%.',
                'hero_cta_text' => 'Mulai Skrining AI',
                'hero_cta_url' => '/kuesioner',
                'hero_cta2_text' => 'Lihat Penjelasan',
                'hero_cta2_url' => '/tentang',
                'hero_overlay_opacity' => '0.85',
            ],
            'hero_bg_file' => $fakeHeroBg,
        ];

        $updateRes = $this->actingAs($this->admin)->post('/admin/content', $payload);
        $updateRes->assertRedirect('/admin/content');
        $updateRes->assertSessionHas('success');

        // Cek tersimpan di SiteContent
        $this->assertEquals('Revolusi Kesehatan Diabetes Indonesia', SiteContent::get('hero_title'));
        $this->assertEquals('AI Klinis XGBoost 2026', SiteContent::get('hero_pill'));
        $this->assertEquals('0.85', SiteContent::get('hero_overlay_opacity'));
        $this->assertNotEmpty(SiteContent::get('hero_bg_image'));

        // Cek tampil langsung di beranda tanpa edit kode
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee('Revolusi Kesehatan Diabetes Indonesia');
        $homeRes->assertSee('AI Klinis XGBoost 2026');
        $homeRes->assertSee('Mulai Skrining AI');

        // Reset hero ke default
        $resetRes = $this->actingAs($this->admin)->post('/admin/hero/reset');
        $resetRes->assertRedirect('/admin/content');
        $resetRes->assertSessionHas('success');

        $this->assertEmpty(SiteContent::get('hero_bg_image'));
        $this->assertEquals('85', SiteContent::get('hero_overlay_opacity'));
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubTitlesAndArticleCrudTest extends TestCase
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
     * Test admin can create article with intro_title and conclusion_title
     */
    public function test_admin_can_create_article_with_sub_titles(): void
    {
        $payload = [
            'title' => 'Panduan Pencegahan Diabetes Lengkap',
            'category' => 'Pencegahan',
            'summary' => 'Ringkasan komprehensif panduan pencegahan diabetes melitus tipe 2 bagi masyarakat umum.',
            'intro_title' => 'Latar Belakang & Pengantar Medis',
            'intro' => 'Diabetes melitus merupakan salah satu penyakit metabolik paling banyak dialami masyarakat dunia.',
            'conclusion_title' => 'Kesimpulan Akhir & Harapan Sehat',
            'conclusion' => 'Menerapkan pola hidup aktif dan gizi seimbang dapat mencegah komplikasi diabetes secara efektif.',
            'content_items' => [
                [
                    'number' => 1,
                    'title' => 'Pola Makan Seimbang',
                    'text' => 'Kurangi konsumsi gula berlebih dan perbanyak sayur kaya serat.',
                    'file_name' => '',
                ]
            ],
            'tags' => 'Pencegahan, Pola Makan, Hidup Sehat',
            'references' => 'PERKENI 2021. Pedoman Pengelolaan Diabetes Melitus Tipe 2.',
            'is_published' => 1,
        ];

        $res = $this->actingAs($this->admin)->post('/admin/articles', $payload);
        $res->assertRedirect('/admin/articles');
        $res->assertSessionHas('success');

        $article = Article::where('title', 'Panduan Pencegahan Diabetes Lengkap')->first();
        $this->assertNotNull($article);
        $this->assertEquals('Latar Belakang & Pengantar Medis', $article->intro_title);
        $this->assertEquals('Kesimpulan Akhir & Harapan Sehat', $article->conclusion_title);
        $this->assertEquals('Latar Belakang & Pengantar Medis', $article->structured_data['intro_title']);
        $this->assertEquals('Kesimpulan Akhir & Harapan Sehat', $article->structured_data['conclusion_title']);

        // Test display on public article detail page
        $publicRes = $this->get('/berita-artikel/' . $article->slug);
        $publicRes->assertStatus(200);
        $publicRes->assertSee('Latar Belakang & Pengantar Medis');
        $publicRes->assertSee('Kesimpulan Akhir & Harapan Sehat');
        $publicRes->assertSee('Pola Makan Seimbang');
    }

    /**
     * Test admin can update article sub titles
     */
    public function test_admin_can_update_sub_titles(): void
    {
        $article = Article::create([
            'title' => 'Artikel Sebelum Update Sub Judul',
            'category' => 'Nutrisi',
            'summary' => 'Ringkasan artikel.',
            'intro_title' => 'Judul Pembuka Lama',
            'intro' => 'Paragraf pembuka lama.',
            'conclusion_title' => 'Judul Penutup Lama',
            'conclusion' => 'Paragraf penutup lama.',
            'content' => '<p>Konten lama.</p>',
            'is_published' => 1,
        ]);

        $updatePayload = [
            'title' => 'Artikel Setelah Update Sub Judul',
            'category' => 'Nutrisi',
            'summary' => 'Ringkasan artikel baru yang telah direvisi.',
            'intro_title' => 'Sub Pembuka: Menakar Risiko Glukosa',
            'intro' => 'Paragraf pembuka yang diperbarui secara medis.',
            'conclusion_title' => 'Sub Penutup: Rekomendasi Terapi Gaya Hidup',
            'conclusion' => 'Pesan penutup yang direvisi.',
            'is_published' => 1,
        ];

        $res = $this->actingAs($this->admin)->put('/admin/articles/' . $article->id, $updatePayload);
        $res->assertRedirect('/admin/articles');
        $res->assertSessionHas('success');

        $updated = Article::find($article->id);
        $this->assertEquals('Sub Pembuka: Menakar Risiko Glukosa', $updated->intro_title);
        $this->assertEquals('Sub Penutup: Rekomendasi Terapi Gaya Hidup', $updated->conclusion_title);

        $publicRes = $this->get('/berita-artikel/' . $updated->slug);
        $publicRes->assertStatus(200);
        $publicRes->assertSee('Sub Pembuka: Menakar Risiko Glukosa');
        $publicRes->assertSee('Sub Penutup: Rekomendasi Terapi Gaya Hidup');
    }

    /**
     * Test admin can delete article successfully
     */
    public function test_admin_can_delete_article_with_delete_and_post(): void
    {
        $article1 = Article::create([
            'title' => 'Artikel Uji Hapus DELETE',
            'category' => 'Umum',
            'summary' => 'Ringkasan untuk uji hapus.',
            'content' => '<p>Konten uji hapus.</p>',
            'is_published' => 1,
        ]);

        $res1 = $this->actingAs($this->admin)->delete('/admin/articles/' . $article1->id);
        $res1->assertRedirect('/admin/articles');
        $this->assertDatabaseMissing('articles', ['id' => $article1->id]);

        $article2 = Article::create([
            'title' => 'Artikel Uji Hapus POST',
            'category' => 'Umum',
            'summary' => 'Ringkasan untuk uji hapus via route post.',
            'content' => '<p>Konten uji hapus post.</p>',
            'is_published' => 1,
        ]);

        $res2 = $this->actingAs($this->admin)->post('/admin/articles/' . $article2->id . '/delete');
        $res2->assertRedirect('/admin/articles');
        $this->assertDatabaseMissing('articles', ['id' => $article2->id]);
    }
}

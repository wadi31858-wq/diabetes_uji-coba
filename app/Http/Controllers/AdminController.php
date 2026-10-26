<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\SiteContent;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Dashboard Utama Administrator
     */
    public function dashboard()
    {
        $stats = [
            'total_assessments' => Assessment::count(),
            'total_articles' => Article::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'risk_low' => Assessment::where('category', 'Rendah')->count(),
            'risk_medium' => Assessment::where('category', 'Sedang')->count(),
            'risk_high' => Assessment::where('category', 'Tinggi')->count(),
        ];

        $recentAssessments = Assessment::latest()->take(6)->get();
        $recentArticles = Article::latest()->take(4)->get();

        return view('admin.dashboard', compact('stats', 'recentAssessments', 'recentArticles'));
    }

    /**
     * Daftar Semua Artikel (Kelola Berita)
     */
    public function articlesIndex(Request $request)
    {
        $query = Article::query();

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('category', 'like', "%{$q}%")
                  ->orWhere('summary', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $articles = $query->latest()->paginate(10)->withQueryString();
        $categories = Article::select('category')->distinct()->pluck('category');

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    /**
     * Form Tambah Artikel Baru
     */
    public function articleCreate()
    {
        return view('admin.articles.create');
    }

    /**
     * Simpan Artikel Baru
     */
    public function articleStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'summary' => 'required|string|max:5000',
            'intro_title' => 'nullable|string|max:255',
            'intro' => 'nullable|string',
            'conclusion_title' => 'nullable|string|max:255',
            'conclusion' => 'nullable|string',
            'tags' => 'nullable|string',
            'references' => 'nullable|string',
            'content' => 'nullable|string',
            'content_items' => 'nullable|array',
            'content_items.*.number' => 'nullable',
            'content_items.*.title' => 'nullable|string|max:255',
            'content_items.*.text' => 'nullable|string',
            'content_items.*.file' => 'nullable|file|max:20480',
            'content_items.*.file_name' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:1000',
            'image_file' => 'nullable|file|image|max:10240',
            'attachment_file' => 'nullable|file|max:20480',
            'attachment_name' => 'nullable|string|max:255',
            'extra_items' => 'nullable|array',
            'extra_text_1' => 'nullable|string',
            'extra_text_2' => 'nullable|string',
            'extra_text_3' => 'nullable|string',
            'extra_text_4' => 'nullable|string',
            'author' => 'nullable|string|max:150',
            'is_published' => 'nullable|boolean',
        ]);

        $slug = Str::slug($validated['title']);
        if (empty($slug)) {
            $slug = 'artikel-' . time();
        }
        $originalSlug = $slug;
        $counter = 1;
        while (Article::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-" . $counter++;
        }

        $defaultImages = [
            'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=1000&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=1000&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=1000&auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=1000&auto=format&fit=crop&q=80'
        ];

        // Handle Image File Upload atau Image URL
        $imageUrl = !empty($validated['image']) ? $validated['image'] : $defaultImages[array_rand($defaultImages)];
        if ($request->hasFile('image_file')) {
            $imageFile = $request->file('image_file');
            $dir = public_path('uploads/articles');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            $filename = time() . '_' . Str::slug(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move($dir, $filename);
            $imageUrl = asset('uploads/articles/' . $filename);
        }

        // Process Dynamic Content Items (Nomer, Judul, Penjelasan & File tanpa kode HTML)
        $savedContentItems = [];
        $rawItems = $request->input('content_items', $request->input('extra_items', []));

        if (is_array($rawItems)) {
            foreach ($rawItems as $idx => $itemData) {
                $itemNum = !empty($itemData['number']) ? $itemData['number'] : ($idx + 1);
                $itemTitle = isset($itemData['title']) ? trim($itemData['title']) : '';
                $itemText = isset($itemData['text']) ? trim($itemData['text']) : '';
                $itemFileName = isset($itemData['file_name']) ? trim($itemData['file_name']) : '';
                $itemFileUrl = null;

                $fileKey = "content_items.{$idx}.file";
                if (!$request->hasFile($fileKey)) {
                    $fileKey = "extra_items.{$idx}.file";
                }

                if ($request->hasFile($fileKey)) {
                    $attFile = $request->file($fileKey);
                    $attDir = public_path('uploads/articles');
                    if (!file_exists($attDir)) {
                        mkdir($attDir, 0755, true);
                    }
                    $origName = $attFile->getClientOriginalName();
                    $attFilename = time() . "_{$idx}_" . Str::slug(pathinfo($origName, PATHINFO_FILENAME)) . '.' . $attFile->getClientOriginalExtension();
                    $attFile->move($attDir, $attFilename);
                    $itemFileUrl = asset('uploads/articles/' . $attFilename);
                    if (empty($itemFileName)) {
                        $itemFileName = $origName;
                    }
                }

                if (!empty($itemTitle) || !empty($itemText) || !empty($itemFileUrl)) {
                    $savedContentItems[] = [
                        'number' => $itemNum,
                        'title' => $itemTitle,
                        'text' => $itemText,
                        'file' => $itemFileUrl,
                        'file_name' => $itemFileName ?: null,
                    ];
                }
            }
        }

        // Fallback untuk legacy extra_text_1..4 jika content_items kosong
        if (empty($savedContentItems)) {
            for ($i = 1; $i <= 4; $i++) {
                $et = $request->input("extra_text_{$i}");
                if (!empty($et)) {
                    $savedContentItems[] = [
                        'number' => $i,
                        'title' => '',
                        'text' => $et,
                        'file' => null,
                        'file_name' => null,
                    ];
                }
            }
        }

        // Build structured data (Intro, Points, Conclusion, Tags, References)
        $introTitle = $request->input('intro_title');
        $intro = $request->input('intro');
        $conclusionTitle = $request->input('conclusion_title');
        $conclusion = $request->input('conclusion');
        $tagsRaw = $request->input('tags');
        $refsRaw = $request->input('references');

        $tagsArray = !empty($tagsRaw) 
            ? array_values(array_filter(array_map('trim', explode(',', $tagsRaw))))
            : [$validated['category'], 'Pola Hidup Sehat'];

        $refsArray = !empty($refsRaw)
            ? array_values(array_filter(array_map('trim', explode("\n", $refsRaw))))
            : [];

        $structuredData = [
            'intro_title' => $introTitle,
            'intro' => $intro,
            'points' => $savedContentItems,
            'conclusion_title' => $conclusionTitle,
            'conclusion' => $conclusion,
            'tags' => $tagsArray,
            'references' => $refsArray,
        ];

        // Tentukan isi konten artikel HTML editorial yang rapi
        if (!empty($savedContentItems) || !empty($intro) || !empty($conclusion) || !empty($introTitle) || !empty($conclusionTitle)) {
            $compiledContent = $this->compileContentFromStructured($structuredData, $validated['summary']);
        } else {
            $compiledContent = !empty($validated['content']) ? $validated['content'] : '<p class="text-sm sm:text-base text-gray-800 leading-relaxed mb-4">' . e($validated['summary']) . '</p>';
        }

        // Legacy Attachment File Upload
        $attachmentUrl = null;
        $attachmentName = null;
        if ($request->hasFile('attachment_file')) {
            $att = $request->file('attachment_file');
            $attDir = public_path('uploads/attachments');
            if (!file_exists($attDir)) {
                mkdir($attDir, 0755, true);
            }
            $originalName = $att->getClientOriginalName();
            $attFilename = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $att->getClientOriginalExtension();
            $att->move($attDir, $attFilename);
            $attachmentUrl = asset('uploads/attachments/' . $attFilename);
            $attachmentName = !empty($request->input('attachment_name')) ? $request->input('attachment_name') : $originalName;
        }

        // Sync legacy columns extra_text_1..4
        $extra1 = $savedContentItems[0]['text'] ?? $request->input('extra_text_1');
        $extra2 = $savedContentItems[1]['text'] ?? $request->input('extra_text_2');
        $extra3 = $savedContentItems[2]['text'] ?? $request->input('extra_text_3');
        $extra4 = $savedContentItems[3]['text'] ?? $request->input('extra_text_4');

        if (empty($attachmentUrl) && !empty($savedContentItems[0]['file'])) {
            $attachmentUrl = $savedContentItems[0]['file'];
            $attachmentName = $savedContentItems[0]['file_name'];
        }

        // Jika request hanya mengirimkan extra_items flat (seperti pada tes unit legacy)
        $isRawExtraList = $request->has('extra_items') && !$request->has('content_items') && empty($intro) && empty($conclusion) && empty($introTitle) && empty($conclusionTitle);
        $extraItemsToSave = $isRawExtraList ? $savedContentItems : $structuredData;

        Article::create([
            'slug' => $slug,
            'title' => $validated['title'],
            'category' => $validated['category'],
            'date' => now()->format('d/m/Y'),
            'updated_at_display' => now()->translatedFormat('d M Y'),
            'image' => $imageUrl,
            'attachment_file' => $attachmentUrl,
            'attachment_name' => $attachmentName,
            'extra_text_1' => $extra1,
            'extra_text_2' => $extra2,
            'extra_text_3' => $extra3,
            'extra_text_4' => $extra4,
            'intro_title' => $introTitle,
            'conclusion_title' => $conclusionTitle,
            'extra_items' => $extraItemsToSave,
            'summary' => $validated['summary'],
            'content' => $compiledContent,
            'author' => (!empty($validated['author'])) ? $validated['author'] : 'Tim Medis Diabetes Uji Coba',
            'is_published' => $request->has('is_published') ? (bool)$request->input('is_published') : true,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berita berhasil ditambahkan!');
    }

    /**
     * Form Edit Artikel
     */
    public function articleEdit($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update Artikel
     */
    /**
     * Update Artikel
     */
    public function articleUpdate(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'summary' => 'required|string|max:5000',
            'intro_title' => 'nullable|string|max:255',
            'intro' => 'nullable|string',
            'conclusion_title' => 'nullable|string|max:255',
            'conclusion' => 'nullable|string',
            'tags' => 'nullable|string',
            'references' => 'nullable|string',
            'content' => 'nullable|string',
            'content_items' => 'nullable|array',
            'content_items.*.number' => 'nullable',
            'content_items.*.title' => 'nullable|string|max:255',
            'content_items.*.text' => 'nullable|string',
            'content_items.*.file' => 'nullable|file|max:20480',
            'content_items.*.file_name' => 'nullable|string|max:255',
            'content_items.*.existing_file' => 'nullable|string',
            'content_items.*.existing_file_name' => 'nullable|string',
            'content_items.*.remove_file' => 'nullable',
            'image' => 'nullable|string|max:1000',
            'image_file' => 'nullable|file|image|max:10240',
            'attachment_file' => 'nullable|file|max:20480',
            'attachment_name' => 'nullable|string|max:255',
            'remove_attachment' => 'nullable|boolean',
            'extra_items' => 'nullable|array',
            'extra_text_1' => 'nullable|string',
            'extra_text_2' => 'nullable|string',
            'extra_text_3' => 'nullable|string',
            'extra_text_4' => 'nullable|string',
            'author' => 'nullable|string|max:150',
            'is_published' => 'nullable|boolean',
        ]);

        // Handle Image
        $imageUrl = !empty($validated['image']) ? $validated['image'] : $article->image;
        if ($request->hasFile('image_file')) {
            $imageFile = $request->file('image_file');
            $dir = public_path('uploads/articles');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            $filename = time() . '_' . Str::slug(pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move($dir, $filename);
            $imageUrl = asset('uploads/articles/' . $filename);
        }

        // Process Dynamic Content Items (Nomer, Judul, Penjelasan & File)
        $savedContentItems = [];
        $rawItems = $request->input('content_items', $request->input('extra_items'));

        if (is_array($rawItems)) {
            foreach ($rawItems as $idx => $itemData) {
                $itemNum = !empty($itemData['number']) ? $itemData['number'] : ($idx + 1);
                $itemTitle = isset($itemData['title']) ? trim($itemData['title']) : '';
                $itemText = isset($itemData['text']) ? trim($itemData['text']) : '';
                $itemFileName = isset($itemData['file_name']) ? trim($itemData['file_name']) : '';
                $itemFileUrl = $itemData['existing_file'] ?? null;
                $itemExistingName = $itemData['existing_file_name'] ?? null;

                if (!empty($itemData['remove_file'])) {
                    $itemFileUrl = null;
                    $itemFileName = null;
                } else {
                    if (empty($itemFileName) && !empty($itemExistingName)) {
                        $itemFileName = $itemExistingName;
                    }
                }

                $fileKey = "content_items.{$idx}.file";
                if (!$request->hasFile($fileKey)) {
                    $fileKey = "extra_items.{$idx}.file";
                }

                if ($request->hasFile($fileKey)) {
                    $attFile = $request->file($fileKey);
                    $attDir = public_path('uploads/articles');
                    if (!file_exists($attDir)) {
                        mkdir($attDir, 0755, true);
                    }
                    $origName = $attFile->getClientOriginalName();
                    $attFilename = time() . "_{$idx}_" . Str::slug(pathinfo($origName, PATHINFO_FILENAME)) . '.' . $attFile->getClientOriginalExtension();
                    $attFile->move($attDir, $attFilename);
                    $itemFileUrl = asset('uploads/articles/' . $attFilename);
                    if (empty($itemFileName)) {
                        $itemFileName = $origName;
                    }
                }

                if (!empty($itemTitle) || !empty($itemText) || !empty($itemFileUrl)) {
                    $savedContentItems[] = [
                        'number' => $itemNum,
                        'title' => $itemTitle,
                        'text' => $itemText,
                        'file' => $itemFileUrl,
                        'file_name' => $itemFileName ?: null,
                    ];
                }
            }
        }

        // Fallback untuk legacy extra_text_1..4 jika content_items kosong
        if (empty($savedContentItems) && $rawItems === null) {
            for ($i = 1; $i <= 4; $i++) {
                $et = $request->input("extra_text_{$i}");
                if (!empty($et)) {
                    $savedContentItems[] = [
                        'number' => $i,
                        'title' => '',
                        'text' => $et,
                        'file' => null,
                        'file_name' => null,
                    ];
                }
            }
        }

        // Build structured data
        $introTitle = $request->has('intro_title') ? $request->input('intro_title') : ($article->intro_title ?? ($article->structured_data['intro_title'] ?? ''));
        $intro = $request->has('intro') ? $request->input('intro') : ($article->structured_data['intro'] ?? '');
        $conclusionTitle = $request->has('conclusion_title') ? $request->input('conclusion_title') : ($article->conclusion_title ?? ($article->structured_data['conclusion_title'] ?? ''));
        $conclusion = $request->has('conclusion') ? $request->input('conclusion') : ($article->structured_data['conclusion'] ?? '');
        $tagsRaw = $request->input('tags');
        $refsRaw = $request->input('references');

        $tagsArray = !empty($tagsRaw) 
            ? array_values(array_filter(array_map('trim', explode(',', $tagsRaw))))
            : ($article->structured_data['tags'] ?? [$validated['category'], 'Pola Hidup Sehat']);

        $refsArray = !empty($refsRaw)
            ? array_values(array_filter(array_map('trim', explode("\n", $refsRaw))))
            : ($article->structured_data['references'] ?? []);

        $structuredData = [
            'intro_title' => $introTitle,
            'intro' => $intro,
            'points' => !empty($savedContentItems) ? $savedContentItems : ($article->structured_data['points'] ?? []),
            'conclusion_title' => $conclusionTitle,
            'conclusion' => $conclusion,
            'tags' => $tagsArray,
            'references' => $refsArray,
        ];

        // Tentukan isi konten artikel
        if (!empty($savedContentItems) || !empty($intro) || !empty($conclusion) || !empty($introTitle) || !empty($conclusionTitle)) {
            $compiledContent = $this->compileContentFromStructured($structuredData, $validated['summary']);
        } else {
            $compiledContent = !empty($validated['content']) ? $validated['content'] : $article->content;
        }

        // Handle Legacy Attachment File
        $attachmentUrl = $article->attachment_file;
        $attachmentName = $article->attachment_name;

        if ($request->boolean('remove_attachment')) {
            $attachmentUrl = null;
            $attachmentName = null;
        }

        if ($request->hasFile('attachment_file')) {
            $att = $request->file('attachment_file');
            $attDir = public_path('uploads/attachments');
            if (!file_exists($attDir)) {
                mkdir($attDir, 0755, true);
            }
            $originalName = $att->getClientOriginalName();
            $attFilename = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $att->getClientOriginalExtension();
            $att->move($attDir, $attFilename);
            $attachmentUrl = asset('uploads/attachments/' . $attFilename);
            $attachmentName = !empty($request->input('attachment_name')) ? $request->input('attachment_name') : $originalName;
        } elseif (!empty($request->input('attachment_name')) && $attachmentUrl) {
            $attachmentName = $request->input('attachment_name');
        }

        // Sync legacy extra_text_1..4
        $extra1 = $savedContentItems[0]['text'] ?? ($rawItems === null ? $request->input('extra_text_1', $article->extra_text_1) : null);
        $extra2 = $savedContentItems[1]['text'] ?? ($rawItems === null ? $request->input('extra_text_2', $article->extra_text_2) : null);
        $extra3 = $savedContentItems[2]['text'] ?? ($rawItems === null ? $request->input('extra_text_3', $article->extra_text_3) : null);
        $extra4 = $savedContentItems[3]['text'] ?? ($rawItems === null ? $request->input('extra_text_4', $article->extra_text_4) : null);

        if (empty($attachmentUrl) && !empty($savedContentItems[0]['file'])) {
            $attachmentUrl = $savedContentItems[0]['file'];
            $attachmentName = $savedContentItems[0]['file_name'];
        }

        $isRawExtraList = $request->has('extra_items') && !$request->has('content_items') && empty($intro) && empty($conclusion) && empty($introTitle) && empty($conclusionTitle);
        $extraItemsToSave = $isRawExtraList ? $savedContentItems : $structuredData;

        $article->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'updated_at_display' => now()->translatedFormat('d M Y'),
            'image' => $imageUrl,
            'attachment_file' => $attachmentUrl,
            'attachment_name' => $attachmentName,
            'extra_text_1' => $extra1,
            'extra_text_2' => $extra2,
            'extra_text_3' => $extra3,
            'extra_text_4' => $extra4,
            'intro_title' => $introTitle,
            'conclusion_title' => $conclusionTitle,
            'extra_items' => $extraItemsToSave,
            'summary' => $validated['summary'],
            'content' => $compiledContent,
            'author' => (!empty($validated['author'])) ? $validated['author'] : $article->author,
            'is_published' => $request->has('is_published') ? (bool)$request->input('is_published') : true,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Format data artikel terstruktur menjadi HTML editorial yang bersih dan elegan (seperti screenshot)
     */
    private function compileContentFromStructured(array $structured, string $fallbackSummary = ''): string
    {
        $html = '';

        // 1. Paragraf Pengantar (Intro)
        $introTitle = trim($structured['intro_title'] ?? '');
        $intro = trim($structured['intro'] ?? '');

        if (!empty($introTitle)) {
            $html .= '<h3 class="text-base sm:text-lg font-bold text-gray-900 mt-4 mb-2 tracking-tight">' . e($introTitle) . '</h3>';
        }

        if (!empty($intro)) {
            $introParagraphs = array_filter(array_map('trim', explode("\n\n", $intro)));
            foreach ($introParagraphs as $p) {
                if (!empty($p)) {
                    $html .= '<p class="text-sm sm:text-base text-gray-800 leading-relaxed mb-4">' . nl2br(e($p)) . '</p>';
                }
            }
        } elseif (!empty($fallbackSummary)) {
            $html .= '<p class="text-sm sm:text-base text-gray-800 leading-relaxed mb-4">' . e($fallbackSummary) . '</p>';
        }

        // 2. Poin-Poin Bernomor & Sub-Poin Bullet
        $points = $structured['points'] ?? [];
        foreach ($points as $idx => $it) {
            $num = !empty($it['number']) ? $it['number'] : ($idx + 1);
            $title = trim($it['title'] ?? '');
            $text = trim($it['text'] ?? '');
            $file = $it['file'] ?? null;
            $fileName = !empty($it['file_name']) ? $it['file_name'] : 'Unduh Berkas Lampiran';

            // 1. Judul Poin Bernomor
            if (!empty($title)) {
                $formattedTitle = preg_match('/^\d+[\.\)]\s*/', $title) ? $title : "{$num}. {$title}";
                $html .= '<h3 class="text-base sm:text-lg font-bold text-gray-900 mt-6 mb-2 tracking-tight">' . e($formattedTitle) . '</h3>';
            } elseif (!empty($num) && empty($title) && !empty($text)) {
                $lines = array_map('trim', explode("\n", $text));
                $firstLine = array_shift($lines);
                if (!preg_match('/^[\-\*\•]/u', $firstLine) && strlen($firstLine) < 120 && count($lines) > 0) {
                    $formattedTitle = preg_match('/^\d+[\.\)]\s*/', $firstLine) ? $firstLine : "{$num}. {$firstLine}";
                    $html .= '<h3 class="text-base sm:text-lg font-bold text-gray-900 mt-6 mb-2 tracking-tight">' . e($formattedTitle) . '</h3>';
                    $text = implode("\n", $lines);
                }
            }

            // 2. File Lampiran atau Gambar Pendukung (DI BAWAH JUDUL, DI ATAS PENJELASAN)
            if (!empty($file)) {
                $ext = strtolower(pathinfo(parse_url($file, PHP_URL_PATH), PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $html .= '<div class="my-3 overflow-hidden rounded-2xl border border-gray-100 shadow-2xs max-w-xl">';
                    $html .= '<img src="' . $file . '" alt="' . e($title ?: 'Gambar Poin ' . $num) . '" class="w-full h-auto object-cover max-h-96">';
                    $html .= '</div>';
                } else {
                    $html .= '<div class="my-3 p-3 bg-sky-50/80 rounded-xl border border-sky-100 flex items-center justify-between gap-3 max-w-xl">';
                    $html .= '<div class="flex items-center space-x-2 text-xs font-bold text-sky-950 truncate"><span class="text-base">📎</span><span class="truncate">' . e($fileName) . '</span></div>';
                    $html .= '<a href="' . $file . '" target="_blank" download class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-lg transition-all shrink-0 shadow-2xs">Unduh File &darr;</a>';
                    $html .= '</div>';
                }
            }

            // 3. Penjelasan / Sub-Poin (DI BAWAH FILE)
            if (!empty($text)) {
                $lines = array_map('trim', explode("\n", $text));
                $inBulletList = false;

                foreach ($lines as $line) {
                    if (empty($line)) {
                        if ($inBulletList) {
                            $html .= '</ul>';
                            $inBulletList = false;
                        }
                        continue;
                    }

                    // Deteksi tanda bullet (-, *, •)
                    if (preg_match('/^[\-\*\•]\s*(.*)$/u', $line, $matches)) {
                        if (!$inBulletList) {
                            $html .= '<ul class="space-y-1.5 my-2.5 text-sm sm:text-base text-gray-800">';
                            $inBulletList = true;
                        }
                        $html .= '<li class="flex items-start space-x-2"><span class="text-gray-900 font-bold shrink-0 mt-0.5">•</span><span>' . e($matches[1]) . '</span></li>';
                    } else {
                        if ($inBulletList) {
                            $html .= '</ul>';
                            $inBulletList = false;
                        }
                        $html .= '<p class="text-sm sm:text-base text-gray-800 leading-relaxed mb-3">' . e($line) . '</p>';
                    }
                }

                if ($inBulletList) {
                    $html .= '</ul>';
                }
            }
        }

        // 3. Paragraf Penutup (Conclusion)
        $conclusionTitle = trim($structured['conclusion_title'] ?? '');
        $conclusion = trim($structured['conclusion'] ?? '');
        if (!empty($conclusionTitle) || !empty($conclusion)) {
            $html .= '<div class="mt-6 pt-4 border-t border-gray-100 space-y-3">';
            if (!empty($conclusionTitle)) {
                $html .= '<h3 class="text-base sm:text-lg font-bold text-gray-900 mb-2 tracking-tight">' . e($conclusionTitle) . '</h3>';
            }
            if (!empty($conclusion)) {
                $concLines = array_filter(array_map('trim', explode("\n\n", $conclusion)));
                foreach ($concLines as $cLine) {
                    if (str_starts_with($cLine, '**') && str_ends_with($cLine, '**')) {
                        $html .= '<p class="text-sm sm:text-base font-extrabold text-gray-900">' . e(trim($cLine, '*')) . '</p>';
                    } else {
                        $html .= '<p class="text-sm sm:text-base text-gray-800 leading-relaxed">' . nl2br(e($cLine)) . '</p>';
                    }
                }
            }
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Format array item konten (nomor, penjelasan, file) menjadi tampilan HTML yang rapi
     */
    private function compileContentFromItems(array $items): string
    {
        return $this->compileContentFromStructured(['points' => $items]);
    }

    /**
     * Hapus Artikel
     */
    public function articleDestroy($id)
    {
        $article = Article::findOrFail($id);
        $title = $article->title;
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', "Artikel '{$title}' berhasil dihapus.");
    }

    /**
     * Kelola Konten Tampilan & Teks Beranda (CMS)
     */
    public function contentIndex()
    {
        $contents = SiteContent::all()->groupBy('group');
        return view('admin.content.index', compact('contents'));
    }

    /**
     * Update Konten Tampilan
     */
    public function contentUpdate(Request $request)
    {
        $request->validate([
            'hero_bg_file' => 'nullable|file|image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'hero_bg_file.image' => 'File background hero harus berupa gambar yang valid.',
            'hero_bg_file.mimes' => 'Format gambar background hero yang didukung: JPG, PNG, WEBP.',
            'hero_bg_file.max' => 'Ukuran gambar background hero maksimal 5MB.',
        ]);

        $items = $request->input('contents', []);

        // Handle Hero Background File Upload jika diunggah
        if ($request->hasFile('hero_bg_file')) {
            $file = $request->file('hero_bg_file');
            $dir = public_path('uploads/hero');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            
            // Hapus file lama jika ada
            $oldHeroBg = SiteContent::get('hero_bg_image');
            if (!empty($oldHeroBg)) {
                $oldPath = public_path(parse_url($oldHeroBg, PHP_URL_PATH));
                if (file_exists($oldPath) && str_contains($oldPath, 'uploads/hero')) {
                    @unlink($oldPath);
                }
            }

            $filename = 'hero_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $items['hero_bg_image'] = asset('uploads/hero/' . $filename);
        }

        foreach ($items as $key => $val) {
            SiteContent::set($key, $val);
        }

        return redirect()->route('admin.content.index')->with('success', 'Konten tampilan beranda berhasil diperbarui secara instan!');
    }

    /**
     * Kembalikan Pengaturan Hero ke Bawaan
     */
    public function heroReset()
    {
        $defaults = [
            'hero_pill' => 'Deteksi Dini Diabetes Uji Coba',
            'hero_title' => "Ketahui Risiko Diabetes\nAnda Sejak Dini",
            'hero_description' => 'Skrining risiko diabetes mandiri dalam 1 menit menggunakan algoritma Machine Learning XGBoost tervalidasi CDC BRFSS.',
            'hero_bg_image' => '',
            'hero_overlay_opacity' => '85',
            'hero_cta_text' => 'Mulai Cek Risiko Sekarang',
            'hero_cta_url' => route('quiz'),
            'hero_cta2_text' => 'Explore 5 Pilar ↓',
            'hero_cta2_url' => '#sectionPilar',
        ];

        $oldHeroBg = SiteContent::get('hero_bg_image');
        if (!empty($oldHeroBg)) {
            $oldPath = public_path(parse_url($oldHeroBg, PHP_URL_PATH));
            if (file_exists($oldPath) && str_contains($oldPath, 'uploads/hero')) {
                @unlink($oldPath);
            }
        }

        foreach ($defaults as $k => $v) {
            SiteContent::set($k, $v, 'hero');
        }

        return redirect()->route('admin.content.index')->with('success', 'Pengaturan Hero Beranda berhasil dikembalikan ke nilai default!');
    }

    /**
     * Halaman Pengaturan Logo Website
     */
    public function logoIndex()
    {
        $currentLogo = SiteContent::get('site_logo');
        $siteName = SiteContent::get('site_name', 'Diabetes Uji Coba');
        return view('admin.logo.index', compact('currentLogo', 'siteName'));
    }

    /**
     * Perbarui Logo Website
     */
    public function logoUpdate(Request $request)
    {
        $request->validate([
            'logo_file' => 'nullable|file|mimes:png,jpg,jpeg,svg|max:2048',
            'logo_url' => 'nullable|url|max:1000',
        ], [
            'logo_file.mimes' => 'Format file logo harus berupa PNG, JPG, JPEG, atau SVG.',
            'logo_file.max' => 'Ukuran file logo maksimal adalah 2MB (2048 KB).',
            'logo_url.url' => 'Format URL logo tidak valid.',
        ]);

        $logoUrl = null;

        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            
            // Validasi SVG sederhana untuk keamanan (pastikan XML valid dan tidak ada tag script)
            if (strtolower($file->getClientOriginalExtension()) === 'svg') {
                $svgContent = file_get_contents($file->getRealPath());
                if (stripos($svgContent, '<script') !== false || stripos($svgContent, 'javascript:') !== false) {
                    return back()->withErrors(['logo_file' => 'File SVG mengandung skrip yang tidak aman.']);
                }
            }

            $dir = public_path('uploads/branding');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }

            // Hapus logo lama jika ada di uploads/branding
            $oldLogo = SiteContent::get('site_logo');
            if (!empty($oldLogo)) {
                $oldPath = public_path(parse_url($oldLogo, PHP_URL_PATH));
                if (file_exists($oldPath) && str_contains($oldPath, 'uploads/branding')) {
                    @unlink($oldPath);
                }
            }

            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $logoUrl = asset('uploads/branding/' . $filename);
        } elseif ($request->filled('logo_url')) {
            $logoUrl = trim($request->input('logo_url'));
        }

        if ($request->filled('site_name')) {
            SiteContent::set('site_name', trim($request->input('site_name')), 'general', 'Nama Website');
        }

        if ($logoUrl) {
            SiteContent::set('site_logo', $logoUrl, 'branding', 'Logo Website');
            return redirect()->route('admin.logo.index')->with('success', 'Logo website berhasil diperbarui dan diterapkan ke seluruh halaman!');
        }

        if ($request->filled('site_name')) {
            return redirect()->route('admin.logo.index')->with('success', 'Nama website berhasil diperbarui!');
        }

        return redirect()->route('admin.logo.index')->with('error', 'Silakan pilih file logo baru atau masukkan URL logo.');
    }

    /**
     * Kembalikan Logo ke Bawaan (Default SVG)
     */
    public function logoReset()
    {
        $oldLogo = SiteContent::get('site_logo');
        if (!empty($oldLogo)) {
            $oldPath = public_path(parse_url($oldLogo, PHP_URL_PATH));
            if (file_exists($oldPath) && str_contains($oldPath, 'uploads/branding')) {
                @unlink($oldPath);
            }
        }

        SiteContent::set('site_logo', null, 'branding', 'Logo Website');

        return redirect()->route('admin.logo.index')->with('success', 'Logo berhasil dikembalikan ke logo bawaan sistem.');
    }

    /**
     * Hapus Data Riwayat Pemeriksaan
     */
    public function assessmentDestroy($id)
    {
        $assessment = Assessment::findOrFail($id);
        $assessment->delete();

        return redirect()->route('history')->with('success', "Rekam riwayat ID #{$id} berhasil dihapus.");
    }
}

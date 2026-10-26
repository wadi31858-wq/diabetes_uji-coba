<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'category',
        'date',
        'updated_at_display',
        'image',
        'attachment_file',
        'attachment_name',
        'summary',
        'intro_title',
        'conclusion_title',
        'content',
        'extra_text_1',
        'extra_text_2',
        'extra_text_3',
        'extra_text_4',
        'extra_items',
        'author',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'extra_items' => 'array',
    ];

    protected $appends = [
        'formatted_extra_items',
        'parsed_content_items',
        'structured_data',
        'tags_list',
        'references_list',
    ];

    /**
     * Mengambil data terstruktur artikel (intro, points, conclusion, tags, references)
     */
    public function getStructuredDataAttribute(): array
    {
        $extra = $this->extra_items;

        if (is_array($extra) && isset($extra['points'])) {
            return [
                'intro_title' => $extra['intro_title'] ?? ($this->attributes['intro_title'] ?? ''),
                'intro' => $extra['intro'] ?? '',
                'points' => is_array($extra['points']) ? $extra['points'] : [],
                'conclusion_title' => $extra['conclusion_title'] ?? ($this->attributes['conclusion_title'] ?? ''),
                'conclusion' => $extra['conclusion'] ?? '',
                'tags' => !empty($extra['tags']) ? (is_array($extra['tags']) ? $extra['tags'] : explode(',', (string)$extra['tags'])) : [$this->category ?? 'Diabetes', 'Pola Hidup Sehat'],
                'references' => !empty($extra['references']) ? (is_array($extra['references']) ? $extra['references'] : explode("\n", (string)$extra['references'])) : [],
            ];
        }

        // Jika extra_items adalah list biasa
        if (is_array($extra) && !empty($extra)) {
            return [
                'intro_title' => $this->attributes['intro_title'] ?? '',
                'intro' => '',
                'points' => $extra,
                'conclusion_title' => $this->attributes['conclusion_title'] ?? '',
                'conclusion' => '',
                'tags' => [$this->category ?? 'Diabetes', 'Pola Hidup Sehat'],
                'references' => [],
            ];
        }

        // Fallback: parse dari content lama tanpa HTML
        $raw = $this->content ?? '';
        $intro = '';
        $conclusion = '';
        $points = [];

        if (!empty(trim($raw))) {
            $cleaned = preg_replace('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', "\n\n$1:\n", $raw);
            $cleaned = preg_replace('/<\/p>/i', "\n\n", $cleaned);
            $cleaned = preg_replace('/<br\s*\/?>/i', "\n", $cleaned);
            $cleaned = strip_tags($cleaned);

            $paragraphs = array_values(array_filter(array_map('trim', explode("\n\n", $cleaned))));
            if (count($paragraphs) > 0) {
                $intro = array_shift($paragraphs);
            }
            if (count($paragraphs) > 1) {
                $conclusion = array_pop($paragraphs);
            }

            foreach ($paragraphs as $idx => $p) {
                $points[] = [
                    'number' => $idx + 1,
                    'title' => '',
                    'text' => $p,
                    'file' => null,
                    'file_name' => null,
                ];
            }
        }

        if (empty($points)) {
            $points = [
                ['number' => 1, 'title' => '', 'text' => '', 'file' => null, 'file_name' => null]
            ];
        }

        return [
            'intro' => $intro,
            'points' => $points,
            'conclusion' => $conclusion,
            'tags' => [$this->category ?? 'Diabetes', 'Pola Hidup Sehat'],
            'references' => [],
        ];
    }

    /**
     * Mengambil daftar tags untuk artikel
     */
    public function getTagsListAttribute(): array
    {
        $structured = $this->structured_data;
        $tags = $structured['tags'] ?? [];
        if (is_string($tags)) {
            $tags = array_map('trim', explode(',', $tags));
        }
        $tags = array_filter(array_map('trim', (array)$tags));
        if (empty($tags)) {
            $tags = ['Gaya Hidup', 'Pola Hidup Sehat'];
            if (!empty($this->category)) {
                $tags[] = $this->category;
            }
        }
        return array_values(array_unique($tags));
    }

    /**
     * Mengambil daftar referensi medis
     */
    public function getReferencesListAttribute(): array
    {
        $structured = $this->structured_data;
        $refs = $structured['references'] ?? [];
        if (is_string($refs)) {
            $refs = array_filter(array_map('trim', explode("\n", $refs)));
        }
        if (empty($refs)) {
            return [
                'PERKENI. Pedoman Pengelolaan dan Pencegahan Diabetes Melitus Tipe 2 Dewasa di Indonesia. Indonesia: PB PERKENI; 2021.',
                'Rini S, Manto OAD, Irawan A. Hubungan Pola Hidup dengan Kadar Gula Darah Pasien dengan Diabetes Melitus Tipe 2. Journal of Nursing Invention. 2022;3(2).',
                'Susanti N, Rahayu S, Mawarni D, Sabilla W. Hubungan Pengetahuan, Faktor Risiko dan Tindakan Pencegahan Diabetes Melitus. Healthy Tadulako Journal (Jurnal Kesehatan Tadulako). 2024;10(4):712-721.'
            ];
        }
        return array_values(array_filter(array_map('trim', (array)$refs)));
    }

    /**
     * Parse konten artikel menjadi item-item penjelasan tanpa kode HTML
     */
    public function getParsedContentItemsAttribute()
    {
        $structured = $this->structured_data;
        return $structured['points'] ?? [
            ['number' => 1, 'title' => '', 'text' => '', 'file' => null, 'file_name' => null]
        ];
    }

    /**
     * Mengambil daftar poin tambahan teks beserta berkas/lampiran
     */
    public function getFormattedExtraItemsAttribute()
    {
        $structured = $this->structured_data;
        $points = $structured['points'] ?? [];
        $items = [];
        foreach ($points as $p) {
            if (!empty($p['text']) || !empty($p['file'])) {
                $items[] = $p;
            }
        }
        return $items;
    }

    /**
     * Mengambil konten artikel terkompilasi secara dinamis
     * Menjamin urutan selalu: Judul Poin -> File/Gambar -> Penjelasan
     */
    public function getContentAttribute($value)
    {
        $extra = $this->extra_items;

        if (is_array($extra) && (!empty($extra['points']) || !empty($extra['intro']))) {
            return $this->compileContentFromStructured($this->structured_data, $this->summary ?? '');
        }

        if (is_array($extra) && !empty($extra) && isset($extra[0])) {
            return $this->compileContentFromStructured(['points' => $extra], $this->summary ?? '');
        }

        return $value;
    }

    /**
     * Kompilasi struktur data menjadi HTML dengan urutan:
     * 1. Judul Poin
     * 2. File / Foto Pendukung (Di bawah judul, di atas penjelasan)
     * 3. Penjelasan / Sub-poin (Di bawah file)
     */
    public function compileContentFromStructured(array $structured, string $fallbackSummary = ''): string
    {
        $html = '';

        // 1. Paragraf Pengantar (Intro)
        $introTitle = trim($structured['intro_title'] ?? ($this->attributes['intro_title'] ?? ''));
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

        // 2. Poin-Poin Bernomor
        $points = $structured['points'] ?? [];
        foreach ($points as $idx => $it) {
            $num = !empty($it['number']) ? $it['number'] : ($idx + 1);
            $title = trim($it['title'] ?? '');
            $text = trim($it['text'] ?? '');
            $file = $it['file'] ?? null;
            $fileName = !empty($it['file_name']) ? $it['file_name'] : 'Unduh Berkas Lampiran';

            // 1. Judul Poin
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

            // 2. File / Foto Pendukung (DI BAWAH JUDUL, DI ATAS PENJELASAN)
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
        $conclusionTitle = trim($structured['conclusion_title'] ?? ($this->attributes['conclusion_title'] ?? ''));
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
     * Auto generate slug jika kosong
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            if (empty($article->date)) {
                $article->date = now()->format('d/m/Y');
            }
            if (empty($article->updated_at_display)) {
                $article->updated_at_display = now()->translatedFormat('d M Y');
            }
        });
    }
}

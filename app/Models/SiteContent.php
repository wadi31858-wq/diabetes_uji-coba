<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'group',
        'title',
        'content',
    ];

    /**
     * Ambil konten berdasarkan key dengan nilai default
     */
    public static function get(string $key, ?string $default = null): string
    {
        try {
            return Cache::remember("site_content_{$key}", 3600, function () use ($key, $default) {
                $record = static::where('key', $key)->first();
                return $record && !empty($record->content) ? $record->content : ($default ?? '');
            });
        } catch (\Throwable $e) {
            return $default ?? '';
        }
    }

    /**
     * Set / update konten berdasarkan key
     */
    public static function set(string $key, ?string $content, string $group = 'general', ?string $title = null): self
    {
        Cache::forget("site_content_{$key}");
        return static::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'title' => $title ?? ucfirst(str_replace('_', ' ', $key)),
                'content' => $content,
            ]
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($item) {
            Cache::forget("site_content_{$item->key}");
        });

        static::deleted(function ($item) {
            Cache::forget("site_content_{$item->key}");
        });
    }
}

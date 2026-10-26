<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Artikel & Berita Edukasi
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category')->default('Diabetes');
            $table->string('date')->nullable();
            $table->string('updated_at_display')->nullable();
            $table->text('image')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('author')->default('Tim Medis Diabetes Uji Coba');
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // 2. Tabel Konten Tampilan & Teks Website (CMS Beranda)
        Schema::create('site_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('group')->default('general');
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_contents');
        Schema::dropIfExists('articles');
    }
};

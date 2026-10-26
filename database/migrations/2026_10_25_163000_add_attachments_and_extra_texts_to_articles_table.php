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
        Schema::table('articles', function (Blueprint $table) {
            $table->string('attachment_file')->nullable()->after('image');
            $table->string('attachment_name')->nullable()->after('attachment_file');
            $table->text('extra_text_1')->nullable()->after('content');
            $table->text('extra_text_2')->nullable()->after('extra_text_1');
            $table->text('extra_text_3')->nullable()->after('extra_text_2');
            $table->text('extra_text_4')->nullable()->after('extra_text_3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'attachment_file',
                'attachment_name',
                'extra_text_1',
                'extra_text_2',
                'extra_text_3',
                'extra_text_4',
            ]);
        });
    }
};

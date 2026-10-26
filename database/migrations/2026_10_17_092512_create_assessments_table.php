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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('gender')->default('Laki-laki');
            $table->unsignedInteger('age')->default(30);
            $table->float('height')->default(170);
            $table->float('weight')->default(65);
            $table->float('bmi')->default(22.5);
            $table->boolean('high_bp')->default(false);
            $table->boolean('high_chol')->default(false);
            $table->boolean('phys_activity')->default(true);
            $table->boolean('family_history')->default(false);
            $table->unsignedInteger('score')->default(0);
            $table->tinyInteger('svm_prediction')->default(0);
            $table->string('category')->default('Sedang');
            $table->string('color', 20)->default('#f37920');
            $table->json('recommendations')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};

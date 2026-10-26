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
        Schema::table('assessments', function (Blueprint $table) {
            $table->boolean('smoker')->default(false)->after('family_history');
            $table->boolean('fruits_veggies')->default(true)->after('smoker');
            $table->boolean('chol_check')->default(true)->after('fruits_veggies');
            $table->boolean('heart_disease')->default(false)->after('chol_check');
            $table->boolean('gestational_history')->default(false)->after('heart_disease');
            $table->json('extra_data')->nullable()->after('recommendations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn(['smoker', 'fruits_veggies', 'chol_check', 'heart_disease', 'gestational_history', 'extra_data']);
        });
    }
};

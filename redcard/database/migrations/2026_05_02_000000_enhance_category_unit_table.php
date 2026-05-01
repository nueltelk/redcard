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
        Schema::table('category_unit', function (Blueprint $table) {
            $table->integer('priority')->default(0)->after('category_id'); // Prioritas kategori
            $table->boolean('is_primary')->default(false)->after('priority'); // Kategori utama
            $table->timestamps(); // Tracking waktu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_unit', function (Blueprint $table) {
            $table->dropColumn(['priority', 'is_primary', 'created_at', 'updated_at']);
        });
    }
};

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
        Schema::table('materis', function (Blueprint $table) {
            $table->enum('tipe', ['pdf', 'youtube'])->default('pdf')->after('judul');
            $table->string('youtube_url')->nullable()->after('tipe');
            // file is already nullable based on create_materis_table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'youtube_url']);
        });
    }
};

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
        Schema::create('bank_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained('mapels')->cascadeOnDelete();
            $table->foreignId('bab_id')->nullable()->constrained('babs')->cascadeOnDelete();
            $table->enum('tipe_soal', ['pg', 'bs', 'jodoh'])->default('pg');
            $table->text('pertanyaan');
            $table->json('opsi')->nullable();
            $table->json('jawaban_benar')->nullable(); // Can be string or JSON array depending on type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soals');
    }
};

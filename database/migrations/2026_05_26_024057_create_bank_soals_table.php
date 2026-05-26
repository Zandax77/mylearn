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
            $table->unsignedBigInteger('bab_id')->nullable();

            $table->enum('tipe_soal', ['pg', 'bs', 'jodoh'])->default('pg');
            $table->text('pertanyaan');
            $table->json('opsi')->nullable();
            $table->json('jawaban_benar')->nullable(); // Can be string or JSON array depending on type
            $table->timestamps();
        });

        // Add foreign key to babs table only if it exists
        if (Schema::hasTable('babs')) {
            Schema::table('bank_soals', function (Blueprint $table) {
                $table->foreign('bab_id')->references('id')->on('babs')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soals');
    }
};

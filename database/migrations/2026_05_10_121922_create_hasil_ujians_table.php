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
        Schema::create('hasil_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('ujian_id');
            $table->integer('nilai');
            $table->boolean('is_lulus');
            $table->timestamp('completed_at')->useCurrent();
            $table->timestamps();
        });

        // Add foreign key to ujians table only if it exists
        if (Schema::hasTable('ujians')) {
            Schema::table('hasil_ujians', function (Blueprint $table) {
                $table->foreign('ujian_id')->references('id')->on('ujians')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_ujians');
    }
};

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
        if (\Schema::hasTable('babs')) {
            Schema::table('materis', function (Blueprint $table) {
                $table->foreignId('bab_id')->nullable()->constrained('babs')->nullOnDelete();
                $table->integer('urutan')->default(1);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            $table->dropForeign(['bab_id']);
            $table->dropColumn('bab_id');
            $table->dropColumn('urutan');
        });
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    protected $fillable = [
        'mapel_id',
        'bab_id',
        'tipe_soal',
        'pertanyaan',
        'opsi',
        'jawaban_benar'
    ];

    protected $casts = [
        'opsi' => 'array',
        'jawaban_benar' => 'array',
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function bab()
    {
        return $this->belongsTo(Bab::class);
    }
}

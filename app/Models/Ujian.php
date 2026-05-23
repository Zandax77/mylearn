<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $fillable = ['judul', 'tipe', 'jumlah_soal_tampil', 'mapel_id', 'bab_id', 'passing_grade', 'durasi_menit'];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function bab()
    {
        return $this->belongsTo(Bab::class);
    }

    public function soals()
    {
        return $this->hasMany(Soal::class);
    }

    public function hasilUjians()
    {
        return $this->hasMany(HasilUjian::class);
    }
}

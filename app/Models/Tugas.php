<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $fillable = ['judul_tugas', 'deskripsi', 'deadline', 'file_tugas', 'mapel_id'];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }
}

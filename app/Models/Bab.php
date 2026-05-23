<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bab extends Model
{
    protected $fillable = ['judul', 'urutan', 'mapel_id'];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function materis()
    {
        return $this->hasMany(Materi::class)->orderBy('urutan');
    }

    public function ujians()
    {
        return $this->hasMany(Ujian::class);
    }
}

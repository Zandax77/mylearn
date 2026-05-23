<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $fillable = ['nama_mapel', 'guru_id'];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function materis()
    {
        return $this->hasMany(Materi::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mapel');
    }

    public function babs()
    {
        return $this->hasMany(Bab::class)->orderBy('urutan');
    }

    public function ujians()
    {
        return $this->hasMany(Ujian::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = ['nama_kelas', 'wali_kelas'];

    public function siswa()
    {
        return $this->hasMany(User::class, 'kelas_id')->where('role', 'siswa');
    }

    public function mapels()
    {
        return $this->belongsToMany(Mapel::class, 'kelas_mapel');
    }
}

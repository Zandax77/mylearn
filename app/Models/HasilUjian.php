<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    protected $fillable = ['siswa_id', 'ujian_id', 'nilai', 'is_lulus', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
        'is_lulus' => 'boolean',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresMateri extends Model
{
    protected $fillable = ['siswa_id', 'materi_id', 'is_read', 'read_at'];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $fillable = ['judul', 'file', 'mapel_id', 'tanggal_upload', 'bab_id', 'urutan'];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function bab()
    {
        return $this->belongsTo(Bab::class);
    }

    public function progresMateris()
    {
        return $this->hasMany(ProgresMateri::class);
    }
}

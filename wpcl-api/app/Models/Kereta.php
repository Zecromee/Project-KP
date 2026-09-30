<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kereta extends Model
{
    use HasFactory;

    protected $table = 'kereta';

    protected $primaryKey = 'id_kereta';

    protected $fillable = [
        'no_ka',
        'nama_ka',
        'relasi',
        'jam_berangkat',
        'jam_datang'
    ];

    public function pemeriksaan()
    {
        return $this->hasMany(Pemeriksaan::class, 'id_kereta', 'id_kereta');
    }

    public function sarana()
    {
        return $this->hasMany(Sarana::class, 'id_kereta', 'id_kereta')->orderBy('id_sarana');
    }
}
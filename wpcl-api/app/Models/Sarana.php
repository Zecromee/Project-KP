<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sarana extends Model
{
    use HasFactory;

    protected $table = 'sarana';

    protected $primaryKey = 'id_sarana';

    protected $fillable = [
        'id_kereta',
        'kode_sarana',
        'nomor_sarana',
        'seri_sarana',
        'depo_induk'
    ];

    public function kereta()
    {
        return $this->belongsTo(Kereta::class, 'id_kereta', 'id_kereta');
    }

    public function detailPemeriksaan()
    {
        return $this->hasMany(DetailPemeriksaan::class, 'id_sarana', 'id_sarana');
    }
}
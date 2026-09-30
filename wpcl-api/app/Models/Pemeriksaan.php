<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'pemeriksaan';

    protected $primaryKey = 'id_pemeriksaan';

    protected $fillable = [
        'nama_petugas',
        'nipp',
        'id_kereta',
        'id_pejabat',
        'pejabat_nama',
        'pejabat_nipp',
        'pejabat_jabatan',
        'tanggal',
        'business_area',
        'no_ref',
        'locotrack',
        'loco_id',
        'nomor_sarana_loco',
        'no_dokumen',
        'versi_dokumen',
        'catatan',
        'catatan_keseluruhan'
    ];

    public function kereta()
    {
        return $this->belongsTo(Kereta::class, 'id_kereta', 'id_kereta');
    }

    public function pejabat()
    {
        return $this->belongsTo(Pejabat::class, 'id_pejabat', 'id_pejabat');
    }

    public function detailPemeriksaan()
    {
        return $this->hasMany(DetailPemeriksaan::class, 'id_pemeriksaan', 'id_pemeriksaan');
    }
}
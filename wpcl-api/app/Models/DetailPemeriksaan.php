<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'detail_pemeriksaan';

    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_pemeriksaan',
        'id_sarana',
        'urutan',
        'pids_luar',
        'pids_dalam',
        'cctv',
        'wifi',
        'backup',
        'sisi_a',
        'sisi_e',
        'td_kecil',
        'td_besar',
        'keterangan'
    ];

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan', 'id_pemeriksaan');
    }

    public function sarana()
    {
        return $this->belongsTo(Sarana::class, 'id_sarana', 'id_sarana');
    }
}
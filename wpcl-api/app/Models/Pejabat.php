<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pejabat extends Model
{
    use HasFactory;

    protected $table = 'pejabat';
    protected $primaryKey = 'id_pejabat';

    protected $fillable = [
        'nama',
        'nipp',
        'jabatan',
        'is_active',
    ];

    public function pemeriksaan()
    {
        return $this->hasMany(Pemeriksaan::class, 'id_pejabat', 'id_pejabat');
    }
}

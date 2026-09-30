<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use App\Models\Kereta;
use App\Models\Sarana;
use App\Models\Pejabat;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPemeriksaan = Pemeriksaan::count();
        $totalKereta = Kereta::count();
        $totalSarana = Sarana::count();
        $totalPejabat = Pejabat::where('is_active', true)->count();

        $pemeriksaanTerbaru = Pemeriksaan::with('kereta', 'pejabat')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $locotrackBaik = Pemeriksaan::where('locotrack', 'B')->count();
        $locotrackRusak = Pemeriksaan::where('locotrack', 'R')->count();
        $locotrackTiada = Pemeriksaan::where('locotrack', 'T')->count();

        return view('admin.dashboard', compact(
            'totalPemeriksaan',
            'totalKereta',
            'totalSarana',
            'totalPejabat',
            'pemeriksaanTerbaru',
            'locotrackBaik',
            'locotrackRusak',
            'locotrackTiada'
        ));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pejabat;

class PejabatController extends Controller
{
    public function index()
    {
        $pejabat = Pejabat::where('is_active', true)->orderBy('id_pejabat')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Pejabat Penandatangan',
            'data' => $pejabat
        ]);
    }
}

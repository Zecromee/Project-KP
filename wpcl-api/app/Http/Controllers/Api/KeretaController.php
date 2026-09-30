<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kereta;

class KeretaController extends Controller
{
    public function index()
    {
        $kereta = Kereta::with('sarana')->orderBy('no_ka')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Kereta',
            'data' => $kereta
        ]);
    }
}
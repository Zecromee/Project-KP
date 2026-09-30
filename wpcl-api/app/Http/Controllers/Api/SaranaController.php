<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sarana;
use Illuminate\Http\Request;

class SaranaController extends Controller
{
    public function search(Request $request)
    {
        $keyword = trim($request->keyword);

        $sarana = Sarana::where('kode_sarana', 'like', "%{$keyword}%")
            ->orWhere('nomor_sarana', 'like', "%{$keyword}%")
            ->orWhere('seri_sarana', 'like', "%{$keyword}%")
            ->orderBy('kode_sarana')
            ->orderBy('nomor_sarana')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Sarana',
            'data' => $sarana
        ]);
    }

    public function byKereta($idKereta)
    {
        $sarana = Sarana::where('id_kereta', $idKereta)
            ->orderBy('id_sarana')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Sarana Kereta',
            'data' => $sarana
        ]);
    }
}
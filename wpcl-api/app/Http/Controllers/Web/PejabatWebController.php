<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pejabat;
use Illuminate\Http\Request;

class PejabatWebController extends Controller
{
    public function index()
    {
        $pejabatList = Pejabat::orderBy('id_pejabat')->get();
        return view('admin.pejabat.index', compact('pejabatList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nipp' => 'required|string|max:30',
            'jabatan' => 'required|string|max:100',
        ]);

        Pejabat::create([
            'nama' => $request->nama,
            'nipp' => $request->nipp,
            'jabatan' => $request->jabatan,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('web.pejabat.index')->with('success', 'Data pejabat berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nipp' => 'required|string|max:30',
            'jabatan' => 'required|string|max:100',
        ]);

        $pejabat = Pejabat::findOrFail($id);
        $pejabat->update([
            'nama' => $request->nama,
            'nipp' => $request->nipp,
            'jabatan' => $request->jabatan,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('web.pejabat.index')->with('success', 'Data pejabat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pejabat = Pejabat::findOrFail($id);
        $pejabat->delete();

        return redirect()->route('web.pejabat.index')->with('success', 'Data pejabat berhasil dihapus.');
    }
}

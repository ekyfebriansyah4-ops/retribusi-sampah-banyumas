<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GolonganTarif;
use Illuminate\Http\Request;

class GolonganTarifController extends Controller
{
    // Daftar semua golongan tarif (bisa dilihat semua user)
    public function index()
    {
        return response()->json(GolonganTarif::all());
    }

    // Khusus Admin: tambah golongan tarif baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_golongan' => 'required|string',
            'tarif_per_bulan' => 'required|integer',
        ]);

        $golongan = GolonganTarif::create($request->only(['nama_golongan', 'tarif_per_bulan']));

        return response()->json($golongan, 201);
    }

    // Khusus Admin: update golongan tarif
    public function update(Request $request, $id)
    {
        $golongan = GolonganTarif::findOrFail($id);
        $golongan->update($request->only(['nama_golongan', 'tarif_per_bulan']));

        return response()->json($golongan);
    }

    // Khusus Admin: hapus golongan tarif
    public function destroy($id)
    {
        GolonganTarif::findOrFail($id)->delete();

        return response()->json(['message' => 'Golongan tarif dihapus']);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    // Daftar tagihan milik user yang login
    public function index(Request $request)
    {
        $tagihan = Tagihan::where('iduser', $request->user()->id)
            ->with('detailTagihan')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($tagihan);
    }

    // Detail 1 tagihan
    public function show(Request $request, $id)
    {
        $tagihan = Tagihan::where('iduser', $request->user()->id)
            ->with('detailTagihan.items')
            ->findOrFail($id);

        return response()->json($tagihan);
    }

    // Khusus tagihan yang belum lunas
    public function belumLunas(Request $request)
    {
        $tagihan = Tagihan::where('iduser', $request->user()->id)
            ->where('status', 'belum_lunas')
            ->with('detailTagihan')
            ->get();

        return response()->json($tagihan);
    }

    // Khusus tagihan yang sudah lunas
    public function lunas(Request $request)
    {
        $tagihan = Tagihan::where('iduser', $request->user()->id)
            ->where('status', 'lunas')
            ->with('detailTagihan')
            ->get();

        return response()->json($tagihan);
    }
    public function infoTagihan(Request $request, $iduser)
{
    if ((int) $iduser !== $request->user()->id) {
        return response()->json(['message' => 'Akses ditolak'], 403);
    }

    $totalBelumLunas = Tagihan::where('iduser', $iduser)
        ->where('status', 'belum_lunas')
        ->sum('jumlah');

    return response()->json([
        'data' => [
            [
                'total_semua' => $totalBelumLunas,
                'total_rdf' => 0,
                'total_item' => 0,
                'total_timbangan' => 0,
            ],
        ],
    ]);
}
}
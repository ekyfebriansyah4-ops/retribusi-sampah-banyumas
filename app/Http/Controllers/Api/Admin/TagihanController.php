<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\DetailTagihan;
use App\Models\User;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    // Lihat semua tagihan dari semua warga
    public function index(Request $request)
    {
        $query = Tagihan::with(['user', 'detailTagihan']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    // Generate tagihan manual untuk 1 warga tertentu
    public function generate(Request $request)
    {
        $request->validate([
            'iduser' => 'required|exists:users,id',
            'bulan' => 'required|string',
            'tahun' => 'required|string',
        ]);

        $user = User::with('golonganTarif')->findOrFail($request->iduser);

        if (!$user->golonganTarif) {
            return response()->json(['message' => 'User belum memiliki golongan tarif'], 422);
        }

        $tagihan = Tagihan::create([
            'iduser' => $user->id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'jumlah' => $user->golonganTarif->tarif_per_bulan,
            'status' => 'belum_lunas',
        ]);

        DetailTagihan::create([
            'tagihan_id' => $tagihan->id,
            'kode_bayar' => 'RTB-' . $request->tahun . str_pad($tagihan->id, 6, '0', STR_PAD_LEFT),
            'tanggalbilling' => now(),
            'tanggalexpired' => now()->addDays(20),
            'nilai' => $user->golonganTarif->tarif_per_bulan,
            'bunga' => 0,
        ]);

        return response()->json($tagihan->load('detailTagihan'), 201);
    }

    // Generate otomatis untuk SEMUA warga aktif sekaligus
    public function generateSemua(Request $request)
    {
        $request->validate(['bulan' => 'required|string', 'tahun' => 'required|string']);

        $users = User::where('role', 'user')->where('status', 'aktif')->whereNotNull('golongan_tarif_id')->with('golonganTarif')->get();

        $count = 0;
        foreach ($users as $user) {
            $tagihan = Tagihan::create([
                'iduser' => $user->id,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'jumlah' => $user->golonganTarif->tarif_per_bulan,
                'status' => 'belum_lunas',
            ]);

            DetailTagihan::create([
                'tagihan_id' => $tagihan->id,
                'kode_bayar' => 'RTB-' . $request->tahun . str_pad($tagihan->id, 6, '0', STR_PAD_LEFT),
                'tanggalbilling' => now(),
                'tanggalexpired' => now()->addDays(20),
                'nilai' => $user->golonganTarif->tarif_per_bulan,
                'bunga' => 0,
            ]);
            $count++;
        }

        return response()->json(['message' => "$count tagihan berhasil dibuat"]);
    }

    // Verifikasi pembayaran manual (misal transfer, bukan QRIS)
    public function verifikasiBayar(Request $request, $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $tagihan->update(['status' => 'lunas']);

        $tagihan->detailTagihan()->update([
            'tanggal_bayar' => now(),
            'bukti' => $request->bukti ?? 'verifikasi_manual_admin',
        ]);

        return response()->json(['message' => 'Pembayaran diverifikasi', 'tagihan' => $tagihan->load('detailTagihan')]);
    }
}
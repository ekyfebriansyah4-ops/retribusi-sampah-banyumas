<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\DetailTagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QrisController extends Controller
{
    public function create(Request $request)
    {
        $request->validate(['tagihan_id' => 'required|exists:tagihan,id']);

        $tagihan = Tagihan::with('detailTagihan')->findOrFail($request->tagihan_id);

        if ($tagihan->status === 'lunas') {
            return response()->json(['message' => 'Tagihan ini sudah lunas'], 422);
        }

        $reference = 'QRIS-' . strtoupper(Str::random(10));

        $tagihan->detailTagihan->update([
            'qris_reference' => $reference,
            'qris_status' => 'pending',
        ]);

        return response()->json([
            'message' => 'QRIS berhasil dibuat (simulasi)',
            'qris_reference' => $reference,
            'nilai' => $tagihan->jumlah,
            'qris_url' => url('/api/qris/simulasi-bayar/' . $reference),
        ]);
    }

    public function simulasiBayar($reference)
    {
        $detail = DetailTagihan::where('qris_reference', $reference)->firstOrFail();

        $detail->update([
            'qris_status' => 'selesai',
            'tanggal_bayar' => now(),
            'bukti' => 'qris_' . $reference,
        ]);

        $detail->tagihan->update(['status' => 'lunas']);

        return response()->json(['message' => 'Pembayaran QRIS berhasil (simulasi)']);
    }
}
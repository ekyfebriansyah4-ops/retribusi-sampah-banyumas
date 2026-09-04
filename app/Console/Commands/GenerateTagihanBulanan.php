<?php

namespace App\Console\Commands;

use App\Models\Tagihan;
use App\Models\DetailTagihan;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateTagihanBulanan extends Command
{
    protected $signature = 'tagihan:generate-bulanan';

    protected $description = 'Generate tagihan retribusi otomatis untuk semua warga aktif setiap awal bulan';

    public function handle()
    {
        $bulan = now()->translatedFormat('F');
        $tahun = now()->format('Y');

        $users = User::where('role', 'user')
            ->where('status', 'aktif')
            ->whereNotNull('golongan_tarif_id')
            ->with('golonganTarif')
            ->get();

        $count = 0;
        foreach ($users as $user) {
            // Cegah duplikat kalau sudah pernah digenerate bulan ini
            $sudahAda = Tagihan::where('iduser', $user->id)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->exists();

            if ($sudahAda) {
                continue;
            }

            $tagihan = Tagihan::create([
                'iduser' => $user->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'jumlah' => $user->golonganTarif->tarif_per_bulan,
                'status' => 'belum_lunas',
            ]);

            DetailTagihan::create([
                'tagihan_id' => $tagihan->id,
                'kode_bayar' => 'RTB-' . $tahun . str_pad($tagihan->id, 6, '0', STR_PAD_LEFT),
                'tanggalbilling' => now(),
                'tanggalexpired' => now()->addDays(20),
                'nilai' => $user->golonganTarif->tarif_per_bulan,
                'bunga' => 0,
            ]);

            $count++;
        }

        $this->info("Berhasil generate $count tagihan untuk bulan $bulan $tahun");
    }
}
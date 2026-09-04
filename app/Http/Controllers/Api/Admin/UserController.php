<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Daftar semua warga (role user)
    public function index()
    {
        $users = User::where('role', 'user')->with('golonganTarif')->get();
        return response()->json($users);
    }

    // Assign/ubah golongan tarif warga
    public function updateGolongan(Request $request, $id)
    {
        $request->validate(['golongan_tarif_id' => 'required|exists:golongan_tarif,id']);

        $user = User::findOrFail($id);
        $user->update(['golongan_tarif_id' => $request->golongan_tarif_id]);

        return response()->json($user);
    }

    // Nonaktifkan warga (bukan hapus, sesuai kesepakatan)
    public function nonaktifkan($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'nonaktif']);

        return response()->json(['message' => 'User dinonaktifkan', 'user' => $user]);
    }

    // Aktifkan kembali
    public function aktifkan($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'aktif']);

        return response()->json(['message' => 'User diaktifkan kembali', 'user' => $user]);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GolonganTarif extends Model
{
    protected $table = 'golongan_tarif';

    protected $fillable = ['nama_golongan', 'tarif_per_bulan'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
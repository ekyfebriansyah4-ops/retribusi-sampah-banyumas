<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihan';

    protected $fillable = ['iduser', 'bulan', 'tahun', 'jumlah', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function detailTagihan()
    {
        return $this->hasOne(DetailTagihan::class);
    }
}
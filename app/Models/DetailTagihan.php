<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTagihan extends Model
{
    protected $table = 'detail_tagihan';

    protected $fillable = [
        'tagihan_id', 'kode_bayar', 'tanggalbilling', 'tanggalexpired',
        'nilai', 'bunga', 'truck', 'netto', 'skrd', 'tanggal_bayar', 'bukti',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }

    public function items()
    {
        return $this->hasMany(ItemRdf::class);
    }
}
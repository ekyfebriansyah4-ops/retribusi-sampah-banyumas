<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemRdf extends Model
{
    protected $table = 'item_rdf';

    protected $fillable = ['detail_tagihan_id', 'jenis', 'qty', 'harga'];

    public function detailTagihan()
    {
        return $this->belongsTo(DetailTagihan::class);
    }
}
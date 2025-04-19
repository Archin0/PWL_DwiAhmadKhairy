<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detail extends Model
{
    use HasFactory;

    protected $table = 'transaksi_detail';
    protected $primaryKey = 'id_detail';
    protected $fillable = [
        'id_barang',
        'id_transaksi',
        'jumlah',
        'subtotal',
        'created_at',
        'updated_at',
    ];
    public $timestamps = false;

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }
}

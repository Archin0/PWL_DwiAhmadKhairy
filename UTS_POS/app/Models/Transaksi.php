<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $fillable = [
        'id_user',
        'kode_transaksi',
        'pembeli',
        'total_barang',
        'diskon',
        'total_harga',
        'metode_pembayaran',
        'created_at',
        'updated_at',
    ];
    // public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(Detail::class, 'id_transaksi', 'id_transaksi');
    }
}

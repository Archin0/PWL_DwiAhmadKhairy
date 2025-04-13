<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Supply extends Model
{
    use HasFactory;

    protected $table = 'supply';
    protected $primaryKey = 'id_supply';    
    protected $fillable = ['id_supplier', 'id_barang', 'id_user', 'jumlah', 'harga_beli'];
    public $timestamps = false;

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

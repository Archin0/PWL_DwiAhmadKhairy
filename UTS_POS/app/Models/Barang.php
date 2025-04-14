<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'id_kategori',
        'id_supplier',
        'kode_barang', 
        'nama_barang', 
        'stok', 
        'harga', 
        'foto_barang',
        'created_at',
        'updated_at',];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function supplies() : HasMany
    {
        return $this->hasMany(Supply::class, 'id_barang');
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'id_barang');
    }
}

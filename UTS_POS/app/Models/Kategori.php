<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $fillable = ['kode_kategori', 'nama_kategori', 'deskripsi'];
    public $timestamps = false;

    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'id_kategori');
    }
}

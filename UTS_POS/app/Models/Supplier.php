<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';
    protected $fillable = [
        'kode_supplier',
        'nama_supplier',  
        'alamat',
        'created_at',
        'updated_at',
    ];

    public function supplies(): HasMany
    {
        return $this->hasMany(Barang::class, 'id_supplier', 'id_supplier');
    }
}

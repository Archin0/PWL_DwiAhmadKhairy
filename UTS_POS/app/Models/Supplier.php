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
        'nama_supplier', 
        'no_telp', 
        'alamat',
        'created_at',
        'updated_at',
    ];

    public function supplies(): HasMany
    {
        return $this->hasMany(Supply::class, 'id_supplier');
    }
}

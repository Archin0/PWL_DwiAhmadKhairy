<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasFactory;

    protected $table = 'level';
    protected $primaryKey = 'id_level';

    protected $fillable = [
        'kode_level',
        'nama_level'
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'id_level', 'id_level');
    }
}

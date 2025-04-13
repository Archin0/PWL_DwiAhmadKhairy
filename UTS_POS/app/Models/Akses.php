<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Akses extends Model
{
    use HasFactory;

    protected $table = 'akses';
    protected $primaryKey = 'id_akses';

    protected $fillable = [
        'id_user',
        'kelola_akun',
        'kelola_barang',
        'transaksi',
        'laporan',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}

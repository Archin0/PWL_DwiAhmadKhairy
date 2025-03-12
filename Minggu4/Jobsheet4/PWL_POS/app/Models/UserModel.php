<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user'; //mendefinisikan nama tabel yang digunakan model
    protected $primaryKey = 'user_id'; //mendefinisikan primary key tabel yang digunakan model


    protected $fillable = ['level_id', 'username', 'nama', 'password']; //Berhasil
    // protected $fillable = ['level_id', 'username', 'nama']; //terjadi error

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
}

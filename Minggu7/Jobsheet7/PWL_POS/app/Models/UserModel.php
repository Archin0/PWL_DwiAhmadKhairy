<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user'; //mendefinisikan nama tabel yang digunakan model
    protected $primaryKey = 'user_id'; //mendefinisikan primary key tabel yang digunakan model


    protected $fillable = ['level_id', 'username', 'nama', 'password', 'created_at', 'updated_at']; //Berhasil
    // protected $fillable = ['level_id', 'username', 'nama']; //terjadi error

    protected $hidden = ['password']; //menghilangkan kolom password ketika melakukan insert atau update 
    protected $casts = ['password' => 'hashed']; //casting pw agar otomatis dihash

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    //Mendapatkan nama role
    public function getNamaRole(): string
    {
        return $this->level->level_nama;
    }

    //Cek apakah user memiliki role tertentu
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }
}

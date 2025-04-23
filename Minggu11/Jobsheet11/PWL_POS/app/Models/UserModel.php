<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $table = 'm_user'; //mendefinisikan nama tabel yang digunakan model
    protected $primaryKey = 'user_id'; //mendefinisikan primary key tabel yang digunakan model


    protected $fillable = [
        'level_id',
        'username',
        'nama',
        'password',
        'profile_photo',
        'image', //tambahan
        'created_at',
        'updated_at'
    ];

    // protected $fillable = ['level_id', 'username', 'nama']; //terjadi error

    protected $hidden = ['password']; //menghilangkan kolom password ketika melakukan insert atau update 
    protected $casts = ['password' => 'hashed']; //casting pw agar otomatis dihash

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/posts/' . $image),
        );
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

    //Mendapatkan kode role
    public function getRole()
    {
        return $this->level->level_kode;
    }
}

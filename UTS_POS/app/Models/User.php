<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_level',
        'nama',
        'username',
        'password',
        'foto_profil',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function supplies(): HasMany
    {
        return $this->hasMany(Supply::class, 'id_user', 'id_user');
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'id_user, id_user');
    }

    public function akses(): HasOne
    {
        return $this->hasOne(Akses::class, 'id_user, id_user');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'id_level', 'id_level');
    }

    //Mendapatkan nama role
    public function getNamaRole(): string
    {
        return $this->level->nama_level;
    }

    //Cek apakah user memiliki role tertentu
    public function hasRole($role): bool
    {
        return $this->level->kode_level == $role;
    }

    //Mendapatkan kode role
    public function getRole()
    {
        return $this->level->kode_level;
    }
}

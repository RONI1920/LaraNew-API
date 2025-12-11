<?php

namespace App\Models;

// 1. Import ini WAJIB ADA
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Post;

class User extends Authenticatable
{
    // 2. Pasang di sini. Urutannya bebas, tapi 'HasApiTokens' HARUS ADA.
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke Post (yang tadi kita buat)
    public function posts()
    {
        // Relasi: Satu User (penulis) punya banyak Postingan
        return $this->hasMany(Post::class, 'user_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'news_content',
        'image'
    ];

    // Relasi ke User
    public function user()
    {
        // Relasi: Satu Postingan dimiliki oleh satu User
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function posts()
    {
        // Relasi: Satu User (penulis) punya banyak Postingan
        return $this->hasMany(Post::class, 'user_id', 'id');
    }
}

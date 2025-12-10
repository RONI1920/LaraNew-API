<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Mengubah data database menjadi format JSON yang kita mau.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->title,              // Ubah key 'title' jadi 'judul'
            'konten' => $this->news_content,      // Ubah key 'news_content' jadi 'konten'
            'gambar' => $this->image,
            'tanggal_tayang' => $this->created_at->format('d-m-Y H:i'), // Format tanggal cantik

            // MAGIC RELASI:
            // Mengambil nama user dari tabel users lewat relasi yang ada di Model Post
            'penulis' => $this->user->name,
        ];
    }
}

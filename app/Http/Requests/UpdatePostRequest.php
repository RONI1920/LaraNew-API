<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Post; // <--- WAJIB ADA! Kalau hilang = Error 500

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $postId = $this->route('post'); // Ambil ID dari URL
        $post = Post::find($postId);

        // Pastikan Post ada & User adalah pemiliknya
        if (!$post || $this->user()->id !== $post->user_id) {
            return false;
        }
        return true;
    }

    public function rules(): array
    {
        return [
            // Wajib diisi agar data berubah
            'title' => 'required|string|max:255',
            'news_content' => 'required|string',

            // Boleh kosong (karena kita skip gambar dulu)
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}

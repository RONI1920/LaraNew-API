<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Post; // Import Model Post

use Illuminate\Support\Facades\Log;

class UpdatePostRequest extends FormRequest
{
    /**
     * Tentukan apakah user boleh memakai request ini (Cek Kepemilikan).
     */

    // app/Http/Requests/UpdatePostRequest.php

    public function authorize(): bool
    {
        // 1. Ambil ID Post dari rute (pastikan routes/api.php menggunakan {post})
        $postId = $this->route('post');

        // 2. Cari Postingan secara manual. Gunakan findOrFail jika Anda ingin 404 otomatis di sini.
        $post = \App\Models\Post::find($postId);
        $currentUser = $this->user();

        // Cek keamanan: Jika Post tidak ditemukan atau Token tidak valid
        if (!$post || !$currentUser) {
            return false;
        }

        // 3. Logika Otorisasi Paling Aman: Bandingkan ID pemilik di DB dengan ID user Token.
        // Relasi model sudah dicek dan benar, kita tinggal bandingkan ID.
        return $currentUser->id === $post->user_id;
    }
    /**
     * Aturan validasi (Syarat data boleh masuk).
     */
    public function rules(): array
    {
        // Karena kita tidak memakai file upload (sementara ini), kita pakai 'string'
        return [
            // 'sometimes' berarti: kalau user kirim 'title', harus string & max 255. 
            // Tapi kalau user tidak kirim 'title', tidak masalah (optional).
            'title' => 'sometimes|required|string|max:255',
            'news_content' => 'sometimes|required|string',
            'image' => 'nullable|string', // Hanya boleh string (link/teks)
        ];
    }
}

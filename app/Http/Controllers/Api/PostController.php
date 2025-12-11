<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
// Import semua Request yang sudah kita buat/sepakati
use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    // 1. LIHAT SEMUA BERITA (Public)
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return PostResource::collection($posts);
    }

    // 2. LIHAT DETAIL SATU BERITA (Public)
    // Menggunakan findOrFail untuk response 404 otomatis
    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return new PostResource($post);
    }

    // 3. POSTING BERITA BARU (Wajib Login)
    public function store(PostRequest $request)
    {
        // 1. Ambil data yang sudah lolos validasi dari PostRequest
        $data = $request->validated();

        // 2. Tambahkan user_id (penulis) dari user yang sedang login
        $data['user_id'] = $request->user()->id;

        // 3. Simpan data ke database (Termasuk kolom 'image' yang berisi string)
        $post = Post::create($data);

        // Menggunakan user()->posts()->create($data) juga bisa, asalkan 
        // kolom 'user_id' tidak ada di array $data, atau diset di fillable.
        // Post::create($data) lebih aman dan eksplisit.

        return response()->json([
            'message' => 'Berita berhasil diposting',
            'data' => new PostResource($post),
        ], 201);
    }

    // 4. UPDATE BERITA (Wajib Login & Pemilik Asli)
    // Mengganti Request $request menjadi UpdatePostRequest $request
    public function update(UpdatePostRequest $request, $id)
    {
        // findOrFail() akan memberikan 404 jika ID tidak ada
        $post = Post::findOrFail($id);

        // Cek kepemilikan sudah diurus di UpdatePostRequest::authorize().
        // Jika user bukan pemilik, Request akan otomatis melempar 403 Forbidden.

        // Ambil data yang sudah divalidasi dari UpdatePostRequest
        $data = $request->validated();

        // Lakukan update
        $post->update($data);

        return response()->json([
            'message' => 'Berita berhasil diupdate',
            'data' => new PostResource($post),
        ], 200);
    }

    // 5. HAPUS BERITA (Wajib Login & Pemilik Asli)
    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id); // findOrFail untuk 404

        // Cek kepemilikan
        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Dilarang menghapus berita orang lain!'], 403);
        }

        $post->delete();

        return response()->json([
            'message' => 'Berita berhasil dihapus'
        ], 200);
    }
}

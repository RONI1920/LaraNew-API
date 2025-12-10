<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;               // Import Model
use App\Http\Resources\PostResource; // Import Resource (Format Tampilan)
use App\Http\Requests\PostRequest;   // Import Request (Validasi)

class PostController extends Controller
{
    // 1. LIHAT SEMUA BERITA (Public)
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return PostResource::collection($posts);
    }

    // 2. LIHAT DETAIL SATU BERITA (Public)
    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return new PostResource($post);
    }

    // 3. POSTING BERITA BARU (Wajib Login)
    public function store(PostRequest $request)
    {
        // Validasi otomatis jalan di PostRequest

        $post = $request->user()->posts()->create([
            'title' => $request->title,
            'news_content' => $request->news_content,
            'image' => $request->image,
        ]);

        return response()->json([
            'message' => 'Berita berhasil diposting',
            'data' => new PostResource($post),
        ], 201);
    }

    // 4. UPDATE BERITA (Wajib Login & Pemilik Asli)
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Berita tidak ditemukan'], 404);
        }

        // Cek apakah yang edit adalah pemiliknya?
        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Anda bukan pemilik berita ini!'], 403);
        }

        // Validasi input edit
        $request->validate([
            'title' => 'required|string|max:255',
            'news_content' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $post->update([
            'title' => $request->title,
            'news_content' => $request->news_content,
            'image' => $request->image,
        ]);

        return response()->json([
            'message' => 'Berita berhasil diupdate',
            'data' => new PostResource($post),
        ], 200);
    }

    // 5. HAPUS BERITA (Wajib Login & Pemilik Asli)
    public function destroy(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Berita tidak ditemukan'], 404);
        }

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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);
        return new PostResource($post);
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
            $data['image'] = $imagePath;
        }

        $post = Post::create($data);

        return response()->json([
            'message' => 'Berita berhasil diposting',
            'data' => new PostResource($post),
        ], 201);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        // 1. Cari Data
        $postRecord = Post::findOrFail($id);
        // 2. Ambil Data Validasi (title & news_content)
        $data = $request->validated();

        // 3. Logika Gambar (Akan di-skip otomatis jika tidak ada file)
        if ($request->hasFile('image')) {
            if ($postRecord->image && Storage::disk('public')->exists($postRecord->image)) {
                Storage::disk('public')->delete($postRecord->image);
            }
            $imagePath = $request->file('image')->store('posts', 'public');
            $data['image'] = $imagePath;
        }

        // 4. Update Database
        $postRecord->update($data);

        // 5. Refresh agar response update
        $postRecord->refresh();

        return response()->json([
            'message' => 'Berita berhasil diupdate',
            'data' => new PostResource($postRecord),
        ], 200);
    }


    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Dilarang!'], 403);
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return response()->json(['message' => 'Berita berhasil dihapus'], 200);
    }
}

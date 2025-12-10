<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Tentukan apakah user boleh memakai request ini.
     * Ubah jadi TRUE agar tidak error 403 Forbidden.
     */
    public function authorize(): bool
    {
        return true; // <--- WAJIB UBAH JADI TRUE
    }

    /**
     * Aturan validasi (Syarat data boleh masuk).
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            // Pastikan ejaan 'news_content' SAMA PERSIS dengan database & model
            'news_content' => 'required|string',
            'image' => 'nullable|string',
        ];
    }
}

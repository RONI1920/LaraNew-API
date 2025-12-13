<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'news_content' => 'required|string',
            'image' => 'nullable|file|image|mimes:jpg,jpeg,png|max:1500',
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthController extends Controller
{
    // fitur pendaftaran
    public function register(Request $request)
    {
        // kita validasi data input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:125',
            'email' => 'required|string|email|max:225|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // jika bisa di lewati yang atas maka next nya

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // jika sudah daftar dan maka kita kasih Token masuk 
        $token = $user->createToken('auth_token')->plainTextToken;

        // setelah token di buat maka kita kembalikan respon
        return response()->json([
            'message' => 'Registrasi Berhasil',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }
}

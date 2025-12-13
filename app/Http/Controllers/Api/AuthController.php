<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // fitur pendaftaran
    public function register(RegisterRequest $request)
    {

        // validate di handle oleh RegisterRquest

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

    public function login(LoginRequest $request)
    {
        // validate di handle oleh LoginRequest

        //user masuk kita cek email nya
        $user = User::where('email', $request->email)->first();

        // kita amankan pssword dan email nya jika terjadi kesalahan
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau Password Salah'
            ], 401);
        }


        // jika pengecekan Email dan Password Berhasil maka User kita Kasih Token untuk masuk
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Login Sukses',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }


    // jika user keluar maka kita bakar token masuk.
    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Log Out Berhasil'
        ], 200);
    }
}

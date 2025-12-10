<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Ini kolom yang tadi error (missing)
            $table->text('news_content'); // Pakai text agar muat banyak karakter
            $table->string('image')->nullable(); // Nullable biar tidak error kalau tidak ada gambar
            $table->unsignedBigInteger('user_id'); // Untuk relasi ke tabel users
            $table->timestamps();

            // Opsional: Menambahkan foreign key agar data konsisten
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};

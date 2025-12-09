<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Post extends Model
{
    public function up(): void
    {
        Schema::create(
            'posts',
            function (Blueprint $table) {
                $table->id();
                // mengabung kan user id dengan
                $table->foreign('user_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->text('new_content');
                $table->string('image')->nullable();

                $table->timestamps();
            }
        );
    }
}

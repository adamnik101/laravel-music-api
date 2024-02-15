<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('album_user_likes', function (Blueprint $table) {
            $table->primary(['album_id', 'user_id']);

            $table->foreignUuid('album_id')->references('id')->on('albums');
            $table->foreignUuid('user_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album_user_likes');
    }
};

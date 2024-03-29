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
        Schema::create('playlists', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('title');
            $table->string('description')->nullable();
            $table->string('image_url')->nullable();
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->foreignUuid('genre_id')->nullable()->references('id')->on('genres');

            $table->softDeletesDatetime();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};

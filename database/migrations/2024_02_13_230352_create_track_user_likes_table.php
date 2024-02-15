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
        Schema::create('track_user_likes', function (Blueprint $table) {
            $table->primary(['user_id', 'track_id']);

            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->foreignUuid('track_id')->references('id')->on('tracks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_user_likes');
    }
};

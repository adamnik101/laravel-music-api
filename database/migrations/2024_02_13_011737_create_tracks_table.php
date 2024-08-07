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
        Schema::create('tracks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("title");
            $table->string('path');
            $table->unsignedBigInteger('duration');
            $table->boolean('explicit');
            $table->string('cover');
            $table->foreignUuid('album_id')->nullable()->references('id')->on('albums');
            $table->foreignUuid('owner_id')->references('id')->on('artists');
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
        Schema::dropIfExists('tracks');
    }
};

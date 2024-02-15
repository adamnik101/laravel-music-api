<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Album extends Model
{
    use HasFactory, HasUuids;

    public function likedBy() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'album_user_likes');
    }
}

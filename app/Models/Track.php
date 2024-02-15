<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Track extends Model
{
    use HasFactory, HasUuids;

    public function playlists() : BelongsToMany {
        return $this->belongsToMany(Playlist::class);
    }
    public function features () : BelongsToMany {
        return $this->belongsToMany(Artist::class, 'features');
    }
    public function likedBy() : BelongsToMany {
        return $this->belongsToMany(User::class, 'track_user_likes');
    }
}

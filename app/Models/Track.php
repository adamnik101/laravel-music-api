<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Track extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public function owner() : BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }
    public function playlists() : BelongsToMany {
        return $this->belongsToMany(Playlist::class);
    }
    public function features () : BelongsToMany {
        return $this->belongsToMany(Artist::class, 'features');
    }
    public function likedBy() : BelongsToMany {
        return $this->belongsToMany(User::class, 'track_user_likes');
    }
    public function album() : BelongsTo {
        return $this->belongsTo(Album::class);
    }
}

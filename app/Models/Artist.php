<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $casts = [
        'verified' => 'boolean',
        'tracks_count' => 'int',
        'albums_count' => 'int'
    ];

    protected $withCount = [
        'tracks',
        'followedBy'
    ];
    public function features () : BelongsToMany {
        return $this->belongsToMany(Track::class, 'features');
    }
    public function followedBy() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'artist_user_followings');
    }
    public function tracks() : HasMany
    {
        return $this->hasMany(Track::class, 'owner_id');
    }
    public function albums() : HasMany
    {
        return $this->hasMany(Album::class);
    }

    public function totalLikes () {
        return $this->hasManyThrough(Track::class, 'track_user_likes');
    }
}

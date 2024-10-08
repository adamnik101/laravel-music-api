<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $withCount = [
        'tracks'
    ];

    protected $casts = [
        'tracks_count' => 'int'
    ];
    public function artist() : BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }
    public function tracks() : HasMany
    {
        return $this->hasMany(Track::class);
    }
    public function likedBy() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'album_user_likes');
    }
}

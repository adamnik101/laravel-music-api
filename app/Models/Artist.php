<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Artist extends Model
{
    use HasFactory, HasUuids;

    public function features () : BelongsToMany {
        return $this->belongsToMany(Track::class, 'features');
    }
    public function followedBy() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'artist_user_followings');
    }
}

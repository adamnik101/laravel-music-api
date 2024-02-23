<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Playlist extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['title', 'description', 'updated_at'];

    public function tracks() : BelongsToMany {
        return $this->belongsToMany(Track::class, 'playlist_track')->withPivot(['id','created_at'])->orderByPivot('created_at');
    }
    public function ownedBy() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function genre() : BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }
}

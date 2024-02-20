<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackPlay extends Model
{
    use HasFactory;

    protected $table = 'track_plays';

    protected $fillable = [
        'user_id',
        'track_id'
    ];
    public function track() : BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

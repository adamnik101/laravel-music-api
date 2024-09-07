<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function role() : BelongsTo {
        return $this->belongsTo(Role::class);
    }
    public function settings() : HasOne {
        return $this->hasOne(Setting::class)->withCasts(['explicit' => 'bool']);
    }
    public function playlists() : HasMany {
        return $this->hasMany(Playlist::class)->withCount('tracks');
    }
    public function likedTracks() : BelongsToMany {
        return $this->belongsToMany(Track::class, 'track_user_likes')->withPivot('created_at')->orderByPivot('created_at', 'desc');
    }
    public function likedAlbums() : BelongsToMany {
        return $this->belongsToMany(Album::class, 'album_user_likes')->withCount('tracks');
    }
    public function followings() : BelongsToMany {
        return $this->belongsToMany(Artist::class, 'artist_user_followings');
    }
    public function trackPlays() : HasMany
    {
        return $this->hasMany(TrackPlay::class);
    }
}

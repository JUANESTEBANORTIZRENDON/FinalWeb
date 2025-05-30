<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Artwork;
use App\Models\ArtworkLike;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\Notification as NotificationModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'bio',
        'website_url',
        'avatar_path',
        'social_media'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'reset_password_token',
        'reset_password_token_expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'social_media' => 'array',
        'reset_password_token_expires_at' => 'datetime'
    ];
    
    /**
     * Check if user is an artist
     *
     * @return bool
     */
    public function isArtist()
    {
        return $this->user_type === 'artist';
    }
    
    /**
     * Check if user is a visitor
     *
     * @return bool
     */
    public function isVisitor()
    {
        return $this->user_type === 'visitor';
    }
    
    /**
     * Get the artworks that belong to the artist.
     */
    public function artworks()
    {
        return $this->hasMany(Artwork::class, 'artist_id');
    }
    
    /**
     * Get the artwork likes that belong to the user.
     */
    public function likes()
    {
        return $this->hasMany(ArtworkLike::class);
    }
    
    /**
     * Get the comments created by the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    /**
     * Get the followers of this artist.
     */
    public function followers()
    {
        return $this->hasMany(Follower::class, 'artist_id');
    }
    
    /**
     * Get the artists this user is following.
     */
    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }
    
    /**
     * Get the artists this user follows (relación belongsToMany a través de follower)
     */
    public function followedArtists()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'artist_id');
    }
    
    /**
     * Get the notifications that belong to the user.
     */
    public function notifications()
    {
        return $this->hasMany(NotificationModel::class);
    }
}

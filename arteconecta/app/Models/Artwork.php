<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artwork extends Model
{
    use HasFactory;

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
        'artist_id',
        'title',
        'description',
        'category_id',
        'image_path',
        'creation_date',
        'technique',
        'dimensions',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'creation_date' => 'date',
        'is_public' => 'boolean',
    ];

    /**
     * Get the artist that owns the artwork.
     */
    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    /**
     * Get the category of the artwork.
     */
    public function category()
    {
        return $this->belongsTo(ArtCategory::class, 'category_id');
    }

    /**
     * Get the likes for the artwork.
     */
    public function likes()
    {
        return $this->hasMany(ArtworkLike::class);
    }

    /**
     * Get the comments for the artwork.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

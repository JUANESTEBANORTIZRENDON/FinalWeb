<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
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
        'artwork_id',
        'user_id',
        'content',
    ];

    /**
     * Get the artwork that owns the comment.
     */
    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }

    /**
     * Get the user who posted the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

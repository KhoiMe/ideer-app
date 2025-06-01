<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $fillable = [
        'vote_type',
        'description',
        'color',
        'icon',
    ];

    protected $casts = [
        'vote_type'  => 'enum',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the idea that was voted on.
     */
    public function idea(): BelongsTo
    {
        return $this->belongsTo(Idea::class);
    }

    /**
     * Scope to get only upvotes.
     */
    public function scopeUpvotes($query)
    {
        return $query->where('vote_type', 'upvote');
    }

    /**
     * Scope to get only downvotes.
     */
    public function scopeDownvotes($query)
    {
        return $query->where('vote_type', 'downvote');
    }

    /**
     * Scope to get votes for a specific idea.
     */
    public function scopeForIdea($query, $ideaId)
    {
        return $query->where('idea_id', $ideaId);
    }

    /**
     * Scope to get votes by a specific user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if this is an upvote.
     */
    public function isUpvote(): bool
    {
        return $this->vote_type === 'upvote';
    }

    /**
     * Check if this is a downvote.
     */
    public function isDownvote(): bool
    {
        return $this->vote_type === 'downvote';
    }
}

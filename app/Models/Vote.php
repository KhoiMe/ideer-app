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

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Update idea vote counts when vote is created
        static::created(function ($vote) {
            $vote->updateIdeaVoteCounts();
        });

        // Update idea vote counts when vote is updated
        static::updated(function ($vote) {
            $vote->updateIdeaVoteCounts();
        });

        // Update idea vote counts when vote is deleted
        static::deleted(function ($vote) {
            $vote->updateIdeaVoteCounts();
        });
    }

    /**
     * Update the vote counts on the associated idea.
     */
    public function updateIdeaVoteCounts(): void
    {
        $idea = $this->idea;

        if ($idea) {
            $upvotes = $idea->votes()->upvotes()->count();
            $downvotes = $idea->votes()->downvotes()->count();

            $idea->update([
                'idea_upvote_count' => $upvotes,
                'idea_downvote_count' => $downvotes,
                'totalvote_count' => $upvotes - $downvotes
            ]);
        }
    }
}

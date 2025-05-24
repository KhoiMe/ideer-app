<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Idea extends Model
{
    protected $fillable = [
        'idea_title',
        'idea_explanation',
        'idea_upvote_count',
        'idea_downvote_count',
        'totalvote_count',
        'user_id'
    ];

    protected $casts = [
        'idea_upvote_count' => 'integer',
        'idea_downvote_count' => 'integer',
        'totalvote_count' => 'integer',
    ];

    // Many-to-many relationship with categories
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'idea_category');
    }

    // Relationship with user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with votes (if you have a votes table)
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    // Helper method to get user's vote on this idea
    public function userVote($userId)
    {
        return $this->votes()->where('user_id', $userId)->first();
    }

    // Helper method to update vote counts
    public function updateVoteCounts()
    {
        $upvotes = $this->votes()->where('vote_type', 'upvote')->count();
        $downvotes = $this->votes()->where('vote_type', 'downvote')->count();

        $this->update([
            'idea_upvote_count' => $upvotes,
            'idea_downvote_count' => $downvotes,
            'totalvote_count' => $upvotes - $downvotes
        ]);
    }
}

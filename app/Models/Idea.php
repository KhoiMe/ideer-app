<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Idea extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getSortedIdeas()
    {
        return self::query()
            ->leftJoin('idea_votes', 'ideas.id', '=', 'idea_votes.idea_id')
            ->selectRaw('ideas.*, coalesce(sum(idea_votes.count), 0) as votes')
            ->orderBy('votes', 'desc')
            ->groupBy('ideas.id')
            ->limit(1000)
            ->with('user')
            ->get();
    }

    public function voteCount(): int
    {
        return self::query()
            ->leftJoin('idea_votes', 'ideas.id', '=', 'idea_votes.idea_id')
            ->selectRaw('ideas.*, coalesce(sum(idea_votes.count), 0) as votes')
            ->where('ideas.id', $this->id)
            ->groupBy('ideas.id')
            ->first()->votes;
    }


    // Relationship with user

}

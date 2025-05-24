<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
        'icon',
    ];

    public function ideas(): BelongsToMany
    {
        return $this->belongsToMany(Idea::class, 'idea_category');
    }
}

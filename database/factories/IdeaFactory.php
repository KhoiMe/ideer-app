<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

// Models
use App\Models\Idea;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Idea>
 */
class IdeaFactory extends Factory
{
    protected $model = \App\Models\Idea::class;

    public function definition(): array
    {
        return [
            'idea_title' => $this->faker->sentence,
            'idea_explanation' => $this->faker->paragraph,
            'user_id' => User::factory(),
        ];

    }
}

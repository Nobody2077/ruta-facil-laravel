<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Opinion;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'commentable_type' => Project::class,
            'commentable_id' => Project::factory(),
            'body' => $this->faker->paragraph(2),
            'status' => $this->faker->randomElement(['visible', 'oculto']),
        ];
    }

    public function forOpinion(): static
    {
        return $this->state(fn (): array => [
            'commentable_type' => Opinion::class,
            'commentable_id' => Opinion::factory(),
        ]);
    }

    public function forProject(): static
    {
        return $this->state(fn (): array => [
            'commentable_type' => Project::class,
            'commentable_id' => Project::factory(),
        ]);
    }
}

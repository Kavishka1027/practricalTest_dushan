<?php

namespace App\Post\IO\Database\Factories;

use App\Post\Entities\Models\Post;
use App\Website\Entities\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'website_id' => Website::factory()->create(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }
}

<?php

namespace App\Website\IO\Database\Factories;

use App\Website\Entities\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebsiteFactory extends Factory
{
    protected $model = Website::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'url' => fake()->unique()->url(),
        ];
    }
}

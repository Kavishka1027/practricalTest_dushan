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
            'name' => $this->faker->company,
            'url' => $this->faker->url,
            'email' => $this->faker->unique()->companyEmail,
        ];
    }
}

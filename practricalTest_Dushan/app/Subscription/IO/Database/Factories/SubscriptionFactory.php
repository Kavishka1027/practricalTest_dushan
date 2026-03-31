<?php

namespace App\Subscription\IO\Database\Factories;

use App\Subscription\Entities\Models\Subscription;
use App\Website\Entities\Models\Website;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        return [
            'website_id' => Website::factory(),
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}

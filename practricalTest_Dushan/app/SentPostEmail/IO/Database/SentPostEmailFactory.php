<?php

namespace App\SentPostEmail\IO\Database;

use App\Post\Entities\Models\Post;
use App\SentPostEmail\Entities\Models\SentPostEmail;
use App\Subscription\Entities\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SentPostEmailFactory extends Factory
{
    protected $model = SentPostEmail::class;

    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'subscription_id' => Subscription::factory(),
            'sent_at' => now(),
        ];
    }
}

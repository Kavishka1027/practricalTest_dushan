<?php

namespace App\Jobs;

use App\Post\Entities\Models\Post;
use App\Services\PostEmailDeliveryService;
use App\Subscription\Entities\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendPostEmailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $postId,
        public int $subscriptionId
    ) {
    }

    public function handle(PostEmailDeliveryService $service): void
    {
        $post = Post::with('website')->findOrFail($this->postId);
        $subscription = Subscription::findOrFail($this->subscriptionId);

        $service->send($post, $subscription);
    }
}

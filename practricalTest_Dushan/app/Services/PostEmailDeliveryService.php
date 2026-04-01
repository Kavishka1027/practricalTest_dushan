<?php


namespace App\Services;

use App\Mail\PostPublishedMail;

use App\Post\Entities\Models\Post;
use App\SentPostEmail\Entities\Models\SentPostEmail;
use App\Subscription\Entities\Models\Subscription;
use Illuminate\Support\Facades\Mail;

class PostEmailDeliveryService
{
    public function send(Post $post, Subscription $subscription): bool
    {
        $delivery = SentPostEmail::firstOrCreate(
            [
                'post_id' => $post->id,
                'subscription_id' => $subscription->id,
            ],
            [
                'sent_at' => now(),
            ]
        );

        if (!$delivery->wasRecentlyCreated) {
            return false;
        }

        Mail::to($subscription->email)->send(new PostPublishedMail($post, $subscription));

        return true;
    }
}

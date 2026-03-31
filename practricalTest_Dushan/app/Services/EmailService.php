<?php

namespace App\Services;

use App\Contracts\EmailServiceInterface;
use App\Post\Entities\Models\Post;
use App\SentEmail\Entities\Models\SentEmail;
use App\Mail\NewPostNotification;
use Illuminate\Support\Facades\Mail;

class EmailService implements EmailServiceInterface
{
    public function sendPostToSubscribers(Post $post): void
    {
        $post->loadMissing('website', 'website.subscriptions');

        foreach ($post->website->subscriptions as $subscriber) {
            if ($this->shouldSendEmail($post, $subscriber->email)) {
                Mail::to($subscriber->email)
                    ->queue(new NewPostNotification($post));
            }
        }
    }

    public function shouldSendEmail(Post $post, string $email): bool
    {
        return $this->markEmailAsSent($post, $email)->wasRecentlyCreated;
    }

    public function markEmailAsSent(Post $post, string $email): SentEmail
    {
        return SentEmail::firstOrCreate([
            'post_id' => $post->id,
            'email' => $email,
        ]);
    }
}

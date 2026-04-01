<?php

namespace App\Listeners;

use App\Events\PostPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Artisan;

class QueuePostEmailSending implements ShouldQueue
{
    public function handle(PostPublished $event): void
    {
        Artisan::queue('posts:send-emails', [
            'postId' => $event->post->id,
        ]);
    }
}

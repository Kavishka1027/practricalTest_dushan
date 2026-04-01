<?php

use App\Events\PostPublished;
use App\Listeners\QueuePostEmailSending;
use App\Post\Entities\Models\Post;
use Illuminate\Support\Facades\Artisan;

test('queues the send post emails command when post is published', function () {

    Artisan::spy();
    $post = Post::factory()->create();
    $event = new PostPublished($post);
    $listener = new QueuePostEmailSending();

    $listener->handle($event);

    Artisan::shouldHaveReceived('queue')
        ->once()
        ->with('posts:send-emails', [
            'postId' => $post->id,
        ]);
});

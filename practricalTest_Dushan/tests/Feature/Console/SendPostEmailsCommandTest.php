<?php

use App\Jobs\SendPostEmailJob;
use App\Post\Entities\Models\Post;
use App\Subscription\Entities\Models\Subscription;
use App\Website\Entities\Models\Website;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Queue::fake();

    $this->website = Website::factory()->create();
    $this->post = Post::factory()->create([
        'website_id' => $this->website->id,
    ]);

    Subscription::factory()->count(3)->create([
        'website_id' => $this->website->id,
    ]);
});

test('queues email jobs for all subscribers of the post website', function () {

    $postId = $this->post->id;

    $this->artisan('posts:send-emails', ['postId' => $postId])
        ->assertSuccessful();

    Queue::assertPushed(SendPostEmailJob::class, 3);
});

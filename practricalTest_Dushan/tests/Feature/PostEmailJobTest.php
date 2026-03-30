<?php

namespace Tests\Feature;

use App\Website\Entities\Models\Website;
use App\Subscription\Entities\Models\Subscription;
use App\Post\Entities\Models\Post;
use App\Jobs\SendPostEmailJob;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use App\Mail\NewPostNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
    Queue::fake();
});

test('send post email job sends email to subscriber', function () {
    $website = Website::factory()->create();
    $subscriber = Subscription::factory()->create([
        'website_id' => $website->id,
        'email' => 'subscriber@example.com',
    ]);

    $postData = [
        'website_id' => $website->id,
        'title' => 'Test Post',
        'description' => 'Test Description',
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(201);

    Queue::assertPushed(SendPostEmailJob::class, function ($job) use ($postData, $subscriber) {
        return $job->post->title === $postData['title'] &&
            $job->subscriber->email === $subscriber->email;
    });

    $post = Post::where('title', 'Test Post')->first();
    $job = new SendPostEmailJob($post, $subscriber);
    $job->handle();

    Mail::assertSent(NewPostNotification::class, function ($mail) use ($subscriber, $post) {
        return $mail->hasTo($subscriber->email) &&
            $mail->post->id === $post->id;
    });
});

test('send post email job handles failed email sending gracefully', function () {
    $website = Website::factory()->create();
    $subscriber = Subscription::factory()->create([
        'website_id' => $website->id,
        'email' => 'invalid-email@example.com',
    ]);

    $postData = [
        'website_id' => $website->id,
        'title' => 'Test Post',
        'description' => 'Test Description',
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(201);

    Mail::shouldReceive('send')
        ->once()
        ->andThrow(new \Exception('Email sending failed'));

    $post = Post::where('title', 'Test Post')->first();
    $job = new SendPostEmailJob($post, $subscriber);

    expect(fn() => $job->handle())
        ->toThrow(\Exception::class);
});

test('send post email job is queued on correct queue', function () {
    $website = Website::factory()->create();
    $subscriber = Subscription::factory()->create([
        'website_id' => $website->id,
        'email' => 'subscriber@example.com',
    ]);

    $postData = [
        'website_id' => $website->id,
        'title' => 'Test Post',
        'description' => 'Test Description',
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(201);

    Queue::assertPushed(SendPostEmailJob::class, function ($job) {
        return $job->queue === 'emails';
    });
});

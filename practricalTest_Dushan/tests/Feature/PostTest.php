<?php

namespace Tests\Feature;

use App\Website\Entities\Models\Website;
use App\Post\Entities\Models\Post;
use App\Subscription\Entities\Models\Subscription;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendPostEmailJob;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Queue::fake();
});

test('can create post for a particular website with valid data', function () {
    $website = Website::factory()->create();
    $postData = [
        'website_id' => $website->id,
        'title' => 'Test Post Title',
        'description' => 'This is a test post description.',
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'website_id',
                'title',
                'description',
                'created_at',
                'updated_at'
            ]
        ]);

    $this->assertDatabaseHas('posts', [
        'website_id' => $website->id,
        'title' => 'Test Post Title',
        'description' => 'This is a test post description.',
    ]);
});

test('cannot create post with missing website_id', function () {
    $postData = [
        'title' => 'Test Post Title',
        'description' => 'This is a test post description.',
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['website_id']);
});

test('cannot create post with non-existent website_id', function () {
    $postData = [
        'website_id' => 99999,
        'title' => 'Test Post Title',
        'description' => 'This is a test post description.',
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['website_id']);
});

test('cannot create post with missing title', function () {
    $website = Website::factory()->create();
    $postData = [
        'website_id' => $website->id,
        'description' => 'This is a test post description.',
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('cannot create post with missing description', function () {
    $website = Website::factory()->create();
    $postData = [
        'website_id' => $website->id,
        'title' => 'Test Post Title',
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});

test('creates post and dispatches email jobs to all subscribers via API', function () {
    $website = Website::factory()->create();
    $subscribers = Subscription::factory()->count(3)->create([
        'website_id' => $website->id
    ]);

    $postData = [
        'website_id' => $website->id,
        'title' => 'New Post Title',
        'description' => 'New post description.',
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(201);

    Queue::assertPushed(SendPostEmailJob::class, 3);

    foreach ($subscribers as $subscriber) {
        Queue::assertPushed(SendPostEmailJob::class, function ($job) use ($postData, $subscriber) {
            return $job->post->title === $postData['title'] &&
                $job->subscriber->email === $subscriber->email;
        });
    }
});

test('does not send duplicate emails for the same post to same subscriber via API', function () {
    $website = Website::factory()->create();
    Subscription::factory()->create([
        'website_id' => $website->id,
        'email' => 'test@example.com'
    ]);

    $postData = [
        'website_id' => $website->id,
        'title' => 'New Post Title',
        'description' => 'New post description.',
    ];

    $response = $this->postJson('/api/posts', $postData);
    $secondResponse = $this->postJson('/api/posts', $postData);

    $response->assertStatus(201);
    $secondResponse->assertStatus(201);

    Queue::assertPushed(SendPostEmailJob::class, 1);
});

test('creates post with no subscribers - no jobs dispatched via API', function () {
    $website = Website::factory()->create();
    $postData = [
        'website_id' => $website->id,
        'title' => 'New Post Title',
        'description' => 'New post description.',
    ];

    $response = $this->postJson('/api/posts', $postData);

    $response->assertStatus(201);
    Queue::assertNothingPushed();
});

test('can retrieve a specific post via API', function () {
    $website = Website::factory()->create();
    $post = Post::factory()->create([
        'website_id' => $website->id,
        'title' => 'Specific Post',
        'description' => 'Specific Description'
    ]);

    $response = $this->getJson("/api/posts/{$post->id}");

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $post->id,
                'website_id' => $website->id,
                'title' => 'Specific Post',
                'description' => 'Specific Description'
            ]
        ]);
});

test('can retrieve all posts for a website via API', function () {
    $website = Website::factory()->create();
    Post::factory()->count(3)->create([
        'website_id' => $website->id
    ]);

    $response = $this->getJson("/api/websites/{$website->id}/posts");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'website_id', 'title', 'description']
            ]
        ]);

    $this->assertCount(3, $response->json('data'));
});

test('returns 404 when retrieving non-existent post via API', function () {
    $response = $this->getJson('/api/posts/99999');

    $response->assertStatus(404);
});

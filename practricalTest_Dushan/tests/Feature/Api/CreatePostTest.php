<?php

use App\Events\PostPublished;
use App\Website\Entities\Models\Website;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();
    $this->website = Website::factory()->create();
});

test('creates a post for a particular website', function () {

    $payload = [
        'title' => 'New Feature Released',
        'description' => 'A new feature has been released today.',
    ];

    $response = $this->postJson("/api/websites/{$this->website->id}/posts", $payload);

    $response->assertCreated()
        ->assertJson([
            'message' => 'Post created successfully.',
        ]);

    $this->assertDatabaseHas('posts', [
        'website_id' => $this->website->id,
        'title' => 'New Feature Released',
    ]);

    Event::assertDispatched(PostPublished::class);
});

test('validates post creation request', function () {

    $payload = [];

    $response = $this->postJson("/api/websites/{$this->website->id}/posts", $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'description']);
});

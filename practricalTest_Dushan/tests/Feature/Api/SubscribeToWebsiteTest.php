<?php

use App\Subscription\Entities\Models\Subscription;
use App\Website\Entities\Models\Website;

beforeEach(function () {
    $this->website = Website::factory()->create();
});

test('creates a subscription for a website', function () {

    $payload = [
        'website_id' => $this->website->id,
        'email' => 'user@example.com',
    ];

    $response = $this->postJson('/api/subscriptions', $payload);

    $response->assertCreated()
        ->assertJson([
            'message' => 'Subscription created successfully.',
        ]);

    $this->assertDatabaseHas('subscriptions', [
        'website_id' => $this->website->id,
        'email' => 'user@example.com',
    ]);
});

test('validates required fields when creating a subscription', function () {

    $payload = [];

    $response = $this->postJson('/api/subscriptions', $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['website_id', 'email']);
});

test('does not allow duplicate subscription for same website and email', function () {

    Subscription::factory()->create([
        'website_id' => $this->website->id,
        'email' => 'user@example.com',
    ]);

    $payload = [
        'website_id' => $this->website->id,
        'email' => 'user@example.com',
    ];

    $response = $this->postJson('/api/subscriptions', $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});


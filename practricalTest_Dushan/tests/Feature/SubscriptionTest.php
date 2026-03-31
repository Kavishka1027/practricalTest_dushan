<?php

namespace Tests\Feature;

use App\Website\Entities\Models\Website;
use App\Subscription\Entities\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can subscribe to a particular website via API', function () {
    $website = Website::factory()->create();
    $subscriptionData = [
        'website_id' => $website->id,
        'email' => 'test@example.com',
    ];

    $response = $this->postJson('/api/subscriptions', $subscriptionData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'website_id',
                'email',
                'created_at',
                'updated_at'
            ]
        ]);

    $this->assertDatabaseHas('subscriptions', [
        'website_id' => $website->id,
        'email' => 'test@example.com',
    ]);
});

test('cannot subscribe with missing website_id via API', function () {
    $subscriptionData = [
        'email' => 'test@example.com',
    ];

    $response = $this->postJson('/api/subscriptions', $subscriptionData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['website_id']);
});

test('cannot subscribe with non-existent website_id via API', function () {
    $subscriptionData = [
        'website_id' => 99999,
        'email' => 'test@example.com',
    ];

    $response = $this->postJson('/api/subscriptions', $subscriptionData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['website_id']);
});

test('cannot subscribe with missing email via API', function () {
    $website = Website::factory()->create();
    $subscriptionData = [
        'website_id' => $website->id,
    ];

    $response = $this->postJson('/api/subscriptions', $subscriptionData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('cannot subscribe with invalid email format via API', function () {
    $website = Website::factory()->create();
    $subscriptionData = [
        'website_id' => $website->id,
        'email' => 'invalid-email',
    ];

    $response = $this->postJson('/api/subscriptions', $subscriptionData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('cannot subscribe with duplicate email for same website via API', function () {
    $website = Website::factory()->create();
    Subscription::factory()->create([
        'website_id' => $website->id,
        'email' => 'test@example.com',
    ]);

    $subscriptionData = [
        'website_id' => $website->id,
        'email' => 'test@example.com',
    ];

    $response = $this->postJson('/api/subscriptions', $subscriptionData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('can subscribe same email to different websites via API', function () {
    $website1 = Website::factory()->create();
    $website2 = Website::factory()->create();

    $subscriptionData1 = [
        'website_id' => $website1->id,
        'email' => 'test@example.com',
    ];

    $subscriptionData2 = [
        'website_id' => $website2->id,
        'email' => 'test@example.com',
    ];

    $response1 = $this->postJson('/api/subscriptions', $subscriptionData1);
    $response2 = $this->postJson('/api/subscriptions', $subscriptionData2);

    $response1->assertStatus(201);
    $response2->assertStatus(201);

    $this->assertDatabaseCount('subscriptions', 2);
    $this->assertDatabaseHas('subscriptions', [
        'website_id' => $website1->id,
        'email' => 'test@example.com',
    ]);
    $this->assertDatabaseHas('subscriptions', [
        'website_id' => $website2->id,
        'email' => 'test@example.com',
    ]);
});

test('can retrieve all subscriptions for a website via API', function () {
    $website = Website::factory()->create();
    Subscription::factory()->count(3)->create([
        'website_id' => $website->id
    ]);

    $response = $this->getJson("/api/websites/{$website->id}/subscriptions");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'website_id', 'email']
            ]
        ]);

    $this->assertCount(3, $response->json('data'));
});

test('can unsubscribe from a website via API', function () {
    $website = Website::factory()->create();
    $subscription = Subscription::factory()->create([
        'website_id' => $website->id,
        'email' => 'test@example.com'
    ]);

    $response = $this->deleteJson("/api/subscriptions/{$subscription->id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('subscriptions', [
        'id' => $subscription->id
    ]);
});

test('returns 404 when unsubscribing with non-existent subscription via API', function () {
    $response = $this->deleteJson('/api/subscriptions/99999');

    $response->assertStatus(404);
});

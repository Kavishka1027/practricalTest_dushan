<?php

namespace Tests\Feature;

use App\Website\Entities\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can list all websites via API', function () {
    Website::factory()->count(5)->create();

    $response = $this->getJson('/api/websites');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'url',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

    $this->assertCount(5, $response->json('data'));
});

test('can get single website via API', function () {
    $website = Website::factory()->create([
        'name' => 'Test Website',
        'url' => 'https://test.com'
    ]);

    $response = $this->getJson("/api/websites/{$website->id}");

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $website->id,
                'name' => 'Test Website',
                'url' => 'https://test.com',
            ]
        ]);
});

test('returns 404 for non-existent website via API', function () {
    $response = $this->getJson('/api/websites/99999');

    $response->assertStatus(404);
});

test('can get website with its posts via API', function () {
    $website = Website::factory()->create();
    $website->posts()->createMany([
        ['title' => 'Post 1', 'description' => 'Description 1'],
        ['title' => 'Post 2', 'description' => 'Description 2'],
        ['title' => 'Post 3', 'description' => 'Description 3'],
    ]);

    $response = $this->getJson("/api/websites/{$website->id}?include=posts");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'url',
                'posts' => [
                    '*' => ['id', 'title', 'description']
                ]
            ]
        ]);

    $this->assertCount(3, $response->json('data.posts'));
});

test('can get website with its subscribers via API', function () {
    $website = Website::factory()->create();
    $website->subscriptions()->createMany([
        ['email' => 'user1@example.com'],
        ['email' => 'user2@example.com'],
        ['email' => 'user3@example.com'],
    ]);

    $response = $this->getJson("/api/websites/{$website->id}?include=subscribers");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'url',
                'subscribers' => [
                    '*' => ['id', 'email']
                ]
            ]
        ]);

    $this->assertCount(3, $response->json('data.subscribers'));
});

test('can create new website via API', function () {
    $websiteData = [
        'name' => 'New Website',
        'url' => 'https://newwebsite.com'
    ];

    $response = $this->postJson('/api/websites', $websiteData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'url',
                'created_at',
                'updated_at'
            ]
        ]);

    $this->assertDatabaseHas('websites', [
        'name' => 'New Website',
        'url' => 'https://newwebsite.com'
    ]);
});

test('cannot create website with duplicate name via API', function () {
    Website::factory()->create([
        'name' => 'Existing Website'
    ]);

    $websiteData = [
        'name' => 'Existing Website',
        'url' => 'https://newwebsite.com'
    ];

    $response = $this->postJson('/api/websites', $websiteData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

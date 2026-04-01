<?php

use App\Website\Entities\Models\Website;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

test('lists all websites', function () {

    Website::factory()->count(3)->create();

    $response = $this->getJson('/api/websites');

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

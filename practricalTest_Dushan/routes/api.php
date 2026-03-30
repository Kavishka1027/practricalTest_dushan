<?php

use App\Post\IO\Http\Controllers\PostController;
use App\Subscription\IO\Http\Controllers\SubscriptionController;
use App\Website\IO\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/websites', [WebsiteController::class, 'index']);
    Route::get('/websites/{website}', [WebsiteController::class, 'show']);

    Route::post('/websites/{website}/subscribe', [SubscriptionController::class, 'subscribe']);

    Route::post('/websites/{website}/posts', [PostController::class, 'store']);
});


<?php

use App\Post\IO\Http\Controllers\PostController;
use App\Subscription\IO\Http\Controllers\SubscriptionController;
use App\Website\IO\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/websites', [WebsiteController::class, 'index']);
Route::post('/subscriptions', [SubscriptionController::class, 'store']);
Route::post('/websites/{website}/posts', [PostController::class, 'store']);

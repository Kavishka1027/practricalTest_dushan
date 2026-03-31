<?php

use Illuminate\Support\Facades\Route;
use App\Post\IO\Http\Controllers\PostController;
use App\Subscription\IO\Http\Controllers\SubscriptionController;
use App\Website\IO\Http\Controllers\WebsiteController;

Route::view('/', 'app')->name('home');

Route::get('/websites', [WebsiteController::class, 'index'])
    ->name('websites.index');

Route::get('/websites/{website}', [WebsiteController::class, 'show'])
    ->name('websites.show');

Route::post('/websites/{website}/subscribe', [SubscriptionController::class, 'subscribe'])
    ->name('websites.subscribe');

Route::post('/websites/{website}/posts', [PostController::class, 'store'])
    ->name('websites.posts.store');

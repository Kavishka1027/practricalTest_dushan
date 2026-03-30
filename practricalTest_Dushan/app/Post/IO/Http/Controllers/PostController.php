<?php

namespace App\Post\IO\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Post\UseCase\CreatePostInteractor;
use App\Post\UseCase\Requests\PostRequest;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function store(PostRequest $request, CreatePostInteractor $interactor): JsonResponse
    {
        return $interactor->execute($request);
    }
}

<?php

namespace App\Post\IO\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Post\UseCase\CreatePostInteractor;
use App\Post\UseCase\Requests\StorePostRequest;
use App\Website\Entities\Models\Website;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function store(
        StorePostRequest $request,
        Website $website,
        CreatePostInteractor $interactor
    ): JsonResponse {
        $post = $interactor($request, $website);

        return response()->json([
            'message' => 'Post created successfully.',
            'data' => $post,
        ], 201);
    }
}

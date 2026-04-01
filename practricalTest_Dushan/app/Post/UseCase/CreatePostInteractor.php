<?php

namespace App\Post\UseCase;

use App\Events\PostPublished;
use App\Post\Entities\Models\Post;
use App\Post\UseCase\Requests\StorePostRequest;
use App\Website\Entities\Models\Website;

class CreatePostInteractor
{
    public function __invoke(StorePostRequest $request, Website $website): Post
    {
        $post = $website->posts()->create([
            'title' => $request->string('title')->toString(),
            'description' => $request->string('description')->toString(),
        ]);

        PostPublished::dispatch($post);

        return $post;
    }
}

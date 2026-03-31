<?php

namespace App\Post\UseCase;

use App\Jobs\SendPostEmailJob;
use App\Post\Entities\Models\Post;
use App\Post\UseCase\Requests\PostRequest;
use App\Website\Entities\Models\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CreatePostInteractor
{
    public function execute(PostRequest $request): JsonResponse
    {
        $data = $request->validated();
        $website = Website::findOrFail($request->route('website'));

        $post = Post::create([
            'website_id' => $website->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'url' => $data['url'] ?? $this->generatePostUrl($website->url, $data['title']),
        ]);

        SendPostEmailJob::dispatch($post);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
        ], 201);
    }

    protected function generatePostUrl(string $websiteUrl, string $title): string
    {
        return sprintf(
            '%s/posts/%s-%s',
            rtrim($websiteUrl, '/'),
            Str::slug($title),
            Str::lower((string) Str::uuid())
        );
    }
}

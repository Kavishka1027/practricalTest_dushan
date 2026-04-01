<?php

namespace App\Events;

use App\Post\Entities\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostPublished
{
    use Dispatchable, SerializesModels;

    public function __construct(public Post $post)
    {
    }
}

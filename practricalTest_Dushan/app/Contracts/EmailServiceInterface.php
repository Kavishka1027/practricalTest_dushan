<?php

namespace App\Contracts;

use App\Post\Entities\Models\Post;
use App\SentEmail\Entities\Models\SentEmail;

interface EmailServiceInterface
{
    public function sendPostToSubscribers(Post $post): void;

    public function shouldSendEmail(Post $post, string $email): bool;

    public function markEmailAsSent(Post $post, string $email): SentEmail;
}

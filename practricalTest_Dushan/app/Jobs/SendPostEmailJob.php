<?php

namespace App\Jobs;

use App\Contracts\EmailServiceInterface;
use App\Post\Entities\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPostEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Post $post)
    {}

    public function handle(EmailServiceInterface $emailService): void
    {
        $emailService->sendPostToSubscribers($this->post);
    }
}

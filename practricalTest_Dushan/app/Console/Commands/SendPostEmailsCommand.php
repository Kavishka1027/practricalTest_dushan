<?php


namespace App\Console\Commands;

use App\Jobs\SendPostEmailJob;
use App\Post\Entities\Models\Post;
use Illuminate\Console\Command;

class SendPostEmailsCommand extends Command
{
    protected $signature = 'posts:send-emails {postId}';
    protected $description = 'Queue email sending jobs for all subscribers of a post website';

    public function handle(): int
    {
        $post = Post::with('website.subscriptions')->findOrFail((int)$this->argument('postId'));

        foreach ($post->website->subscriptions as $subscription) {
            SendPostEmailJob::dispatch($post->id, $subscription->id);
        }

        $this->info('Email jobs queued successfully.');

        return self::SUCCESS;
    }
}

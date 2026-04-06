<?php

namespace App\Mail;

use App\Post\Entities\Models\Post;
use App\Subscription\Entities\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostPublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Post         $post,
        public Subscription $subscription
    )
    {
    }

    public function build(): self
    {
        return $this
            ->subject('New post published: ' . $this->post->title)
            ->view('emails.post-published');
    }
}

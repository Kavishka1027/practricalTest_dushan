<?php

namespace App\SentPostEmail\Entities\Models;

use App\Post\Entities\Models\Post;
use App\Post\IO\Database\Factories\PostFactory;
use App\Subscription\Entities\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SentPostEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'subscription_id',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }
}

<?php

namespace App\SentEmail\Entities\Models;

use App\Post\Entities\Models\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SentEmail extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'email'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}

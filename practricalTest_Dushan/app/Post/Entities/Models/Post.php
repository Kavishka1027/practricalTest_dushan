<?php

namespace App\Post\Entities\Models;

use App\SentEmail\Entities\Models\SentPostEmail;
use App\Website\Entities\Models\Website;
use App\Post\IO\Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['website_id', 'title', 'description'];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    public function sentEmails(): HasMany
    {
        return $this->hasMany(SentPostEmail::class);
    }

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }
}

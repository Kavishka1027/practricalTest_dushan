<?php

namespace App\Subscription\Entities\Models;

use App\Website\Entities\Models\Website;
use App\Subscription\IO\Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['website_id', 'email'];

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }


    protected static function newFactory(): SubscriptionFactory
    {
        return SubscriptionFactory::new();
    }
}

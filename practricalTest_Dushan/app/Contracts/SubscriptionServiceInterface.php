<?php

namespace App\Contracts;

use App\Website\Entities\Models\Website;
use App\Subscription\Entities\Models\Subscription;

interface SubscriptionServiceInterface
{
    public function subscribe(Website $website, string $email): Subscription;
    public function validateSubscription(Website $website, string $email): bool;
}

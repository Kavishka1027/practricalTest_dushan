<?php

namespace App\Services;

use App\Contracts\SubscriptionServiceInterface;
use App\Website\Entities\Models\Website;
use App\Subscription\Entities\Models\Subscription;
use Illuminate\Validation\ValidationException;

class SubscriptionService implements SubscriptionServiceInterface
{
    public function subscribe(Website $website, string $email): Subscription
    {
        $this->validateSubscription($website, $email);

        return Subscription::firstOrCreate([
            'website_id' => $website->id,
            'email' => $email
        ]);
    }

    public function validateSubscription(Website $website, string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'email' => ['The email must be a valid email address.']
            ]);
        }

        return true;
    }
}

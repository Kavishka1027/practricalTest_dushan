<?php

namespace App\Subscription\UseCase;

use App\Subscription\Entities\Models\Subscription;
use App\Subscription\UseCase\Requests\StoreSubscriptionRequest;

class SubscribeToWebsiteInteractor
{
    public function __invoke(StoreSubscriptionRequest $request): Subscription
    {
        return Subscription::create([
            'website_id' => $request->integer('website_id'),
            'email' => $request->string('email')->toString(),
        ]);
    }
}

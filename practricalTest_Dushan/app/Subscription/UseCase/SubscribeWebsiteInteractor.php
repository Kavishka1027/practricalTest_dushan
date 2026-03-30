<?php

namespace App\Subscription\UseCase;

use App\Contracts\SubscriptionServiceInterface;
use App\Subscription\UseCase\Requests\SubscriptionRequest;
use Illuminate\Http\JsonResponse;

class SubscribeWebsiteInteractor
{
    public function __construct(
        protected SubscriptionServiceInterface $subscriptionService
    ) {
    }

    public function execute(SubscriptionRequest $request): JsonResponse
    {
        $website = $request->route('website');
        $subscription = $this->subscriptionService->subscribe($website, $request->validated('email'));

        return response()->json([
            'message' => $subscription->wasRecentlyCreated
                ? 'Successfully subscribed to website'
                : 'This email is already subscribed to the selected website',
            'subscription' => $subscription,
        ], $subscription->wasRecentlyCreated ? 201 : 200);
    }
}

<?php

namespace App\Subscription\IO\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Subscription\UseCase\Requests\StoreSubscriptionRequest;
use App\Subscription\UseCase\SubscribeToWebsiteInteractor;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function store(
        StoreSubscriptionRequest $request,
        SubscribeToWebsiteInteractor $interactor
    ): JsonResponse {
        $subscription = $interactor($request);

        return response()->json([
            'message' => 'Subscription created successfully.',
            'data' => $subscription,
        ], 201);
    }
}

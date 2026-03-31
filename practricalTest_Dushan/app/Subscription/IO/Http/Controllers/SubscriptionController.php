<?php

namespace App\Subscription\IO\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Subscription\UseCase\Requests\SubscriptionRequest;
use App\Subscription\UseCase\SubscribeWebsiteInteractor;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function subscribe(SubscriptionRequest $request, SubscribeWebsiteInteractor $interactor): JsonResponse
    {
        return $interactor->execute($request);
    }
}

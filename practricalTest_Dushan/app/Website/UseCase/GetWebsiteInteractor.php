<?php

namespace App\Website\UseCase;

use Illuminate\Http\JsonResponse;

class GetWebsiteInteractor
{
    public function execute(): JsonResponse
    {
        $website = request()->route('website');

        return response()->json($website->load(['posts', 'subscriptions']));
    }
}

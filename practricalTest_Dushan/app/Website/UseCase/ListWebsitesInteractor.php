<?php

namespace App\Website\UseCase;

use App\Website\Entities\Models\Website;
use Illuminate\Http\JsonResponse;

class ListWebsitesInteractor
{
    public function execute(): JsonResponse
    {
        $websites = Website::withCount(['posts', 'subscriptions'])->get();

        return response()->json($websites);
    }
}

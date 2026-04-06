<?php

namespace App\Website\UseCase;

use App\Website\Entities\Models\Website;
use Illuminate\Support\Facades\Cache;

class ListWebsitesInteractor
{
    public function __invoke(): array
    {
        return Cache::remember('websites.list', 3600, function () {
            return Website::query()
                ->select(['id', 'name', 'url'])
                ->orderBy('name')
                ->get()
                ->toArray();
        });
    }
}

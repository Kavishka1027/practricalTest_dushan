<?php

namespace App\Website\IO\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Website\UseCase\GetWebsiteInteractor;
use App\Website\UseCase\ListWebsitesInteractor;
use Illuminate\Http\JsonResponse;

class WebsiteController extends Controller
{
    public function index(ListWebsitesInteractor $interactor): JsonResponse
    {
        return $interactor->execute();
    }

    public function show(GetWebsiteInteractor $interactor): JsonResponse
    {
        return $interactor->execute();
    }
}

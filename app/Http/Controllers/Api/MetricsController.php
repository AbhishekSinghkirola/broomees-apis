<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReputationService;

class MetricsController extends Controller
{
    public function index(ReputationService $reputationService)
    {

        $metrics = $reputationService->getMetrics();


        return response()->json($metrics);
    }
}

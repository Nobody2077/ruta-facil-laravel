<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function __construct(private ReportService $service) {}

    public function summary(): JsonResponse
    {
        return response()->json(['data' => $this->service->summary()]);
    }

    public function recorridos(): JsonResponse
    {
        return response()->json(['data' => $this->service->recorridos()]);
    }
}

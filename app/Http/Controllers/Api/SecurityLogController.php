<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SecurityLogResource;
use App\Models\SecurityLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SecurityLogController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $logs = SecurityLog::with('user')
            ->when($request->query('event'), fn ($q, $event) => $q->where('event', $event))
            ->latest()
            ->paginate(20);

        return SecurityLogResource::collection($logs);
    }
}

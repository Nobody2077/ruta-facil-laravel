<?php

namespace App\Http\Controllers;

use App\Models\SecurityLog;
use Illuminate\Http\Request;

class SecurityLogController extends Controller
{
    public function index(Request $request)
    {
        $event = $request->query('event');

        $logs = SecurityLog::with('user')
            ->when($event, fn ($q) => $q->where('event', $event))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $events = ['login', 'logout', 'login_failed', 'access_denied'];

        return view('seguridad.logs', compact('logs', 'events', 'event'));
    }
}

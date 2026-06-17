<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'data' => $user->notifications()->take(20)->get()->map(fn ($n) => [
                'id'      => $n->id,
                'leida'   => $n->read_at !== null,
                'creada'  => $n->created_at,
                'datos'   => $n->data,
            ]),
        ]);
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['message' => 'Notificación marcada como leída.']);
    }
}

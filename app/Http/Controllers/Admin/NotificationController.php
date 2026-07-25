<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET /api/admin/notifications
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications;
        return response()->json($notifications);
    }

    // PUT /api/admin/notifications/mark-read
    public function markAsRead(Request $request)
    {
        $id = $request->input('id');

        if ($id) {
            $notification = $request->user()->notifications()->where('id', $id)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        } else {
            $request->user()->unreadNotifications->markAsRead();
        }

        return response()->json(['message' => 'Notifications marked as read']);
    }

    // DELETE /api/notifications/clear
    public function clearAll(Request $request)
    {
        $request->user()->notifications()->delete();
        return response()->json(['message' => 'Notifications cleared']);
    }
}

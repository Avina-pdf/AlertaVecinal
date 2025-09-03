<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->latest()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function readOne(Request $request, string $id)
    {
        $n = $request->user()->notifications()->where('id', $id)->firstOrFail();
        $n->markAsRead();
        return back();
    }

    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back()->with('status', 'Notificaciones marcadas como leídas.');
    }

    public function count(Request $request)
    {
        return ['count' => $request->user()->unreadNotifications()->count()];
    }
}

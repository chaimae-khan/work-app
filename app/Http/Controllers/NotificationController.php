<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Get all notifications for the authenticated user, ordered by newest first
        $notifications = auth()->user()->notifications()->latest()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }
    
    public function markAsRead($id)
    {
        // Mark a specific notification as read
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
        return back();
    }
    
    public function markAllAsRead()
    {
        // Mark all unread notifications as read
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
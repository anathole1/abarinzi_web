<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Fetch all notifications for the user, paginated
        $notifications = $user->notifications()->paginate(15);
        // Mark all unread notifications as read when the user visits this page
        $user->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }
}
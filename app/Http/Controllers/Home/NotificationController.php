<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Landing function to mark or delete notifications
     */
    public function __invoke(Request $request)
    {
        switch($request->action)
        {
            case 'read':
                $this->markMessages($request->list);
                break;
            case 'delete':
                $this->deleteMessages($request->list);
                break;
        }

        // return response()->noContent();
        return response()->json([
            'notifications' => [
                'list' => $request->user()->notifications,
                'new'  => $request->user()->unreadNotifications->count(),
            ],
        ]);
    }

    /**
     * Mark the selected notifications as read
     */
    protected function markMessages($list)
    {
        foreach($list as $msg)
        {
            Auth::user()->notifications()->where('id', $msg)->first()->markAsRead();
            Log::debug('Marked Notification ID '.$msg.' as read');
        }
    }

    /**
     * Delete the selected notifications
     */
    protected function deleteMessages($list)
    {
        foreach($list as $msg)
        {
            Auth::user()->notifications()->where('id', $msg)->first()->delete();
            Log::debug('Marked Notification ID '.$msg.' as read');
        }
    }
}

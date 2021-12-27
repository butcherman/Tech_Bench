<?php

namespace App\Listeners\Notify;

use App\Events\TechTips\TechTipCommentFlaggedEvent;
use App\Models\User;
use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use App\Notifications\FlaggedTechTipCommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotifyOfFlaggedComment implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(TechTipCommentFlaggedEvent $event)
    {
        $perm     = UserRolePermissionTypes::where('description', 'Manage Tech Tips')->first();
        $roles    = UserRolePermissions::where('perm_type_id', $perm->perm_type_id)->where('allow', true)->get()->pluck('role_id');
        $userList = User::where('role_id', $roles)->get();

        Notification::send($userList, new FlaggedTechTipCommentNotification($event->comment, Auth::user()));
    }
}

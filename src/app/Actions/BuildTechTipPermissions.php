<?php

namespace App\Actions;

use App\Models\TechTipComment;
use App\Models\TechTip;
use App\Models\User;

class BuildTechTipPermissions
{
    public static function build(User $user)
    {
        return [
            'manage' => $user->can('manage', TechTip::class),
            'create' => $user->can('create', TechTip::class),
            'update' => $user->can('update', TechTip::class),
            'delete' => $user->can('delete', TechTip::class),
            'comment' => $user->can('create', TechTipComment::class),
        ];
    }
}

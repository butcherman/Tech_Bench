<?php

namespace App\Actions;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\User;

class BuildTechTipPermissions
{
    public static function build(User $user): array
    {
        return [
            'manage' => $user->can('manage', TechTip::class),
            'create' => $user->can('create', TechTip::class),
            'update' => $user->can('update', TechTip::class),
            'delete' => $user->can('delete', TechTip::class),
            'public' => $user->can('public', TechTip::class),
            'comment' => $user->can('create', TechTipComment::class),
        ];
    }
}

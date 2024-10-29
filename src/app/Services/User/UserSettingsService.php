<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Collection;

class UserSettingsService
{
    public function updateUserAccount(Collection $requestData, User $user): void
    {
        $user->update($requestData->toArray());
    }
}

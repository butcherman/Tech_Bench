<?php

namespace App\Exceptions\Customer;

use Exception;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentDataFailedVerificationException extends Exception
{
    public function report(int $newEquipId, int $changingId): void
    {
        Log::critical(
            'Someone is trying to update the Equipment Data value for a different Equipment ID than they are viewing',
            [
                'viewing_equip_id' => $newEquipId,
                'changing_equip_id' => $changingId,
            ]
        );
    }

    public function render(): void
    {
        abort(403);
    }
}

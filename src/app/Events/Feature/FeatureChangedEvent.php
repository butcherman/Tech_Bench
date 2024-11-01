<?php

namespace App\Events\Feature;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FeatureChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is called when something happens that may affect a users permission
     * to use an application feature.  The feature table will be cleared and
     * the application can then rebuild it.
     */
    public function __construct(public ?User $user = null) {}
}

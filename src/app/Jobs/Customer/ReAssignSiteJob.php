<?php

namespace App\Jobs\Customer;

use App\Actions\Customer\ReAssignCustomerSite;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ReAssignSiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Collection $request, User $user)
    {
        Log::notice(
            'Re-Assign Customer job called by '.$user->username,
            $request->toArray()
        );
    }

    /**
     * Execute the job.
     */
    public function handle(ReAssignCustomerSite $action): void
    {
        $action($this->request);
    }
}

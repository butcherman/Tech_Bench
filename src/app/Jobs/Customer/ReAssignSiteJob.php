<?php

namespace App\Jobs\Customer;

use App\Actions\Customer\ReAssignCustomerSite;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ReAssignSiteJob implements ShouldQueue
{
    use Queueable;

    /*
    |---------------------------------------------------------------------------
    | Move a Customer Site from one Customer to another.
    |---------------------------------------------------------------------------
    */
    public function __construct(protected int $siteId, protected int $toCustId) {}

    /**
     * Execute the job.
     */
    public function handle(ReAssignCustomerSite $reAssignAction): void
    {
        $reAssignAction($this->siteId, $this->toCustId);
    }
}

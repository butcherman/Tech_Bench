<?php

namespace App\Jobs\Customer;

use App\Http\Requests\Customer\ReAssignSiteRequest;
use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReAssignSiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $moveSite, $toCustomer;

    /**
     * Create a new job instance.
     */
    public function __construct(ReAssignSiteRequest $request)
    {
        $this->moveSite = CustomerSite::find($request->moveSiteId);
        $this->toCustomer = Customer::find($request->toCustomer);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //

        Log::critical('starting job....');

        // TODO - Finish setting up Job.
    }
}

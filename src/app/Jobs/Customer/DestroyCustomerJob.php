<?php

namespace App\Jobs\Customer;

use App\Actions\DestroyCustomer;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DestroyCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Customer $customer)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $jobData = new DestroyCustomer($this->customer);

        if ($jobData->wasSuccessful()) {
            Log::channel('cust')->info('Force Deleting Customer'.$this->customer->name.' was Successful');
        } else {
            // @codeCoverageIgnoreStart
            Log::channel('cust')->error('Force Deleting Customer'.$this->customer->name.' Failed');
            // @codeCoverageIgnoreEnd
        }
    }
}

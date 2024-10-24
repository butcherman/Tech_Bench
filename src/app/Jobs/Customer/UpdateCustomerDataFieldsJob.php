<?php

namespace App\Jobs\Customer;

use App\Models\EquipmentType;
use App\Service\Customer\CustomerEquipmentDataService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCustomerDataFieldsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected EquipmentType $equipment) {}

    /**
     * Execute the job.
     */
    public function handle(CustomerEquipmentDataService $svc): void
    {
        $svc->updateEquipmentDataFields($this->equipment);
    }
}

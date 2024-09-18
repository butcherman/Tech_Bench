<?php

namespace App\Jobs\Customer;

use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateCustomerDataFieldsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected CustomerEquipment $equipment)
    {
        Log::info('Creating new Data Fields for Customer Equipment', $equipment->toArray());
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dataFields = DataField::where('equip_id', $this->equipment->equip_id)->get();

        foreach ($dataFields as $field) {
            CustomerEquipmentData::create([
                'cust_equip_id' => $this->equipment->cust_equip_id,
                'field_id' => $field->field_id,
                'value' => null,
            ]);

            Log::debug('New Equipment Data Created', [
                'cust_equip_id' => $this->equipment->cust_equip_id,
                'field_id' => $field->field_id,
                'value' => null,
            ]);
        }
    }
}

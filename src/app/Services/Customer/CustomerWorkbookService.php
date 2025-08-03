<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerWorkbook;
use App\Models\CustomerWorkbookValue;
use App\Models\EquipmentWorkbook;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CustomerWorkbookService
{
    /**
     * Create a new workbook for customer equipment
     */
    public function createWorkbook(Customer $customer, CustomerEquipment $equipment): void
    {
        $blankWorkbook = EquipmentWorkbook::where('equip_id', $equipment->equip_id)
            ->first();

        CustomerWorkbook::create([
            'wb_hash' => Str::uuid(),
            'cust_id' => $customer->cust_id,
            'cust_equip_id' => $equipment->cust_equip_id,
            'wb_data' => $blankWorkbook->workbook_data,
            'wb_version' => $blankWorkbook->version_hash,
        ]);
    }

    /**
     * Make a workbook avaialable via its public link
     */
    public function publishWorkbook(CustomerWorkbook $workbook, Collection $requestData): void
    {
        $expire = $requestData->get('expires');

        $workbook->published = is_null($expire) ? false : true;
        $workbook->publish_until = $expire ? Carbon::parse($requestData->get('expires')) : null;
        $workbook->save();
    }

    /**
     * Determine if the workbook is available to be viewed publicly
     */
    public function validateWorkbook(CustomerWorkbook $workbook): bool
    {
        if (!$workbook->published || is_null($workbook->publish_until)) {
            return false;
        }

        if (Carbon::now() > Carbon::parse($workbook->publish_until)) {
            return false;
        }

        return true;
    }

    /**
     * Get a Workbook for the Customer Equipment
     */
    public function getWorkbook(Customer $customer, CustomerEquipment $equipment): ?CustomerWorkbook
    {
        $workbook = CustomerWorkbook::where('cust_equip_id', $equipment->cust_equip_id)->first();

        // Update the workbook with any editing changes that need to be made
        $workbook->wb_data = str_replace('[ Customer Name ]', $customer->name, $workbook->wb_data);

        return $workbook;
    }

    /**
     * Remove internal only pages from the workbook
     */
    public function getPublicWorkbookData(CustomerWorkbook $workbook): mixed
    {
        $wbData = json_decode($workbook->wb_data);
        $body = $wbData->body;

        foreach ($body as $key => $page) {
            if (!$page->canPublish) {
                unset($body[$key]);
            }
        }

        $wbData->body = array_values($body);

        return json_encode($wbData);
    }

    /**
     * Get all value data for the selected workbook
     */
    public function getWorkbookValues(CustomerWorkbook $workbook): mixed
    {
        // TODO - Return only public values
        return $workbook->WorkbookValues->mapWithKeys(function ($item) {
            return [$item->index => $item->value];
        });
    }

    /**
     * Set a workbook field value
     */
    public function setWorkbookValue(CustomerWorkbook $workbook, Collection $requestData): void
    {
        CustomerWorkbookValue::updateOrCreate(
            [
                'wb_id' => $workbook->wb_id,
                'index' => $requestData->get('index'),
            ],
            [
                'value' => $requestData->get('fieldValue'),
            ]
        );
    }
}

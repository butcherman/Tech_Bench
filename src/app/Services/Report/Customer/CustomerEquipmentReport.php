<?php

namespace App\Services\Report\Customer;

use App\Facades\CacheData;
use App\Models\Customer;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CustomerEquipmentReport extends CustomerReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamForm = 'CustomerEquipmentReportForm';
        $this->reportDataPage = 'CustomerEquipmentReport';
        $this->reportParamProps = [
            'equipment-types' => CacheData::equipmentCategorySelectBox(),
        ];
    }

    /**
     * Validate the request to run the report.
     */
    public function getValidationParams(): array
    {
        return [
            'equip_id' => ['required', 'numeric'],
        ];
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): array
    {
        $data = [
            'equipName' => EquipmentType::find($reportParams->get('equip_id'))->name,
            'custList' => Customer::whereHas(
                'Equipment',
                fn (Builder $query) => $query->where('equip_id', $reportParams->get('equip_id'))
            )->get(),
        ];

        return $data;
    }
}

<?php

namespace App\Services\Report\Customer;

use App\Facades\CacheData;
use App\Models\Customer;
use App\Models\CustomerFileType;
use App\Models\EquipmentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CustomerFilesReport extends CustomerReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamForm = 'CustomerFilesReportForm';
        $this->reportDataPage = 'CustomerFilesReport';
        $this->reportParamProps = [
            'file-types' => CacheData::fileTypes(),
            'equipment-types' => CacheData::equipmentCategorySelectBox(),
        ];
    }

    /**
     * Validate the request to run the report.
     */
    public function getValidationParams(): array
    {
        return [
            'hasInput' => 'required|string',
            'file_types' => 'required|array',
            'has_equipment' => 'nullable|numeric',
        ];
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): array
    {
        $has = $reportParams->get('hasInput') === 'have' ? true : false;
        $equip = EquipmentType::find($reportParams->get('has_equipment'));
        $data = [];

        foreach ($reportParams->get('file_types') as $typeId) {
            $fileType = CustomerFileType::find($typeId);

            if ($has) {
                $custList = $this->getHasType($fileType->file_type_id, $equip->equip_id ?? null);
            } else {
                $custList = $this->getMissingType($fileType->file_type_id, $equip->equip_id ?? null);
            }

            $data[$fileType->description] = $custList;
        }

        return $data;
    }

    /**
     * Get Customers that have the included file type
     */
    protected function getHasType(int $typeId, ?int $equipId = null)
    {
        return Customer::whereHas(
            'Files',
            fn (Builder $query) => $query->where('file_type_id', $typeId)
        )->when($equipId, function (Builder $query) use ($equipId) {
            $query->whereHas(
                'Equipment',
                fn (Builder $q) => $q->where('equip_id', $equipId)
            );
        })->with('Files')->orderBy('name', 'asc')->get();
    }

    /**
     * Get Customers that are missing the included file type
     */
    protected function getMissingType(int $typeId, ?int $equipId = null)
    {
        return Customer::whereDoesntHave(
            'Files',
            fn (Builder $query) => $query->where('file_type_id', $typeId)
        )->when($equipId, function (Builder $query) use ($equipId) {
            $query->whereHas(
                'Equipment',
                fn (Builder $q) => $q->where('equip_id', $equipId)
            );
        })->orderBy('name', 'asc')->get();
    }
}

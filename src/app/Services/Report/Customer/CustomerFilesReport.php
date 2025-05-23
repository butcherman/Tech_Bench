<?php

namespace App\Services\Report\Customer;

use App\Facades\CacheData;
use App\Models\Customer;
use App\Models\CustomerFileType;
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
        ];
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): array
    {
        $has = $reportParams->get('hasInput') === 'have' ? true : false;
        $data = [];

        foreach ($reportParams->get('file_types') as $typeId) {
            $fileType = CustomerFileType::find($typeId);

            if ($has) {
                $custList = $this->getHasType($fileType->file_type_id);
            } else {
                $custList = $this->getMissingType($fileType->file_type_id);
            }

            $data[$fileType->description] = $custList;
        }

        return $data;
    }

    /**
     * Get Customers that have the included file type
     */
    protected function getHasType(int $typeId)
    {
        return Customer::whereHas(
            'Files',
            fn (Builder $query) => $query->where('file_type_id', $typeId)
        )->with('Files')->orderBy('name', 'asc')->get();
    }

    /**
     * Get Customers that are missing the included file type
     */
    protected function getMissingType(int $typeId)
    {
        return Customer::whereDoesntHave(
            'Files',
            fn (Builder $query) => $query->where('file_type_id', $typeId)
        )->orderBy('name', 'asc')->get();
    }
}

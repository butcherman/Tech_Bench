<?php

namespace App\Services\Report\Customer;

use App\Facades\CacheFacade;
use App\Models\Customer;
use App\Models\CustomerFileType;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class CustomerFilesReport extends CustomerReportBase
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->reportParamPage = 'Report/Customer/Files/Index';
        $this->reportDataPage = 'Report/Customer/Files/Show';
        $this->reportParamProps = ['file-types' => CacheFacade::fileTypes(),];
    }

    /**
     * Validate the request to run the report.
     */
    public function validateReportParams(Request $request): Collection
    {
        return Validator::make($request->all(), [
            'hasInput' => ['required', 'string'],
            'fileTypes' => ['required', 'array'],
        ])->safe()->collect();
    }

    /**
     * Run the report
     */
    public function generateReportData(Collection $reportParams): mixed
    {
        $reportData = [];

        foreach ($reportParams->get('fileTypes') as $fileType) {
            $type = CustomerFileType::find($fileType);

            $reportData[$type->description] = match ($reportParams->get('hasInput')) {
                'have' => $this->getHasType($type->file_type_id),
                'are missing' => $this->getMissingType($type->file_type_id),
                default => dd($reportParams->get('hasInput'))
            };
        }

        return [
            'data' => $reportData,
            'hasTag' => $reportParams->get('hasInput')
        ];
    }

    /**
     * Return list of customers that Have the selected Type ID
     */
    protected function getHasType(int $typeId): EloquentCollection
    {
        return Customer::whereHas('CustomerFile', function ($q) use ($typeId) {
            $q->where('file_type_id', $typeId);
        })->with('CustomerFile')->orderBy('name', 'asc')->get();
    }

    /**
     * Return list of customers that do not have the selected Type ID
     */
    protected function getMissingType(int $typeId): EloquentCollection
    {
        return Customer::whereDoesntHave('CustomerFile', function ($q) use ($typeId) {
            $q->where('file_type_id', $typeId);
        })->with('CustomerFile')->orderBy('name', 'asc')->get();
    }
}

<?php

namespace App\Service\Reports;

use App\Http\Requests\Report\Customer\CustomerFilesRequest;
use App\Models\Customer;
use App\Models\CustomerFileType;
use Illuminate\Database\Eloquent\Builder;

class CustomerFilesReport extends Reports
{
    public function __construct(protected CustomerFilesRequest $request)
    {
        parent::__construct();
    }

    protected function buildReportData()
    {
        foreach ($this->request->fileTypes as $fileTypeId) {
            $fileType = CustomerFileType::find($fileTypeId);

            if ($this->request->hasInput === 'has') {
                $custList = $this->getHasType($fileTypeId);
            } else {
                $custList = $this->getMissingType($fileTypeId);
            }

            $this->reportData[$fileType->description] = $custList;
        }
    }

    protected function getHasType(int $typeId)
    {
        return Customer::whereHas(
            'CustomerFile',
            fn(Builder $query) =>
            $query->where('file_type_id', $typeId)
        )->with('CustomerFile')->orderBy('name', 'asc')->get();
    }

    protected function getMissingType(int $typeId)
    {
        return Customer::whereDoesntHave(
            'CustomerFile',
            fn(Builder $query) =>
            $query->where('file_type_id', $typeId)
        )->orderBy('name', 'asc')->get();
    }
}
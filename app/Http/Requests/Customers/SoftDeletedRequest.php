<?php

namespace App\Http\Requests\Customers;

use App\Jobs\CustomerRemoveFilesJob;
use App\Models\Customer;
use App\Models\CustomerFile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SoftDeletedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        if ($this->isMethod('POST')) {
            return $this->user()->can('restore', Customer::class);
        }

        return $this->user()->can('forceDelete', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'cust_list' => 'required|array',
        ];
    }

    /**
     * Restore an array of customer ID's
     */
    public function restore()
    {
        foreach ($this->cust_list as $custId) {
            $cust = Customer::onlyTrashed()->where('cust_id', $custId)->first();
            $cust->restore();
            Log::stack(['daily', 'cust'])->info('Customer '.$cust->name.' has been restored by '.$this->user()->username);
        }
    }

    /**
     * Completely delete an array of customer ID's
     */
    public function destroy()
    {
        $fileList = [];

        foreach ($this->cust_list as $custId) {
            $cust = Customer::onlyTrashed()->where('cust_id', $custId)->first();

            //  Get a list of the customers files to destroy later
            $custFiles = CustomerFile::where('cust_id', $custId)->get();
            foreach ($custFiles as $file) {
                $fileList[] = $file->file_id;
            }

            $cust->forceDelete();
            Log::stack(['daily', 'cust'])->notice('Customer '.$cust->name.' was completely deleted by '.$this->user()->username);
        }

        CustomerRemoveFilesJob::dispatch($fileList);
    }
}

<?php

// TODO - Refactor

namespace App\Http\Requests\Customer;

use App\Models\CustomerFile;
use App\Models\FileUpload;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CustomerFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->isMethod('PUT')) {
            return $this->user()->can('update', $this->file);
        }

        return $this->user()->can('create', CustomerFile::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'file_type' => 'required|string',
            'site_list' => 'required_if:file_type,site',
            'cust_equip_id' => 'required_if:file_type,equipment',
            'file_type_id' => 'required|exists:customer_file_types',
        ];
    }

    /**
     * Create a new customer file
     */
    public function createFile(FileUpload $fileData)
    {
        Log::debug('Creating new customer file', $fileData->toArray());
        $this->mergeData($fileData);
        $this->addAttributes();
        Log::debug('Request Data - ', $this->all());

        $newFile = CustomerFile::create($this->except(['file_type', 'site_list']));
        Log::debug('New Customer File Created', $newFile->toArray());

        if ($this->file_type === 'site') {
            $newFile->CustomerSite()->sync($this->site_list);
            Log::debug('Synced new customer file to Customer Sites', $this->site_list);
        }

        return $newFile;
    }

    /**
     * Update an existing Customer File
     */
    public function updateFile()
    {
        $this->addAttributes();
        $this->file->update($this->except(['file_type', 'site_list']));

        $this->file->CustomerSite()->sync($this->site_list);
    }

    /**
     * Add additional attributes needed for the file
     */
    public function addAttributes()
    {
        $this->merge([
            'cust_id' => $this->customer->cust_id,
            'user_id' => $this->user()->user_id,
        ]);

        // If this is not an equipment file, remove cust equip_id
        if ($this->file_type !== 'equipment') {
            $this->cust_equip_id = null;
        }

        // If this is not a site note, remove any attached sites
        if ($this->file_type !== 'site') {
            $this->site_list = json_decode('[]');
        }
    }

    /**
     * Add the necessary additional data for the customer file
     */
    protected function mergeData(FileUpload $fileData)
    {
        $this->merge([
            'file_id' => $fileData->file_id,
            'user_id' => $this->user()->user_id,
            'cust_id' => $this->customer->cust_id,
            'site_list' => json_decode($this->site_list),
            'cust_equip_id' => json_decode($this->cust_equip_id),
            'name' => $this->name,
            'file_type' => $this->file_type,
        ]);
    }
}

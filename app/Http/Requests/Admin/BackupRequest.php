<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;

class BackupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('viewAny', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'enabled' => 'required|boolean',
            'number'  => 'required|numeric',
        ];
    }
}

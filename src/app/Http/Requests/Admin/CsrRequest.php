<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;

class CsrRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'countryName' => 'required|string',
            'stateOrProvinceName' => 'required|string',
            'localityName' => 'required|string',
            'organizationName' => 'required|string',
            'organizationalUnitName' => 'required|string',
            'commonName' => 'required|string',
            'emailAddress' => 'required|email',
        ];
    }
}

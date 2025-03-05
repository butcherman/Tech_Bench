<?php

namespace App\Http\Requests;

use App\Models\FileLink;
use Illuminate\Foundation\Http\FormRequest;

class FileLinkSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage', FileLink::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'default_link_life' => ['required', 'numeric'],
            'auto_delete' => ['required', 'boolean'],
            'auto_delete_days' => ['required', 'numeric'],
            'auto_delete_override' => ['required', 'boolean'],
        ];
    }
}

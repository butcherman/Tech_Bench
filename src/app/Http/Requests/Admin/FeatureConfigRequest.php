<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use App\Traits\AppSettingsTrait;
use Illuminate\Foundation\Http\FormRequest;

class FeatureConfigRequest extends FormRequest
{
    use AppSettingsTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'file_links' => ['required', 'boolean'],
            'public_tips' => ['required', 'boolean'],
            'tip_comments' => ['required', 'boolean'],
        ];
    }
}

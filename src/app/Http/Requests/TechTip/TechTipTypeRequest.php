<?php

namespace App\Http\Requests\TechTip;

use App\Models\TechTip;
use Illuminate\Foundation\Http\FormRequest;

class TechTipTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage', TechTip::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string'],
        ];
    }
}

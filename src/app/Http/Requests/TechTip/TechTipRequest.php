<?php

namespace App\Http\Requests\TechTip;

use App\Models\TechTip;
use App\Traits\Requests\NormalizeJson;
use Illuminate\Foundation\Http\FormRequest;

class TechTipRequest extends FormRequest
{
    use NormalizeJson;

    protected $errorBag = 'form_error';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->tech_tip) {
            // Update Public Tech Tip
            if ($this->public) {
                return $this->user()->can('update', $this->tech_tip)
                    && $this->user()->can('public', TechTip::class);
            }

            // Update Normal Tech Tip
            return $this->user()->can('update', $this->tech_tip);
        }

        // Create public Tech Tip
        if ($this->public) {
            return $this->user()->can('create', TechTip::class)
                && $this->user()->can('public', TechTip::class);
        }

        // Create normal Tech Tip
        return $this->user()->can('create', TechTip::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string'],
            'tip_type_id' => ['required', 'exists:tech_tip_types'],
            'equipList' => ['required', 'array'],
            'details' => ['required', 'string'],
            'suppress' => ['required', 'bool'],
            'sticky' => ['required', 'bool'],
            'public' => ['required', 'bool'],
            'removedFiles' => ['array', 'nullable'],
        ];
    }
}

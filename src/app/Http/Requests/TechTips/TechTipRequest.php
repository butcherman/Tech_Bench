<?php

namespace App\Http\Requests\TechTips;

use App\Models\TechTip;
use Illuminate\Foundation\Http\FormRequest;

class TechTipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        if ($this->techTip) {
            return $this->user()->can('update', $this->techTip);
        }

        return $this->user()->can('create', TechTip::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string',
            'tip_type_id' => 'required|exists:tech_tip_types',
            'equipList' => 'required',
            'details' => 'required|string',
            'suppress' => 'required',
            'sticky' => 'required',
            'public' => 'required',
        ];
    }
}

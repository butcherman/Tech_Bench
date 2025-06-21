<?php

namespace App\Http\Requests\TechTip;

use App\Models\TechTip;
use Illuminate\Foundation\Http\FormRequest;

class TechTipSettingsRequest extends FormRequest
{
    protected $errorBag = 'form_error';

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
            'allow_comments' => ['required', 'bool'],
            'allow_public' => ['required', 'bool'],
            'allow_download' => ['required', 'bool'],
            'public_link_text' => ['required', 'string'],
        ];
    }
}

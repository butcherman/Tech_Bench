<?php

namespace App\Http\Requests\TechTips;

use App\Models\TechTip;
use App\Traits\AppSettingsTrait;
use Illuminate\Foundation\Http\FormRequest;

class TechTipsSettingsRequest extends FormRequest
{
    use AppSettingsTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage', TechTip::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'allow_comments' => 'required|bool',
            'allow_public' => 'required|bool',
        ];
    }

    public function processSettings()
    {
        $this->saveSettingsArray($this->only(['allow_public', 'allow_comments']), 'techTips');
    }
}

<?php

namespace App\Http\Requests\TechTips;

use App\Events\Feature\FeatureChangedEvent;
use App\Models\TechTip;
use App\Traits\AppSettingsTrait;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Pennant\Feature;

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
        $this->saveSettingsArray(
            $this->only(['allow_public', 'allow_comments']),
            'tech-tips'
        );

        // Forget the feature settings to re-force checking
        event(new FeatureChangedEvent);
    }
}

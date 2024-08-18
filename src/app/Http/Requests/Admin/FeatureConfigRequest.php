<?php

namespace App\Http\Requests\Admin;

use App\Events\Feature\FeatureChangedEvent;
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
            'file_links' => 'required|boolean',
            'public_tips' => 'required|boolean',
            'tip_comments' => 'required|boolean',
        ];
    }

    public function updateFeatureSettings()
    {
        $this->saveSettings('fileLink.feature_enabled', $this->file_links);
        $this->saveSettings('techTips.allow_public', $this->public_tips);
        $this->saveSettings('techTips.allow_comments', $this->tip_comments);

        // Forget the feature settings to re-force checking
        event(new FeatureChangedEvent);
    }
}

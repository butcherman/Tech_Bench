<?php

namespace App\Http\Requests\Admin\Config;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;

class LogoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'mimes:jpeg,bmp,png,jpg,gif'],
        ];
    }
}

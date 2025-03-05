<?php

namespace App\Http\Requests\FileLink;

use App\Models\FileLink;
use Illuminate\Foundation\Http\FormRequest;

class FileLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->link) {
            return $this->user()->can('update', $this->link);
        }

        return $this->user()->can('create', FileLink::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'link_name' => ['required', 'string'],
            'expire' => ['required', 'string'],
            'allow_upload' => ['required'],
            'instructions' => ['nullable', 'string'],
        ];
    }
}

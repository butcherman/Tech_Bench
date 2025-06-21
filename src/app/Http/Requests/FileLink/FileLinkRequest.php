<?php

namespace App\Http\Requests\FileLink;

use App\Models\FileLink;
use App\Traits\Requests\NormalizeJson;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FileLinkRequest extends FormRequest
{
    use NormalizeJson;

    protected $errorBag = 'form_error';

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
            'link_hash' => [
                'required',
                'string',
                'regex:/^[A-Za-z0-9\-]+$/',
                Rule::unique('file_links')->ignore($this->link),
            ],
        ];
    }
}

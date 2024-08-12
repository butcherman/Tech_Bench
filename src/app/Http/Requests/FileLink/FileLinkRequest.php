<?php

namespace App\Http\Requests\FileLink;

use App\Features\FileLinkFeature;
use App\Models\FileLink;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class FileLinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->features()->active(FileLinkFeature::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'link_name' => 'required|string',
            'expire' => 'required|string',
            'allow_upload' => 'required',
            'instructions' => 'nullable|string',
        ];
    }

    public function createFileLink()
    {
        $newLink = FileLink::create([
            'user_id' => $this->user()->user_id,
            'link_hash' => Str::uuid(),
            'link_name' => $this->link_name,
            'expire' => $this->expire,
            'instructions' => $this->instructions,
            'allow_upload' => $this->boolean('allow_upload')
        ]);

        if ($this->session()->has('link-file')) {
            $fileList = $this->session()->pull('link-file');
            $newLink->FileUpload()->sync($fileList);
        }

        return $newLink;
    }
}

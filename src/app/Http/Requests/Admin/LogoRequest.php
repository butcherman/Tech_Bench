<?php

// TODO - Refactor

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

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
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'file' => 'required|mimes:jpeg,bmp,png,jpg,gif',
        ];
    }

    /**
     * Save the Logo File
     */
    public function processLogo()
    {
        $path = 'images/logo';
        $storedFile = Storage::disk('public')->putFile($path, new File($this->file));

        return '/storage/'.$storedFile;
    }
}

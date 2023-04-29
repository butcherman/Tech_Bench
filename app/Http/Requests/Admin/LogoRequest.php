<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LogoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('viewAny', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'logo' => 'required|mimes:jpeg,bmp,png,jpg,gif',
        ];
    }

    /**
     * Save the Logo File
     */
    public function saveLogo(UploadedFile $file)
    {
        $path = 'images/logo';
        $location = '/storage/'.Storage::disk('public')->putFile($path, new File($file));

        return $location;
    }
}

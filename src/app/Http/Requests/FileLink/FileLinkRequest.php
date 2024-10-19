<?php

// TODO - Refactor

namespace App\Http\Requests\FileLink;

use App\Features\FileLinkFeature;
use App\Http\Requests\UploadFileBaseRequest;
use App\Models\FileLink;
use App\Models\FileLinkTimeline;
use Illuminate\Support\Str;

class FileLinkRequest extends UploadFileBaseRequest
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
            'allow_upload' => $this->boolean('allow_upload'),
        ]);

        if ($this->session()->has('link-file')) {
            $fileList = $this->session()->pull('link-file');
            $timeline = FileLinkTimeline::create([
                'link_id' => $newLink->link_id,
                'added_by' => $this->user()->user_id,
            ]);
            $newLink->FileUpload()->syncWithPivotValues($fileList, [
                'timeline_id' => $timeline->timeline_id,
            ]);
        }

        return $newLink;
    }
}

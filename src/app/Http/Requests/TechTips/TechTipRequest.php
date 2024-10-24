<?php

// TODO - Refactor

namespace App\Http\Requests\TechTips;

use App\Events\File\FileDataDeletedEvent;
use App\Http\Requests\UploadFileBaseRequest;
use App\Models\TechTip;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TechTipRequest extends UploadFileBaseRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        if ($this->tech_tip) {
            if ($this->public) {
                return $this->user()->can('update', $this->tech_tip) && $this->user()->can('public', TechTip::class);
            }

            return $this->user()->can('update', $this->tech_tip);
        }

        if ($this->public) {
            return $this->user()->can('create', TechTip::class) && $this->user()->can('public', TechTip::class);
        }

        return $this->user()->can('create', TechTip::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string',
            'tip_type_id' => 'required|exists:tech_tip_types',
            'equipList' => 'required',
            'details' => 'required|string',
            'suppress' => 'required',
            'sticky' => 'required',
            'public' => 'required',
        ];
    }

    /**
     * Create a new Tech Tip
     */
    public function createTechTip()
    {
        $this->mergeData();
        $this->setSlug();

        $newTip = TechTip::create($this->except(['equipList', 'suppress']));
        $newTip->EquipmentType()->sync($this->equipList);

        // Handle any files uploaded with the tip
        if ($this->session()->has('tip-file')) {
            $fileList = $this->session()->pull('tip-file');
            Log::debug('Tech Tip file list for Tip ID '.$newTip->tip_id, $fileList);
            $newTip->FileUpload()->sync($fileList);
        }

        return $newTip;
    }

    /**
     * Update an existing Tech Tip
     */
    public function updateTechTip()
    {
        $this->mergeData();
        if ($this->subject !== $this->tech_tip->subject) {
            $this->setSlug();
        }

        $this->tech_tip
            ->update($this->except(['equipList', 'suppress', 'removedFiles']));
        $this->tech_tip->EquipmentType()->sync($this->equipList);

        // Handle any files added or removed from the tip
        $existingFiles = $this->tech_tip->FileUpload->pluck('file_id')->toArray();
        foreach ($this->removedFiles as $fileId) {
            unset($existingFiles[array_search($fileId, $existingFiles)]);
        }

        $newFiles = $this->session()->pull('tip-file', []);
        $allFiles = array_merge($existingFiles, $newFiles);
        $this->tech_tip->FileUpload()->sync($allFiles);

        foreach ($this->removedFiles as $fileId) {
            event(new FileDataDeletedEvent($fileId));
        }

        return $this->tech_tip;
    }

    /**
     * Re-format any necessary data
     */
    protected function mergeData()
    {
        if ($this->tech_tip) {
            $this->merge([
                'updated_id' => $this->user()->user_id,
            ]);
        } else {
            $this->merge([
                'user_id' => $this->user()->user_id,
            ]);
        }
    }

    /**
     * Create a unique slug for the Tech Tip
     */
    protected function setSlug()
    {
        $index = 1;
        $slug = Str::slug($this->subject);

        while (TechTip::where('slug', $slug)->first()) {
            $slug = Str::slug($this->subject.'-'.$index);
            $index++;
        }

        $this->merge(['slug' => $slug]);
    }
}

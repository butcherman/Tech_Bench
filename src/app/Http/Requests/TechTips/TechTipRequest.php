<?php

namespace App\Http\Requests\TechTips;

use App\Models\TechTip;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class TechTipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        if ($this->techTip) {
            return $this->user()->can('update', $this->techTip);
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

        $newTip = TechTip::create($this->except(['equipList']));
        $newTip->EquipmentType()->sync($this->equipList);

        return $newTip;
    }

    /**
     * Re-format any necessary data
     */
    protected function mergeData()
    {
        if ($this->file) {
            $this->merge([
                'equipList' => json_decode($this->equipList),
                'sticky' => (bool) $this->sticky,
                'public' => (bool) $this->public,
            ]);
        }

        if ($this->techTip) {
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
            $slug = Str::slug($this->subject . '-' . $index);
            $index++;
        }

        $this->merge(['slug' => $slug]);
    }
}

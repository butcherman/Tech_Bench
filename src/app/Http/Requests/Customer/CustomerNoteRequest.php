<?php

namespace App\Http\Requests\Customer;

use App\Models\CustomerNote;
use Illuminate\Foundation\Http\FormRequest;

class CustomerNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->note) {
            return $this->user()->can('update', $this->note);
        }

        return $this->user()->can('create', CustomerNote::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string'],
            'note_type' => ['required', 'string'],
            'urgent' => ['required', 'boolean'],
            'site_list' => ['required_if:note_type,site'],
            'cust_equip_id' => ['required_if:note_type,equipment'],
            'details' => ['required', 'string'],
        ];
    }
}

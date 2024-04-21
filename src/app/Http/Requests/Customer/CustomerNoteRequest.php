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
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string',
            'note_type' => 'required|string',
            'urgent' => 'required|boolean',
            'site_list' => 'required_if:note_type,site',
            'cust_equip_id' => 'required_if:note_type,equipment',
            'details' => 'required|string',
        ];
    }

    /**
     * Create a new customer note
     */
    public function createNote()
    {
        $this->addAttributes();
        $newNote = CustomerNote::create($this->except(['note_type', 'site_list']));

        if ($this->note_type === 'site') {
            $newNote->CustomerSite()->sync($this->site_list);
        }

        return $newNote;
    }

    /**
     * Update an existing note
     */
    public function updateNote()
    {
        $this->addAttributes();
        $this->note->update($this->except(['note_type', 'site_list']));

        $this->note->CustomerSite()->sync($this->site_list);

        return $this->note;
    }

    /**
     * Add additional attributes needed for the note
     */
    public function addAttributes()
    {
        $this->merge([
            'cust_id' => $this->customer->cust_id,
        ]);

        // If this is a new note, add created by, if existing add updated by
        if ($this->note) {
            $this->merge(['updated_by' => $this->user()->user_id]);
        } else {
            $this->merge(['created_by' => $this->user()->user_id]);
        }

        // If this is not an equipment note, remove equip_id
        if ($this->note_type !== 'equipment') {
            $this->cust_equip_id = null;
        }

        // If this is not a site note, remove any attached sites
        if ($this->note_type !== 'site') {
            $this->site_list = [];
        }
    }
}

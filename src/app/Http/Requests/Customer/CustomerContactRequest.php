<?php

namespace App\Http\Requests\Customer;

use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Models\PhoneNumberType;
use App\Service\PhoneNumberService;
use Illuminate\Foundation\Http\FormRequest;

class CustomerContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->contact) {
            return $this->user()->can('update', $this->contact);
        }

        return $this->user()->can('create', CustomerContact::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'title' => 'nullable|string',
            'email' => 'nullable|email',
            'sites' => 'nullable',
            'local' => 'required|boolean',
            'decision_maker' => 'required|boolean',
            'note' => 'nullable|string',
            'phones' => 'nullable|array',
        ];
    }

    /**
     * Build a new Customer Contact
     */
    public function createContact()
    {
        $this->merge(['cust_id' => $this->customer->cust_id]);

        $newContact = CustomerContact::create($this->except(['phones', 'site_list']));
        $newContact->CustomerSite()->sync($this->site_list);
        $this->updatePhoneNumbers($newContact);

        return $newContact;
    }

    /**
     * Create or update all phone numbers
     */
    protected function updatePhoneNumbers(CustomerContact $contact)
    {
        $phoneService = new PhoneNumberService;
        $phoneIdList = [];

        if ($this->phones) {
            foreach ($this->phones as $num) {
                if (isset($num['number'])) {
                    $phoneEntry = CustomerContactPhone::updateOrCreate([
                        'id' => $num['id'] ?? null,
                        'cont_id' => $contact->cont_id,
                    ], [
                        'phone_type_id' => $phoneService->getPhoneNumberType($num['type'])->phone_type_id,
                        'phone_number' => $phoneService->cleanPhoneString($num['number']),
                        'extension' => $num['ext'],
                    ]);

                    $phoneIdList[] = $phoneEntry->id;
                }
            }
        }

        // $contact->CustomerContactPhone()->sync($phoneIdList);
    }
}

<?php

namespace App\Http\Requests\Customers;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Models\PhoneNumberType;
use Illuminate\Foundation\Http\FormRequest;

class CustomerContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->route('contact')) {
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
            'cust_id' => 'required|exists:customers',
            'name' => 'required|string',
            'title' => 'nullable|string',
            'email' => 'nullable|email',
            'note' => 'nullable|string',
            'shared' => 'required|boolean',
            'phones' => 'nullable|array',
        ];
    }

    /**
     * Check if the item is shared, if so change the id field to be the parent id
     */
    public function checkForShared()
    {
        if ($this->shared) {
            $cust = Customer::find($this->cust_id);
            if ($cust->parent_id) {
                $this->merge(['cust_id' => $cust->parent_id]);
            }
        }
    }

    /**
     * Add phone numbers to the Customer Contact
     */
    public function processPhoneNumbers(int $contId, bool $isEdit = false): void
    {
        $existingPhones = $isEdit ? CustomerContactPhone::where('cont_id', $contId)->get()->pluck('id')->toArray() : [];
        if($this->phones)
        {

            foreach ($this->phones as $num) {
                //  Update or enter new number
                $type = PhoneNumberType::where('description', $num['type'])->first();
                if (isset($num['id']) && isset($num['number'])) {
                    $thisNum = CustomerContactPhone::find($num['id']);
                    $thisNum->update([
                        'phone_type_id' => $type->phone_type_id,
                        'phone_number' => $num['number'],
                        'extension' => $num['ext'],
                    ]);

                    $key = array_search($num['id'], $existingPhones);
                    unset($existingPhones[$key]);
                } else {
                    if (isset($num['number'])) {
                        CustomerContactPhone::create([
                            'cont_id' => $contId,
                            'phone_type_id' => $type->phone_type_id,
                            'phone_number' => $this->cleanPhoneNumber($num['number']),
                            'extension' => $num['ext'],
                        ]);
                    }
                }
            }
        }

        //  Remove any leftover numbers
        foreach ($existingPhones as $num) {
            CustomerContactPhone::find($num)->delete();
        }

    }

    /*
    *   Clean the phone number to be digits only
    */
    protected function cleanPhoneNumber($number): string
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
    }
}

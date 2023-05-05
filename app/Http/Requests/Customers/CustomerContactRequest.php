<?php

namespace App\Http\Requests\Customers;

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
        if($this->route('contact'))
        {
            return $this->user()->can('update', CustomerContact::find($this->route('contact')));
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
     * Add phone numbers to the Customer Contact
     */
    public function addPhoneNumbers($contId)
    {


            foreach($this->phones as $num)
            {
                $type = PhoneNumberType::where('description', $num['type'])->first();

                if(isset($num['number']))
                {

                    CustomerContactPhone::create([
                        'cont_id' => $contId,
                        'phone_type_id' => $type->phone_type_id,
                        'phone_number' => $this->cleanPhoneNumber($num['number']),
                        'extension' => $num['ext'],
                    ]);
                }
            }

    }

    /*
    *   Clean the phone number to be digits only
    */
    protected function cleanPhoneNumber($number)
    {
        return preg_replace('~.*(\d{3})[^\d]*(\d{3})[^\d]*(\d{4}).*~', '$1$2$3', $number);
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class CsrRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
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
            'country' => 'required|string',
            'state' => 'required|string',
            'locality' => 'required|string',
            'organization' => 'required|string',
            'ouName' => 'required|string',
            'common' => 'required|string',
            'email' => 'required|email',
        ];
    }

    /**
     * Generate a new private key and CSR request for SSL Certificate
     */
    public function processCsrRequest()
    {
        $newKey = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        $csrForm = [
            'countryName' => $this->country,
            'stateOrProvinceName' => $this->state,
            'localityName' => $this->locality,
            'organizationName' => $this->organization,
            'organizationalUnitName' => $this->ouName,
            'commonName' => $this->common,
            'emailAddress' => $this->email,
            'countryName' => 'US',
        ];

        $csr = openssl_csr_new($csrForm, $newKey, ['digest_alg' => 'sha256']);
        openssl_pkey_export($newKey, $pkeyOut);

        Storage::disk('security')->delete('private/server.key');
        Storage::disk('security')->put('private/server.key', $pkeyOut);

        openssl_csr_export($csr, $csrOut);

        return $csrOut;
    }
}

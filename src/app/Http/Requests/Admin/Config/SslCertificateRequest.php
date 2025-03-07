<?php

namespace App\Http\Requests\Admin\Config;

use App\Models\AppSettings;
use App\Services\Admin\CertificateService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class SslCertificateRequest extends FormRequest
{
    public function __construct(protected CertificateService $svc) {}

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', AppSettings::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'wildcard' => ['required', 'boolean'],
            'certificate' => ['required', 'string'],
            'key' => ['required_if:wildcard,true'],
            'intermediate' => ['required', 'string'],
        ];
    }

    /**
     * Validate that the certificate file is an actual ssl certificate
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $valid = $this->svc->checkUploadedCert($this->certificate);
                if (! $valid) {
                    $validator->errors()
                        ->add(
                            'certificate',
                            'This is not a valid certificate file'
                        );
                }

                $keyValid = $this->svc
                    ->validateKey($this->certificate, $this->key);
                if (! $keyValid) {
                    $validator->errors()
                        ->add(
                            'certificate',
                            'The Certificate and Key do not match'
                        );
                }
            },
        ];
    }
}

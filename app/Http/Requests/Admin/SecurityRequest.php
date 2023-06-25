<?php

namespace App\Http\Requests\Admin;

use App\Models\AppSettings;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\SslCertificate\SslCertificate;

class SecurityRequest extends FormRequest
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
            'wildcard' => 'required|boolean',
            'certificate' => 'required|string',
            'key' => 'required_if:wildcard,true',
            'intermediate' => 'required|string',
        ];
    }

    public function processCertificate()
    {
        $this->saveTmpFiles();

        try {
            $cert = SslCertificate::createFromFile(Storage::disk('security')->path('tmp/server.crt'));
        } catch (Exception $e) {
            Log::critical('SSL Certificate Upload failed - '.$e->getMessage());

            return back()->withErrors($e->getMessage());
        }

        $certData = [
            'is-valid' => $cert ? $cert->isValid() : false,
            'issuer' => $cert ? $cert->getIssuer() : null,
            'expires' => $cert ? $cert->expirationDate()->toFormattedDateString() : null,
            'signature' => $cert ? $cert->getSignatureAlgorithm() : null,
            'organization' => $cert ? $cert->getOrganization() : null,
        ];

        if (! $cert->isValid()) {
            Log::notice('An invalid SSL Certificate was uploaded.  See Details for more information', $certData);

            return back()->withErrors('The uploaded SSL Certificate is not valid.  No changes have been saved');
        }

        $this->moveTmpFiles();
        Log::notice('New SSL Certificate has been uploaded by '.$this->user()->username, $certData);

        return redirect(route('admin.security.index'))->with('success', 'Successfully loaded new SSL Certificate.  Please reboot for changes to take affect');
    }

    protected function saveTmpFiles()
    {
        Storage::disk('security')->put('tmp/server.crt', $this->certificate);
        Storage::disk('security')->put('tmp/intermediate.crt', $this->intermediate);

        if ($this->wildcard) {
            Storage::disk('security')->put('tmp/server.key', $this->key);
        }
    }

    protected function moveTmpFiles()
    {
        Storage::disk('security')->move('tmp/server.crt', 'server.crt');
        Storage::disk('security')->move('tmp/intermediate.crt', 'intermediate.crt');
        if ($this->wildcard) {
            Storage::disk('security')->move('tmp/server.key', 'server.key');
        }
    }
}

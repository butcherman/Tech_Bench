<?php

namespace App\Services\Admin;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\SslCertificate\SslCertificate;
use TypeError;

class CertificateService
{
    /**
     * The name that the server file is stored under
     *
     * @var string
     */
    protected $certFile = 'server.crt';

    /**
     * The name that the key file is stored under
     *
     * @var string
     */
    protected $keyFile = 'private/server.key';

    /**
     * The name that the intermediate file is stored under
     *
     * @var string
     */
    protected $intermediateFile = 'intermediate.crt';

    /**
     * Shortcut to the Storage Facade for easy disk access
     *
     * @var \Illuminate\Support\Facades\Storage
     */
    protected $storage;

    public function __construct()
    {
        $this->storage = Storage::disk('security');
    }

    /**
     * Return the contents of the current Certificate File
     */
    public function getCurrentCert(): ?string
    {
        return $this->storage->get($this->certFile);
    }

    /**
     * Verify that a key file exists
     */
    public function verifyKey(): bool
    {
        return $this->storage->has($this->keyFile);
    }

    /**
     * Verify that a key matches the certificate
     */
    public function validateKey(string $cert, ?string $key = null): bool
    {
        if (is_null($key)) {
            $key = $this->storage->get($this->keyFile);
        }

        Log::debug('Validating SSL Key');

        try {
            $certPubKeyResource = openssl_pkey_get_public($cert);
            $publicKey1 = trim(
                openssl_pkey_get_details($certPubKeyResource)['key']
            );

            $private_key = openssl_pkey_get_private($key);
            $publicKey2 = trim(openssl_pkey_get_details($private_key)['key']);

            $isValid = ! strcmp($publicKey1, $publicKey2);

            Log::debug('The SSL Key is ' . $isValid ? 'valid' : 'not valid');
        } catch (TypeError $e) {
            Log::debug('The provided SSL Key is not an Open SSL Key');
            Log::error('Error - ' . $e->getMessage());

            return false;
        }

        return $isValid;
    }

    /**
     * Build the meta data for the Certificate File
     */
    protected function buildCertMetaData(?string $cert = null)
    {
        if (is_null($cert)) {
            $cert = $this->getCurrentCert();
        }

        Log::debug('Generating SSL Certificate MetaData');

        try {
            $certData = SslCertificate::createFromString($cert);
        } catch (TypeError $e) {
            Log::critical(
                'The currently loaded SSL Certificate is not a valid SSL Certificate'
            );
            Log::debug($e->getMessage());

            return false;
        }

        return $certData;
    }

    /**
     * Return the meta data for the cert formatted for the HTML Page
     */
    public function getCertMetaData(?string $cert = null): array
    {
        $certData = $this->buildCertMetaData($cert);

        if (! $certData) {
            return ['Error' => 'Invalid Certificate File'];
        }

        return [
            'is_valid' => $certData->isValid(),
            'issuer' => $certData->getIssuer(),
            'issued_to' => $certData->getDomain(),
            'expires' => $certData->expirationDate()->toFormattedDateString(),
            'signature' => $certData->getSignatureAlgorithm(),
            'organization' => $certData->getOrganization(),
        ];
    }

    /**
     * Check a certificate to see if it is a valid certificate
     */
    public function checkUploadedCert(string $cert): bool
    {
        $data = collect($this->getCertMetaData($cert));

        // If the cert is not actually a certificate, fail
        if ($data->has('Error')) {
            return false;
        }

        // If the cert is not valid (i.e. expired), fail
        return $data->get('is_valid');
    }

    /**
     * Save uploaded certificate files
     */
    public function saveCertificateFiles(
        string $cert,
        string $intermediate,
        ?string $key = null
    ): void {
        $this->storage->put($this->certFile, $cert);
        $this->storage->put($this->intermediateFile, $intermediate);

        if (! is_null($key)) {
            $this->storage->put($this->keyFile, $key);
        }
    }

    /**
     * Generate a CSR Request and new Private Key
     */
    public function generateCsrRequest(Collection $requestData): string
    {
        $newKey = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        $csrForm = $requestData->all();

        $csr = openssl_csr_new($csrForm, $newKey, ['digest_alg' => 'sha256']);
        openssl_pkey_export($newKey, $pkeyOut);

        $this->storage->delete($this->keyFile);
        $this->storage->put($this->keyFile, $pkeyOut);

        openssl_csr_export($csr, $csrOut);

        return $csrOut;
    }

    /**
     * Remove the existing certificate and key
     */
    public function destroyCertificate(): void
    {
        $this->storage->delete($this->certFile);
        $this->storage->delete($this->keyFile);
        $this->storage->delete($this->intermediateFile);
    }
}

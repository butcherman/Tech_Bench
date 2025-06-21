<?php

namespace Tests\Unit\Jobs\Maintenance;

use App\Jobs\Maintenance\CheckSslCertificateJob;
use App\Mail\Admin\SslExpiredMail;
use App\Mail\Admin\SslExpiresSoonMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckSslCertificateJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle_no_issues(): void
    {
        Mail::fake();

        $this->generateCertificate(7);

        CheckSslCertificateJob::dispatch();

        Mail::assertNothingQueued();
    }

    public function test_handle_expires_soon(): void
    {
        Mail::fake();

        $this->generateCertificate(6);

        CheckSslCertificateJob::dispatch();

        Mail::assertQueued(SslExpiresSoonMail::class);
    }

    public function test_handle_expired(): void
    {
        Mail::fake();

        $this->generateCertificate(6);

        $this->travel(30)->days();

        CheckSslCertificateJob::dispatch();

        Mail::assertQueued(SslExpiredMail::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Methods
    |---------------------------------------------------------------------------
    */
    /**
     * Generate a fake SSL Certificate
     */
    protected function generateCertificate(int $daysValid): void
    {
        Storage::fake('security');

        //  Generate private key
        $key = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        //  Certificate Signing Request
        $dn = [
            'countryName' => 'US',
            'stateOrProvinceName' => 'CA',
            'localityName' => 'My Home USA',
            'organizationName' => 'Butcherman Unlimited',
            'organizationalUnitName' => 'Butcherman Unlimited',
            'commonName' => 'Its The Butcherman',
            'emailAddress' => 'butcherman@noem.com',
        ];
        $csr = openssl_csr_new($dn, $key, ['digest_alg' => 'sha256']);

        $cert = openssl_csr_sign(
            $csr,
            null,
            $key,
            $daysValid,
            ['digest_alg' => 'sha256']
        );

        openssl_x509_export($cert, $fullCert);
        openssl_pkey_export($key, $fullKey);

        Storage::disk('security')->put('server.crt', $fullCert);
        Storage::disk('security')->put('private/server.key', $fullKey);
    }
}

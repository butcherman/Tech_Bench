<?php

namespace Tests\Unit\Actions\Admin;

use App\Actions\Admin\CheckSslCertificate;
use App\Mail\Admin\SslExpiredMail;
use App\Mail\Admin\SslExpiresSoonMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckSslCertificateUnitTest extends TestCase
{
    protected $cert;

    protected $key;

    public function setUp(): void
    {
        parent::setUp();

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

        //  Cert is only valid for 120 days
        $cert = openssl_csr_sign(
            $csr,
            null,
            $key,
            31,
            ['digest_alg' => 'sha256']
        );

        openssl_x509_export($cert, $this->cert);
        openssl_pkey_export($key, $this->key);

        Storage::disk('security')->put('server.crt', $this->cert);
        Storage::disk('security')->put('private/server.key', $this->key);
    }

    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_thirty_days_out()
    {
        Mail::fake();

        $testObj = new CheckSslCertificate;
        $testObj();

        Mail::assertQueued(SslExpiresSoonMail::class);
    }

    public function test_invoke_twenty_days_out()
    {
        Mail::fake();

        $this->travel(10)->days();

        $testObj = new CheckSslCertificate;
        $testObj();

        Mail::assertNotQueued(SslExpiresSoonMail::class);
    }

    public function test_invoke_expired()
    {
        Mail::fake();

        $this->travel(40)->days();

        $testObj = new CheckSslCertificate;
        $testObj();

        Mail::assertQueued(SslExpiredMail::class);
    }
}

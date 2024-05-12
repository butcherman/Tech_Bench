<?php

namespace Tests\Feature\_Jobs\Admin;

use App\Jobs\Admin\CheckCertificateJob;
use App\Models\User;
use App\Notifications\Security\CertificateExpiredNotification;
use App\Notifications\Security\CertificateExpiresSoonNotification;
use App\Notifications\Security\CertificateInvalidNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckCertificateTest extends TestCase
{
    protected $cert;

    protected $key;

    /**
     * Setup will generate a self signed cert for testing
     */
    public function setUp(): void
    {
        parent::setUp();

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

        //  Cert is only valid for 91 days
        $cert = openssl_csr_sign($csr, null, $key, 120, ['digest_alg' => 'sha256']);
        openssl_x509_export($cert, $this->cert);
        openssl_pkey_export($key, $this->key);

        Storage::fake('security');
        Storage::disk('security')->put('server.crt', $this->cert);
        Storage::disk('security')->put('private/server.key', $this->key);
    }

    /**
     * Test Handle Job
     */
    public function test_job_no_problems()
    {
        Notification::fake();

        dispatch(new CheckCertificateJob());

        Notification::assertNothingSent();
    }

    public function test_job_expires_90_days()
    {
        Notification::fake();

        $this->travel(29)->days();

        dispatch(new CheckCertificateJob());

        Notification::assertSentTo(User::find(1), CertificateExpiresSoonNotification::class);
    }

    public function test_job_expires_60_days()
    {
        Notification::fake();

        $this->travel(59)->days();

        dispatch(new CheckCertificateJob());

        Notification::assertSentTo(User::find(1), CertificateExpiresSoonNotification::class);
    }

    public function test_job_expires_30_days()
    {
        Notification::fake();

        $this->travel(89)->days();

        dispatch(new CheckCertificateJob());

        Notification::assertSentTo(User::find(1), CertificateExpiresSoonNotification::class);
    }

    public function test_job_expires_16_days()
    {
        Notification::fake();

        $this->travel(90)->days();

        dispatch(new CheckCertificateJob());

        Notification::assertNothingSent();
    }

    public function test_job_expires_15_days()
    {
        Notification::fake();

        $this->travel(104)->days();

        dispatch(new CheckCertificateJob());

        Notification::assertSentTo(User::find(1), CertificateExpiresSoonNotification::class);
    }

    public function test_job_expired_cert()
    {
        Notification::fake();

        $this->travel(180)->days();

        dispatch(new CheckCertificateJob());

        Notification::assertSentTo(User::find(1), CertificateExpiredNotification::class);
    }

    public function test_job_invalid_cert()
    {
        Notification::fake();
        Storage::disk('security')->put('server.crt', 'some random text');

        $this->travel(180)->days();

        dispatch(new CheckCertificateJob());

        Notification::assertSentTo(User::find(1), CertificateInvalidNotification::class);
    }
}

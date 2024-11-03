<?php

namespace Tests\Unit\Services\Admin;

use App\Services\Admin\CertificateService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificateServiceUnitTest extends TestCase
{
    protected $cert;

    protected $key;

    protected $testObj;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('security');

        $this->testObj = new CertificateService;

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

        //  Cert is only valid for 5 days
        $cert = openssl_csr_sign(
            $csr,
            null,
            $key,
            5,
            ['digest_alg' => 'sha256']
        );

        openssl_x509_export($cert, $this->cert);
        openssl_pkey_export($key, $this->key);

        Storage::disk('security')->put('server.crt', $this->cert);
        Storage::disk('security')->put('private/server.key', $this->key);
    }

    /*
    |---------------------------------------------------------------------------
    | getCurrentCert()
    |---------------------------------------------------------------------------
    */
    public function test_get_current_cert(): void
    {
        $shouldBe = $this->cert;

        $res = $this->testObj->getCurrentCert();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | verifyKey()
    |---------------------------------------------------------------------------
    */
    public function test_verify_key_missing(): void
    {
        Storage::disk('security')->delete('private/server.key');

        $res = $this->testObj->verifyKey();

        $this->assertFalse($res);
    }

    public function test_verify_key(): void
    {
        $res = $this->testObj->verifyKey();

        $this->assertTrue($res);
    }

    /*
    |---------------------------------------------------------------------------
    | validateKey()
    |---------------------------------------------------------------------------
    */
    public function test_validate_key(): void
    {
        $res = $this->testObj->validateKey($this->cert, $this->key);

        $this->assertTrue($res);
    }

    public function test_validate_key_use_loaded_key(): void
    {
        $res = $this->testObj->validateKey($this->cert);

        $this->assertTrue($res);
    }

    public function test_validate_key_mismatch(): void
    {
        //  Generate new private key
        $key = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        openssl_pkey_export($key, $newKey);

        $res = $this->testObj->validateKey($this->cert, $newKey);

        $this->assertFalse($res);
    }

    public function test_validate_key_use_random_string(): void
    {
        $res = $this->testObj->validateKey($this->cert, 'no good key');

        $this->assertFalse($res);
    }

    /*
    |---------------------------------------------------------------------------
    | getCertMetaData()
    |---------------------------------------------------------------------------
    */
    public function test_get_cert_meta_data(): void
    {
        $res = $this->testObj->getCertMetaData();

        $this->assertArrayHasKey('is_valid', $res);
        $this->assertArrayHasKey('issuer', $res);
        $this->assertArrayHasKey('issued_to', $res);
        $this->assertArrayHasKey('expires', $res);
        $this->assertArrayHasKey('signature', $res);
        $this->assertArrayHasKey('organization', $res);
    }

    public function test_get_cert_meta_data_invalid(): void
    {
        $res = $this->testObj->getCertMetaData('random key for cert');

        $this->assertArrayHasKey('Error', $res);
    }

    /*
    |---------------------------------------------------------------------------
    | checkUploadedCert()
    |---------------------------------------------------------------------------
    */
    public function test_check_uploaded_cert(): void
    {
        $res = $this->testObj->checkUploadedCert($this->cert);

        $this->assertTrue($res);
    }

    public function test_check_uploaded_cert_bad_cert(): void
    {
        $res = $this->testObj->checkUploadedCert('random string...');

        $this->assertFalse($res);
    }

    public function test_check_uploaded_cert_expired_cert(): void
    {
        $this->travel(30)->days();

        $res = $this->testObj->checkUploadedCert($this->cert);

        $this->assertFalse($res);
    }

    /*
    |---------------------------------------------------------------------------
    | saveCertificateFiles()
    |---------------------------------------------------------------------------
    */
    public function test_save_certificate_files(): void
    {
        Storage::disk('security')->delete('server.crt');
        Storage::disk('security')->delete('private/server.key');

        // Make sure the cert files are gone.
        Storage::disk('security')->assertMissing('server.crt');
        Storage::disk('security')->assertMissing('intermediate.crt');
        Storage::disk('security')->assertMissing('private/server.key');

        $this->testObj->saveCertificateFiles($this->cert, $this->cert, $this->key);

        // Files should exist again.
        Storage::disk('security')->assertExists('server.crt');
        Storage::disk('security')->assertExists('intermediate.crt');
        Storage::disk('security')->assertExists('private/server.key');
    }

    /*
    |---------------------------------------------------------------------------
    | generateCsrRequest()
    |---------------------------------------------------------------------------
    */
    public function test_generate_csr_request(): void
    {
        $data = [
            'countryName' => 'US',
            'stateOrProvinceName' => 'CA',
            'localityName' => 'My Home USA',
            'organizationName' => 'Butcherman Unlimited',
            'organizationalUnitName' => 'Butcherman Unlimited',
            'commonName' => 'Its The Butcherman',
            'emailAddress' => 'butcherman@noem.com',
        ];

        $res = $this->testObj->generateCsrRequest(collect($data));

        $this->assertMatchesRegularExpression('/BEGIN CERTIFICATE REQUEST/', $res);
        $this->assertMatchesRegularExpression('/END CERTIFICATE REQUEST/', $res);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyCertificate()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_certificate(): void
    {
        $this->testObj->destroyCertificate();

        Storage::disk('security')->assertMissing('server.crt');
        Storage::disk('security')->assertMissing('intermediate.crt');
        Storage::disk('security')->assertMissing('private/server.key');
    }
}

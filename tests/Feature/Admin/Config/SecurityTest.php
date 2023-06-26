<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    protected $cert;
    protected $key;
    protected $intermediate;

    /**
     * Setup will generate a self signed cert for testing with
     */
    public function setUp():void
    {
        parent::setUp();

        //  Generate private key
        $this->key = openssl_pkey_new(array(
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ));

        //  Certificate Signing Request
        $dn = array(
            'countryName' => 'US',
            'stateOrProvinceName' => 'CA',
            'localityName' => 'My Home USA',
            'organizationName' => 'Butcherman Unlimited',
            'organizationalUnitName' => 'Butcherman Unlimited',
            'commonName' => 'Its The Butcherman',
            'emailAddress' => 'butcherman@noem.com',
        );
        $csr = openssl_csr_new($dn, $this->key, array('digest_alg' => 'sha256'));

        //  Cert is only valid for 5 days
        $this->cert = openssl_csr_sign($csr, null, $this->key, 5, array('digest_alg' => 'sha256'));
    }

    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.security.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.security.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.security.index'));
        $response->assertSuccessful();
    }

    public function test_index_missing_cert()
    {
        Storage::fake('security');
        Storage::disk('security')->makeDirectory('private');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.security.index'));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('admin.security.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.security.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.security.create'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $response = $this->post(route('admin.security.store'), ['data' => 'blah']);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->post(route('admin.security.store'), ['data' => 'blah']);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        Storage::fake();

        openssl_x509_export($this->cert, $certOut);
        openssl_pkey_export($this->key, $pkeyOut);

        $data = [
            'wildcard' => true,
            'certificate' => $certOut,
            'key' => $pkeyOut,
            'intermediate' => $certOut,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.security.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Successfully loaded new SSL Certificate.  Please reboot for changes to take affect');
        Storage::disk('security')->assertExists('server.crt');
        Storage::disk('security')->assertExists('private/server.key');
    }

    public function test_store_invalid_cert()
    {
        $data = [
            'wildcard' => true,
            'certificate' => 'blah blah not a real cert',
            'key' => 'blah blah not a real key',
            'intermediate' => 'something else here...',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.security.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['failed' => 'openssl_x509_fingerprint(): X.509 Certificate cannot be retrieved']);
    }

    public function test_store_expired_cert()
    {
        Storage::fake();

        openssl_x509_export($this->cert, $certOut);
        openssl_pkey_export($this->key, $pkeyOut);

        $data = [
            'wildcard' => true,
            'certificate' => $certOut,
            'key' => $pkeyOut,
            'intermediate' => $certOut,
        ];

        Carbon::setTestNow(Carbon::now()->addDays(15));

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.security.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['invalid' => 'The uploaded SSL Certificate is not valid.  No changes have been saved']);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $response = $this->delete(route('admin.security.destroy'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->delete(route('admin.security.destroy'));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        Storage::fake();

        openssl_x509_export($this->cert, $certOut);
        openssl_pkey_export($this->key, $pkeyOut);

        Storage::disk('security')->put('server.crt', $certOut);
        Storage::disk('security')->put('private/server.key', $pkeyOut);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.security.destroy'));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', 'SSL Certificate Deleted.  Please reboot for changes to take affect');
        Storage::disk('security')->assertMissing('server.crt');
        Storage::disk('security')->assertMissing('private/server.key');
    }
}

<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    protected $cert, $key, $intermediate;

    /**
     * Setup will generate a self signed cert for testing with
     */
    public function setUp(): void
    {
        parent::setUp();

        //  Generate private key
        $this->key = openssl_pkey_new([
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
        $csr = openssl_csr_new($dn, $this->key, ['digest_alg' => 'sha256']);

        //  Cert is only valid for 5 days
        $this->cert = openssl_csr_sign($csr, null, $this->key, 5, ['digest_alg' => 'sha256']);
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
        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.security.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.security.index'));
        $response->assertSuccessful();
    }

    public function test_index_missing_cert()
    {
        Storage::fake('security');
        Storage::disk('security')->makeDirectory('private');

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.security.index'));
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
        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.security.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.security.create'));
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
        $response = $this->actingAs(User::factory()->create())
            ->post(route('admin.security.store'), ['data' => 'blah']);
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

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->post(route('admin.security.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.security.updated'));

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

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->post(route('admin.security.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['cert_error']);
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

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->post(route('admin.security.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['cert_error']);
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $response = $this->get(route('admin.security.edit', 'csr-request'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.security.edit', 'csr-request'));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.security.edit', 'csr-request'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
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

        $response = $this->put(route('admin.security.update', 'csr-request'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
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

        $response = $this->actingAs(User::factory()->create())
            ->put(route('admin.security.update', 'csr-request'), $data);
        $response->assertStatus(403);
    }

    public function test_update()
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

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('admin.security.update', 'csr-request'), $data);
        $response->assertSuccessful();
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $response = $this->delete(route('admin.security.destroy', 'cert'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())
            ->delete(route('admin.security.destroy', 'cert'));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        Storage::fake();

        openssl_x509_export($this->cert, $certOut);
        openssl_pkey_export($this->key, $pkeyOut);

        Storage::disk('security')->put('server.crt', $certOut);
        Storage::disk('security')->put('private/server.key', $pkeyOut);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('admin.security.destroy', 'cert'));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('admin.security.deleted'));

        Storage::disk('security')->assertMissing('server.crt');
        Storage::disk('security')->assertMissing('private/server.key');
    }
}

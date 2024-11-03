<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use OpenSSLAsymmetricKey;
use OpenSSLCertificate;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    /** @var OpenSSLCertificate */
    protected $cert;

    /** @var OpenSSLAsymmetricKey */
    protected $key;

    /** @var OpenSSLCertificate */
    protected $intermediate;

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
        $this->cert = openssl_csr_sign(
            $csr,
            null,
            $this->key,
            5,
            ['digest_alg' => 'sha256']
        );
    }

    /**
     * Index Method
     */
    public function test_index_guest(): void
    {
        $response = $this->get(route('admin.security.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.security.index'));

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.security.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Security/Index')
                ->has('cert')
                ->has('data')
            );
    }

    public function test_index_missing_cert(): void
    {
        Storage::fake('security');
        Storage::disk('security')->makeDirectory('private');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.security.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Security/Index')
                ->has('cert')
                ->has('data')
            );
    }

    /**
     * Create Method
     */
    public function test_create_guest(): void
    {
        $response = $this->get(route('admin.security.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.security.create'));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.security.create'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Security/Create')
                ->has('has-key')
            );
    }

    /**
     * Store Method
     */
    public function test_store_guest(): void
    {
        $response = $this->post(
            route('admin.security.store'),
            ['data' => 'blah']
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->post(route('admin.security.store'), ['data' => 'blah']);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        Storage::fake('security');

        openssl_x509_export($this->cert, $certOut);
        openssl_pkey_export($this->key, $pkeyOut);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'wildcard' => true,
            'certificate' => $certOut,
            'key' => $pkeyOut,
            'intermediate' => $certOut,
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.security.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.security.updated'));

        Storage::disk('security')->assertExists('server.crt');
        Storage::disk('security')->assertExists('private/server.key');
    }

    public function test_store_invalid_cert(): void
    {
        Storage::fake('security');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'wildcard' => true,
            'certificate' => 'blah blah not a real cert',
            'key' => 'blah blah not a real key',
            'intermediate' => 'something else here...',
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.security.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['certificate']);
    }

    public function test_store_expired_cert(): void
    {
        Storage::fake('security');

        openssl_x509_export($this->cert, $certOut);
        openssl_pkey_export($this->key, $pkeyOut);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'wildcard' => true,
            'certificate' => $certOut,
            'key' => $pkeyOut,
            'intermediate' => $certOut,
        ];

        Carbon::setTestNow(Carbon::now()->addDays(15));

        $response = $this->actingAs($user)
            ->post(route('admin.security.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['certificate']);
    }

    /**
     * Edit Method
     */
    public function test_edit_guest(): void
    {
        $response = $this->get(route('admin.security.edit', 'csr-request'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.security.edit', 'csr-request'));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.security.edit', 'csr-request'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Security/Edit')
            );
    }

    /**
     * Update Method
     */
    public function test_update_guest(): void
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

        $response = $this->put(
            route('admin.security.update', 'csr-request'),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'countryName' => 'US',
            'stateOrProvinceName' => 'CA',
            'localityName' => 'My Home USA',
            'organizationName' => 'Butcherman Unlimited',
            'organizationalUnitName' => 'Butcherman Unlimited',
            'commonName' => 'Its The Butcherman',
            'emailAddress' => 'butcherman@noem.com',
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.security.update', 'csr-request'), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        Storage::fake('security');

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'countryName' => 'US',
            'stateOrProvinceName' => 'CA',
            'localityName' => 'My Home USA',
            'organizationName' => 'Butcherman Unlimited',
            'organizationalUnitName' => 'Butcherman Unlimited',
            'commonName' => 'Its The Butcherman',
            'emailAddress' => 'butcherman@noem.com',
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.security.update', 'csr-request'), $data);

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Security/Edit')
                ->has('csr-request')
            );
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest(): void
    {
        $response = $this->delete(route('admin.security.destroy', 'cert'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->delete(route('admin.security.destroy', 'cert'));

        $response->assertForbidden();
    }

    public function test_destroy(): void
    {
        Storage::fake('security');

        openssl_x509_export($this->cert, $certOut);
        openssl_pkey_export($this->key, $pkeyOut);

        Storage::disk('security')->put('server.crt', $certOut);
        Storage::disk('security')->put('private/server.key', $pkeyOut);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->delete(route('admin.security.destroy', 'cert'));

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('admin.security.deleted'));

        Storage::disk('security')->assertMissing('server.crt');
        Storage::disk('security')->assertMissing('intermediate.crt');
        Storage::disk('security')->assertMissing('private/server.key');
    }
}

<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use App\Services\File\TusUploadService;
use ArthurPatriot\Tus\Helpers\TusFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Validation\ValidationException;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery\MockInterface;
use Tests\TestCase;

class LogoTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $response = $this->get(route('admin.logo.edit'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.logo.edit'));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.logo.edit'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Config/Logo'));
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $tusFile = new TusFile(
            id: 'test-upload',
            path: 'logo.png',
            metadata: [
                'name' => 'logo.png',
                'purpose' => 'logo',
                'extension' => 'png',
            ],
        );

        $data = [
            'upload_id' => $tusFile->id,
        ];

        $response = $this->post(route('admin.logo.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        $tusFile = new TusFile(
            id: 'test-upload',
            path: 'logo.png',
            metadata: [
                'name' => 'logo.png',
                'purpose' => 'logo',
                'extension' => 'png',
            ],
        );

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $data = [
            'upload_id' => $tusFile->id,
        ];

        $response = $this->actingAs($user)
            ->post(route('admin.logo.update'), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        $tusFile = new TusFile(
            id: 'test-upload',
            path: 'logo.png',
            metadata: [
                'name' => 'logo.png',
                'purpose' => 'logo',
                'extension' => 'png',
            ],
        );

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $data = [
            'upload_id' => $tusFile->id,
        ];

        $this->mock(TusUploadService::class, function (MockInterface $mock) use ($tusFile) {
            $mock->shouldReceive('getCompletedUPload')->once()->andReturn($tusFile);
            $mock->shouldReceive('validateMimeType')->once()->andReturn(true);
            $mock->shouldReceive('finalizeUpload')->once();
        });

        $response = $this->actingAs($user)
            ->post(route('admin.logo.update'), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.logo',
        ]);
    }

    public function test_update_invalid_mime(): void
    {
        Exceptions::fake();

        $tusFile = new TusFile(
            id: 'test-upload',
            path: 'logo.txt',
            metadata: [
                'name' => 'logo.txt',
                'purpose' => 'logo',
                'extension' => 'txt',
            ],
        );

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $data = [
            'upload_id' => $tusFile->id,
        ];

        $this->mock(TusUploadService::class, function (MockInterface $mock) use ($tusFile) {
            $mock->shouldReceive('getCompletedUPload')->once()->with($tusFile->id)->andReturn($tusFile);
            $mock->shouldReceive('validateMimeType')->once()->andReturn(false);
            $mock->shouldReceive('deleteUpload')->once()->with($tusFile);
            $mock->shouldNotReceive('finalizeUpload');
        });

        $this->expectException(ValidationException::class);

        $response = $this->withoutExceptionHandling()->actingAs($user)
            ->post(route('admin.logo.update'), $data);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('app_settings', [
            'key' => 'app.logo',
        ]);

        Exceptions::assertReported(ValidationException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        DB::table('app_settings')->insert([
            'key' => 'app.logo',
            'value' => json_decode('/img/test.png'),
        ]);

        $response = $this->delete(route('admin.logo.destroy'));
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        DB::table('app_settings')->insert([
            'key' => 'app.logo',
            'value' => json_decode('/img/test.png'),
        ]);

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('admin.logo.destroy'));
        $response->assertForbidden();
    }

    public function test_destroy(): void
    {
        DB::table('app_settings')->insert([
            'key' => 'app.logo',
            'value' => json_decode('/img/test.png'),
        ]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->actingAs($user)->delete(route('admin.logo.destroy'));
        $response->assertStatus(302)
            ->assertSessionHas('success', 'Logo Deleted');

        $this->assertDatabaseMissing('app_settings', [
            'key' => 'app.logo',
        ]);
    }
}

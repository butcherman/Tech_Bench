<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SetLogoTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        Storage::fake('public');
        $data = ['logo' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->post(route('admin.set-logo'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        Storage::fake('public');
        $data = ['logo' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.set-logo'), $data);
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        Storage::fake('public');
        $data = ['logo' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.set-logo'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.logo',
        ]);
    }
}

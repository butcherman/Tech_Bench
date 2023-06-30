<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LogoTest extends TestCase
{
    /**
     * Get Method
     */
    public function test_get_guest()
    {
        $response = $this->get(route('admin.logo.get'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_get_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.logo.get'));
        $response->assertStatus(403);
    }

    public function test_get()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.logo.get'));
        $response->assertSuccessful();
    }

    /**
     * Set Method
     */
    public function test_set_guest()
    {
        Storage::fake('public');
        $data = ['logo' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->post(route('admin.logo.set'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_set_no_permission()
    {
        Storage::fake('public');
        $data = ['logo' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.logo.set'), $data);
        $response->assertStatus(403);
    }

    public function test_set()
    {
        Storage::fake('public');
        $data = ['logo' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.logo.set'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'app.logo',
        ]);
    }
}

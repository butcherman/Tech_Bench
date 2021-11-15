<?php

namespace Tests\Feature\Admin\Logo;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        $data = ['file' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->get(route('admin.set-logo'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        Storage::fake('public');
        $data = ['file' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->actingAs(User::factory()->create())->get(route('admin.set-logo'), $data);
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        Storage::fake('public');
        $data = ['file' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.set-logo'), $data);
        $response->assertSuccessful();
        // $this->assertDatabaseHas('app_settings', [
        //     'key' => 'app.logo'
        // ]);
    }
}

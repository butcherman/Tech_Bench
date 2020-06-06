<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingsControllerTest extends TestCase
{
    public function test_submit_logo_guest()
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('newLogo.png');
        $response = $this->post(route('settings.submit_logo'), ['file' => $logo]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_logo_no_permission()
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('newLogo.png');
        $response = $this->actingAs($this->getTech())->post(route('settings.submit_logo'), ['file' => $logo]);

        $response->assertStatus(403);
    }

    public function test_submit_logo()
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('newLogo.png');
        $response = $this->actingAs($this->getInstaller())->post(route('settings.submit_logo'), ['file' => $logo]);

        $response->assertJson(['url' => config('app.url').'/storage/images/logo/newLogo.png']);
    }
}

<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingsControllerTest extends TestCase
{


    public function test_settings_form_guest()
    {
        $response = $this->get(route('settings.general'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_settings_form_no_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('settings.general'));
        $response->assertStatus(403);
    }

    public function test_settings_form()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('settings.general'));
        $response->assertSuccessful();
        $response->assertViewIs('settings.generalSettings');
    }

    public function test_submit_settings_guest()
    {
        $data = [
            'timezone' => 'America\Los_Angeles',
            'filesize' => 5000,
        ];

        $response = $this->post(route('settings.submit_general'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_settings_no_permission()
    {
        $data = [
            'timezone' => 'America\Los_Angeles',
            'filesize' => 5000,
        ];

        $response = $this->actingAs($this->getTech())->post(route('settings.submit_general'), $data);
        $response->assertStatus(403);
    }

    public function test_submit_settings()
    {
        $data = [
            'timezone' => 'America\Los_Angeles',
            'filesize' => 5000,
        ];

        $response = $this->actingAs($this->getInstaller())->post(route('settings.submit_general'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

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

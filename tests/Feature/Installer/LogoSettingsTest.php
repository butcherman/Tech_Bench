<?php

namespace Tests\Feature\Installer;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class LogoSettingsTest extends TestCase
{
    public function test_visit_logo_settings_page_as_guest()
    {
        $response = $this->get(route('admin.logoSettings'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_logo_settings_page_user_without_permission()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.logoSettings'));

        $response->assertStatus(403);
    }

    public function test_visit_logo_settings_page_as_installer()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.logoSettings'));

        $response->assertSuccessful();
        $response->assertViewIs('installer.logoSettings');
    }

    public function test_submit__new_logo_as_guest()
    {
        $fileName = Str::random(5) . '.jpg';
        $data = [
            'file' => $file = UploadedFile::fake()->image($fileName)
        ];
        $response = $this->post(route('admin.submitLogo'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit__new_logo_user_without_permission()
    {
        $fileName = Str::random(5) . '.jpg';
        $data = [
            'file' => $file = UploadedFile::fake()->image($fileName)
        ];
        $response = $this->actingAs($this->getTech())->post(route('admin.submitLogo'), $data);

        $response->assertStatus(403);
    }

    public function test_submit__new_logo_as_installer()
    {
        $fileName = Str::random(5) . '.jpg';
        $data = [
            'file' => $file = UploadedFile::fake()->image($fileName)
        ];
        $response = $this->actingAs($this->getInstaller())->post(route('admin.submitLogo'), $data);

        $response->assertSuccessful();
        $response->assertJson(['url' => '/storage/img/'.$fileName]);
    }
}

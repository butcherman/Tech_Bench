<?php

namespace Tests\Feature\Installer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConfigurationModificationTest extends TestCase
{

    public function test_visit_settings_page_as_guest()
    {
        $response = $this->get(route('admin.config'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_settings_page_as_regular_user()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.config'));

        $response->assertStatus(403);
    }

    public function test_visit_settings_page()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.config'));

        $response->assertSuccessful();
        $response->assertViewIs('installer.configuration');
    }

    public function test_submit_settings_as_guest()
    {
        $data = [
            'url'      => 'https://newurl.com',
            'timezone' => 'America/Guyana',
            'filesize' => 500,
        ];
        $response = $this->post(route('admin.submitConfig'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_settings_as_regular_user()
    {
        $data = [
            'url'      => 'https://newurl.com',
            'timezone' => 'America/Guyana',
            'filesize' => 500,
        ];
        $response = $this->actingAs($this->getTech())->post(route('admin.submitConfig'), $data);

        $response->assertStatus(403);
    }

    public function test_submit_settings()
    {
        $data = [
            'url'      => 'https://newurl.com',
            'timezone' => 'America/Guyana',
            'filesize' => 500,
        ];
        $response = $this->actingAs($this->getInstaller())->post(route('admin.submitConfig'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }
}

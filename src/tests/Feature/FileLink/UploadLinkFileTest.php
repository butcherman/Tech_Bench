<?php

namespace Tests\Feature\FileLink;

use App\Models\FileLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UploadLinkFileTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        config(['file-link.feature_enabled' => true]);

        $link = FileLink::factory()->create();
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->post(route('links.upload', $link->link_hash), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_feature_disabled(): void
    {
        config(['file-link.feature_enabled' => false]);

        /** @var User $user */
        $user = User::factory()->create();
        $link = FileLink::factory()->create();
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('links.upload', $link->link_hash), $data);

        $response->assertForbidden();
    }

    public function test_invoke(): void
    {
        config(['file-link.feature_enabled' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $link = FileLink::factory()->create();
        $data = [
            'link_name' => 'Test Link',
            'expire' => '2030-12-12',
            'allow_upload' => true,
            'instructions' => 'Here are some instructions',
            'file' => UploadedFile::fake()->image('testPhoto.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('links.upload', $link->link_hash), $data);

        $response->assertSuccessful()->assertSessionHas('link-file');
    }
}

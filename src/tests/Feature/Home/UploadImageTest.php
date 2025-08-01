<?php

namespace Tests\Feature\Home;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadImageTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        Storage::fake('public');
        $data = ['file' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->post(route('upload-image'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        Storage::fake('public');

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = ['file' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->actingAs($user)
            ->post(route('upload-image'), $data);

        $response->assertSuccessful();

        $file = last(explode('/', json_decode($response->getContent())->location));

        Storage::disk('public')->assertExists('images/uploaded/'.$file);
    }
}

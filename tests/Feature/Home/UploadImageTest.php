<?php

namespace Tests\Feature\Home;

use Tests\TestCase;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class UploadImageTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        Storage::fake('public');
        $data = ['file' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->post(route('upload-image'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        Storage::fake('public');
        $data = ['file' => UploadedFile::fake()->image('testPhoto.png')];

        $response = $this->actingAs(User::factory()->create())->post(route('upload-image'), $data);
        $response->assertSuccessful();

        $file = last(explode('/', $response->getContent()));
        Storage::disk('public')->assertExists('images/uploaded/'.$file);
    }
}

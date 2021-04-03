<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    protected $user;

    public function setup():void
    {
        Parent::setup();

        $this->user = User::factory()->create();
    }

    public function test_edit_settings_form_guest()
    {
        $response = $this->get(route('settings.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_edit_settings_form()
    {
        $response = $this->actingAs($this->user)->get(route('settings.index'));
        $response->assertSuccessful();
    }

    public function test_submit_settings_guest()
    {
        $form = User::factory()->make();

        $response = $this->put(route('settings.update', $this->user->user_id), $form->toArray());
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_submit_settings()
    {
        $form = User::factory()->make();

        $response = $this->actingAs($this->user)
                        ->put(route('settings.update', $this->user->user_id), $form->only(['first_name', 'last_name', 'email']));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Account Settings Updated']);
        $this->assertDatabaseHas('users', ['user_id' => $this->user->user_id, 'first_name' => $form->first_name, 'last_name' => $form->last_name]);
    }

    public function test_submit_someone_elses_settings()
    {
        $anotherUser = User::factory()->create();
        $form = User::factory()->make();

        $response = $this->actingAs($this->user)
                        ->put(route('settings.update', $anotherUser->user_id), $form->only(['first_name', 'last_name', 'email']));
        $response->assertStatus(403);
    }
}

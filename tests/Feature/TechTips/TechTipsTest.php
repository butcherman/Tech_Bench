<?php

namespace Tests\Feature\TechTips;

use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Models\User;
use App\Models\UserRolePermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TechTipsTest extends TestCase
{
    /*
    *   Index Method
    */
    public function test_index_guest()
    {
        $response = $this->get(route('tech-tips.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('tech-tips.index'));
        $response->assertSuccessful();
    }

    /*
    *   Create Method
    */
    public function test_create_guest()
    {
        $response = $this->get(route('tech-tips.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_create_no_permission()
    {
        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 23)->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())->get(route('tech-tips.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('tech-tips.create'));
        $response->assertSuccessful();
    }

    /*
    *   Store Method
    */
    public function test_store_guest()
    {
        $data = TechTip::factory()->make();

        $response = $this->post(route('tech-tips.store'), $data->only(['subject', 'tip_type_id', 'details', 'noEmail', 'sticky', 'equipment']));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_store_no_permission()
    {
        UserRolePermissions::where('role_id', 4)->where('perm_type_id', 23)->update(['allow' => false]);
        $data = TechTip::factory()->make();

        $response = $this->actingAs(User::factory()->create())->post(route('tech-tips.store'), $data->only(['subject', 'tip_type_id', 'details', 'noEmail', 'sticky', 'equipment']));
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $tip = TechTip::factory()->make();
        $data = [
            'subject'     => $tip->subject,
            'tip_type_id' => $tip->tip_type_id,
            'details'     => $tip->details,
            'noEmail'     => false,
            'sticky'      => false,
            'equipment'   => [EquipmentType::factory()->create()],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('tech-tips.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('tech_tips', $tip->only(['subject', 'details']));
    }

    /*
    *   Show Method
    */
    public function test_show_guest()
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tech-tips.show', $tip->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_show()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('tech-tips.show', $tip->slug));
        $response->assertSuccessful();
    }

    public function test_show_with_id()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('tech-tips.show', $tip->tip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('tech-tips.show', $tip->slug));
    }
}

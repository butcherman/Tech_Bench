<?php

namespace Tests\Feature\TechTips;

use App\Models\EquipmentType;
use App\Models\TechTip;
use App\Models\User;
use App\Models\UserRolePermissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class TechTipsTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('tech-tips.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('tech-tips.index'));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('tech-tips.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        //  Remove the 'Add Tech Tip' permission from the Tech Role
        UserRolePermissions::whereRoleId(4)->wherePermTypeId(23)->update([
            'allow' => false,
        ]);

        $response = $this->actingAs(User::factory()->create())->get(route('tech-tips.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('tech-tips.create'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = TechTip::factory()->make()->only(['subject', 'tip_type_id', 'details', 'noEmail', 'sticky', 'equipment']);

        $response = $this->post(route('tech-tips.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        //  Remove the 'Add Tech Tip' permission from the Tech Role
        UserRolePermissions::whereRoleId(4)->wherePermTypeId(23)->update([
            'allow' => false,
        ]);
        $data = TechTip::factory()->make()->only(['subject', 'tip_type_id', 'details', 'sticky', 'equipment']);
        $data['no_email'] = false;

        $response = $this->actingAs(User::factory()->create())->post(route('tech-tips.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = TechTip::factory()->make()->only(['subject', 'tip_type_id', 'details', 'noEmail', 'sticky', 'equipment']);
        $data['noEmail']   = false;
        $data['equipment'] = [EquipmentType::factory()->create()];
        $slug = Str::slug($data['subject']);

        $response = $this->actingAs(User::factory()->create())->post(route('tech-tips.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('tech-tips.show', $slug));
        $this->assertDatabaseHas('tech_tips', [
            'subject'     => $data['subject'],
            'tip_type_id' => $data['tip_type_id'],
            'details'     => $data['details'],
            'sticky'      => $data['sticky'],
        ]);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tech-tips.show', $tip->slug));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('tech-tips.show', $tip->slug));
        $response->assertSuccessful();
    }

    public function test_show_with_tip_id()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('tech-tips.show', $tip->tip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('tech-tips.show', $tip->slug));
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $tip = TechTip::factory()->create();

        $response = $this->get(route('tech-tips.edit', $tip->tip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('tech-tips.edit', $tip->tip_id));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('tech-tips.edit', $tip->tip_id));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $tip  = TechTip::factory()->create();
        $data = TechTip::factory()->make()->only(['subject', 'tip_type_id', 'details', 'sticky', 'equipment']);
        $data['noEmail']   = false;
        $data['equipment'] = [EquipmentType::factory()->create()];

        $response = $this->put(route('tech-tips.update', $tip->tip_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $tip  = TechTip::factory()->create();
        $data = TechTip::factory()->make()->only(['subject', 'tip_type_id', 'details', 'sticky', 'equipment']);
        $data['noEmail']   = false;
        $data['equipment'] = [EquipmentType::factory()->create()];

        $response = $this->actingAs(User::factory()->create())->put(route('tech-tips.update', $tip->tip_id), $data);
        $response->assertStatus(403);
        $this->assertDatabaseHas('tech_tips', $tip->only(['tip_id', 'subject', 'tip_type_id', 'details', 'sticky']));
    }

    public function test_update()
    {
        $tip  = TechTip::factory()->create();
        $data = TechTip::factory()->make()->only(['subject', 'tip_type_id', 'details', 'sticky', 'equipment']);
        $data['resendNotif']  = false;
        $data['equipment']    = [EquipmentType::factory()->create()];
        $data['fileList']     = [];
        $data['removedFiles'] = [];
        $slug = Str::slug($data['subject']);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('tech-tips.update', $tip->tip_id), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('tech-tips.show', $slug));
        $this->assertDatabaseHas('tech_tips', [
            'tip_id' => $tip->tip_id,
            'subject'     => $data['subject'],
            'tip_type_id' => $data['tip_type_id'],
            'details'     => $data['details'],
            'sticky'      => $data['sticky'],
        ]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $tip = TechTip::factory()->create();

        $response = $this->delete(route('tech-tips.destroy', $tip->tip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('tech-tips.destroy', $tip->tip_id));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $tip = TechTip::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('tech-tips.destroy', $tip->tip_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('tech-tips.index'));
        $this->assertSoftDeleted('tech_tips', $tip->only(['tip_id', 'subject', 'details']));
    }
}

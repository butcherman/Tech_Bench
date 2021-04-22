<?php

namespace Tests\Feature\Equip;

use Tests\TestCase;

use App\Models\User;
use App\Models\EquipmentType;
use App\Models\EquipmentCategory;

class EquipmentListTest extends TestCase
{
    /*
    *   __invoke Method
    */
    public function test_invoke_guest()
    {
        $cat = EquipmentCategory::factory()->count(3)->create();
        $equip = [];
        foreach($cat as $c)
        {
            $equip[] = EquipmentType::factory()->count(3)->create(['cat_id' => $c->cat_id]);
        }

        $response = $this->get(route('customers.equip-list'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_invoke()
    {
        $cat = EquipmentCategory::factory()->count(3)->create();
        $equip = [];
        foreach($cat as $c)
        {
            $equip[] = EquipmentType::factory()->count(3)->create(['cat_id' => $c->cat_id]);
        }

        $response = $this->actingAs(User::factory()->create())->get(route('customers.equip-list'));
        $response->assertSuccessful();
        $response->assertJson(EquipmentCategory::with('EquipmentType.DataFieldType')->get()->toArray());
    }
}

<?php

namespace Tests\Feature\Equipment;

use App\Events\Equipment\WorkbookCanvasEvent;
use App\Models\EquipmentType;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EquipmentWorkbookTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Index Method
    |---------------------------------------------------------------------------
    */
    public function test_index_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $response = $this->get(route('workbooks.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('workbooks.index'));

        $response->assertForbidden();
    }

    public function test_index(): void
    {
        config(['customer.enable_workbooks' => true]);

        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->actingAs($user)->get(route('workbooks.index'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Equipment/Workbook/Index')
                    ->has('equipment-list')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Create Method
    |---------------------------------------------------------------------------
    */
    public function test_create_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $equip = EquipmentType::factory()->create();

        $response = $this->get(route('workbooks.create', $equip->equip_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)->get(route('workbooks.create', $equip->equip_id));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)->get(route('workbooks.create', $equip->equip_id));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Equipment/Workbook/Create')
                    ->has('equipment-type')
                    ->has('workbook-data')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $equip = EquipmentType::factory()->create();
        $data = [
            'workbook_data' => [
                'body' => [],
                'footer' => [],
                'header' => [],
            ],
        ];

        $response = $this->post(route('workbooks.store', $equip->equip_id), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();
        $data = [
            'workbook_data' => [
                'body' => [],
                'footer' => [],
                'header' => [],
            ],
        ];

        $response = $this->actingAs($user)->post(route('workbooks.store', $equip->equip_id), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();
        $data = [
            'workbook_data' => [
                'body' => [],
                'footer' => [],
                'header' => [],
            ],
        ];

        $response = $this->actingAs($user)->post(route('workbooks.store', $equip->equip_id), $data);

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $equip = EquipmentType::factory()->create();

        $response = $this->get(route('workbooks.show', $equip->equip_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)->get(route('workbooks.show', $equip->equip_id));

        $response->assertForbidden();
    }

    public function test_show(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)->get(route('workbooks.show', $equip->equip_id));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Equipment/Workbook/Show')
                    ->has('equipment-type')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Edit Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $equip = EquipmentType::factory()->create();

        $response = $this->get(route('workbooks.edit', $equip->equip_id));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)->get(route('workbooks.edit', $equip->equip_id));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)->get(route('workbooks.edit', $equip->equip_id));

        $response->assertSuccessful();
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        config(['customer.enable_workbooks' => true]);

        $equip = EquipmentType::factory()->create();
        $data = [
            'workbook_data' => [
                'body' => [],
                'footer' => [],
                'header' => [],
            ],
        ];

        $response = $this->put(route('workbooks.update', $equip->equip_id), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();
        $data = [
            'workbook_data' => [
                'body' => [],
                'footer' => [],
                'header' => [],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('workbooks.update', $equip->equip_id), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        config(['customer.enable_workbooks' => true]);

        Event::fake();

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();
        $data = [
            'workbook_data' => [
                'body' => [],
                'footer' => [],
                'header' => [],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('workbooks.update', $equip->equip_id), $data);

        $response->assertSuccessful()
            ->assertJson(['success' => true])
            ->assertSessionHas('workbookData-'.$equip->equip_id);

        Event::assertDispatched(WorkbookCanvasEvent::class);
    }
}

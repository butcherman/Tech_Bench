<?php

namespace Tests\Feature\Equipment;

use App\Exceptions\Misc\FeatureDisabledException;
use App\Models\EquipmentType;
use App\Models\EquipmentWorkbook;
use App\Models\User;
use Illuminate\Support\Facades\Exceptions;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class EquipmentWorkbookControllerTest extends TestCase
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

    public function test_index_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('workbooks.index'));
        $response->assertStatus(404);

        Exceptions::assertReported(FeatureDisabledException::class);
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

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->actingAs($user)->get(route('workbooks.index'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Equipment/Workbook/Index')
                ->has('equipment-list'));
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

        $response = $this->get(route('workbooks.create', $equip));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('workbooks.create', $equip));
        $response->assertStatus(404);

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    public function test_create_no_permission(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('workbooks.create', $equip));

        $response->assertForbidden();
    }

    public function test_create(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('workbooks.create', $equip));

        $response->assertSuccessful()
            ->assertSessionHas('workbookData-'.$equip->equip_id)
            ->assertInertia(fn (Assert $page) => $page
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
        $form = [
            'workbook_data' => [
                'body' => [],
                'header' => [],
                'footer' => [],
            ],
        ];

        $response = $this->post(route('workbooks.store', $equip), $form);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();
        $form = [
            'workbook_data' => [
                'body' => [],
                'header' => [],
                'footer' => [],
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('workbooks.store', $equip), $form);
        $response->assertStatus(404);

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    public function test_store_no_permission(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();
        $form = [
            'workbook_data' => [
                'body' => [],
                'header' => [],
                'footer' => [],
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('workbooks.store', $equip), $form);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();
        $form = [
            'workbook_data' => [
                'body' => [],
                'header' => [],
                'footer' => [],
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('workbooks.store', $equip), $form);

        $response->assertSuccessful()->assertJson(['success' => true]);

        $this->assertDatabaseHas('equipment_workbooks', [
            'equip_id' => $equip->equip_id,
        ]);
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

        $response = $this->get(route('workbooks.show', $equip));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create();

        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('workbooks.show', $equip));
        $response->assertStatus(404);

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    public function test_show_no_permission(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();

        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('workbooks.show', $equip));

        $response->assertForbidden();
    }

    public function test_show(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $equip = EquipmentType::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('workbooks.show', $equip));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
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
        EquipmentWorkbook::factory()->create(['equip_id' => $equip->equip_id]);

        $response = $this->get(route('workbooks.edit', $equip));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();
        EquipmentWorkbook::factory()->create(['equip_id' => $equip->equip_id]);

        $response = $this->actingAs($user)
            ->get(route('workbooks.edit', $equip));

        $response->assertStatus(404);

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    public function test_edit_no_permission(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();
        EquipmentWorkbook::factory()->create(['equip_id' => $equip->equip_id]);

        $response = $this->actingAs($user)
            ->get(route('workbooks.edit', $equip));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();
        EquipmentWorkbook::factory()->create(['equip_id' => $equip->equip_id]);

        $response = $this->actingAs($user)
            ->get(route('workbooks.edit', $equip));

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
        $form = [
            'workbook_data' => [
                'body' => [],
                'header' => [],
                'footer' => [],
            ],
        ];

        $response = $this->put(route('workbooks.update', $equip), $form);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_feature_disabled(): void
    {
        config(['customer.enable_workbooks' => false]);
        Exceptions::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();
        $form = [
            'workbook_data' => [
                'body' => [],
                'header' => [],
                'footer' => [],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('workbooks.update', $equip), $form);

        $response->assertStatus(404);

        Exceptions::assertReported(FeatureDisabledException::class);
    }

    public function test_update_no_permission(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create();
        $equip = EquipmentType::factory()->create();
        $form = [
            'workbook_data' => [
                'body' => [],
                'header' => [],
                'footer' => [],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('workbooks.update', $equip), $form);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        config(['customer.enable_workbooks' => true]);

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $equip = EquipmentType::factory()->create();
        $form = [
            'workbook_data' => [
                'body' => [],
                'header' => [],
                'footer' => [],
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('workbooks.update', $equip), $form);

        $response->assertSuccessful()
            ->assertJson(['success' => true])
            ->assertSessionHas('workbookData-'.$equip->equip_id);
    }
}

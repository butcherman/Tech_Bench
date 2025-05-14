<?php

namespace Tests\Feature\Customer;

use App\Events\File\FileDataDeletedEvent;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\CustomerSite;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerFileTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Store Method
    |---------------------------------------------------------------------------
    */
    public function test_store_guest(): void
    {
        $customer = Customer::factory()->create();
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $response = $this->post(
            route('customers.files.store', $customer->slug),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission(): void
    {
        // Remove the 'Add Customer File' permission from the Tech Role
        $this->changeRolePermission(4, 'Add Customer File', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.files.store', $customer->slug), $data);

        $response->assertForbidden();
    }

    public function test_store(): void
    {
        Storage::fake('customers');

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $fileName = 'This is a test file';
        $data = [
            'name' => $fileName,
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => json_encode(null),
            'site_list' => json_encode([]),
            'file' => UploadedFile::fake()->image('randomImage.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.files.store', $customer->slug), $data);

        $response->assertSuccessful();

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'name' => $fileName,
        ]);

        $this->assertDatabaseHas('file_uploads', [
            'disk' => 'customers',
            'folder' => $customer->cust_id,
            'file_name' => 'randomImage.png',
        ]);

        Storage::disk('customers')
            ->assertExists(
                $customer->cust_id.DIRECTORY_SEPARATOR.'randomImage.png'
            );
    }

    public function test_store_equip_file(): void
    {
        Storage::fake('customers');

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $fileName = 'This is a test file';
        $data = [
            'name' => $fileName,
            'file_type' => 'equipment',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => CustomerEquipment::factory()
                ->create(['cust_id' => $customer->cust_id])
                ->cust_equip_id,
            'site_list' => json_encode([]),
            'file' => UploadedFile::fake()->image('randomImage.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.files.store', $customer->slug), $data);

        $response->assertSuccessful();

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'name' => $fileName,
            'cust_equip_id' => $data['cust_equip_id'],
        ]);
        $this->assertDatabaseHas('file_uploads', [
            'disk' => 'customers',
            'folder' => $customer->cust_id,
            'file_name' => 'randomImage.png',
        ]);

        Storage::disk('customers')
            ->assertExists(
                $customer->cust_id.DIRECTORY_SEPARATOR.'randomImage.png'
            );
    }

    public function test_store_site_file(): void
    {
        Storage::fake('customers');

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $sites = CustomerSite::factory()
            ->count(3)
            ->create(['cust_id' => $customer->cust_id]);
        $fileName = 'This is a test file';
        $data = [
            'name' => $fileName,
            'file_type' => 'site',
            'file_type_id' => CustomerFileType::inRandomOrder()->first()->file_type_id,
            'cust_equip_id' => json_encode(null),
            'site_list' => json_encode($sites->pluck('cust_site_id')),
            'file' => UploadedFile::fake()->image('randomImage.png'),
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.files.store', $customer->slug), $data);

        $response->assertSuccessful();

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'name' => $fileName,
        ]);

        $this->assertDatabaseHas('file_uploads', [
            'disk' => 'customers',
            'folder' => $customer->cust_id,
            'file_name' => 'randomImage.png',
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $sites[0]->cust_site_id,
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $sites[1]->cust_site_id,
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_site_id' => $sites[2]->cust_site_id,
        ]);

        Storage::disk('customers')
            ->assertExists($customer->cust_id.DIRECTORY_SEPARATOR.'randomImage.png');
    }

    public function test_store_chunked_file(): void
    {
        Storage::fake('customers');

        /** @var User $user */
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $chunks = [
            [
                'dzuuid' => 'a1ae8f25-be4c-4a75-9e3d-c5f4abe7cba0',
                'dzchunkindex' => '0',
                'dztotalfilesize' => '16711880',
                'dzchunksize' => '5000000',
                'dztotalchunkcount' => '4',
                'dzchunkbyteoffset' => '0',
                'name' => 'test_file.png',
                'file_type' => 'general',
                'site_list' => [],
                'cust_equip_id' => 'null',
                'file_type_id' => '2',
                'file' => UploadedFile::fake()->image('test_file.png'),
            ],
            [
                'dzuuid' => 'a1ae8f25-be4c-4a75-9e3d-c5f4abe7cba0',
                'dzchunkindex' => '1',
                'dztotalfilesize' => '16711880',
                'dzchunksize' => '5000000',
                'dztotalchunkcount' => '4',
                'dzchunkbyteoffset' => '5000000',
                'name' => 'test_file.png',
                'file_type' => 'general',
                'site_list' => [],
                'cust_equip_id' => 'null',
                'file_type_id' => '2',
                'file' => UploadedFile::fake()->image('test_file.png'),
            ],
            [
                'dzuuid' => 'a1ae8f25-be4c-4a75-9e3d-c5f4abe7cba0',
                'dzchunkindex' => '2',
                'dztotalfilesize' => '16711880',
                'dzchunksize' => '5000000',
                'dztotalchunkcount' => '4',
                'dzchunkbyteoffset' => '10000000',
                'name' => 'test_file.png',
                'file_type' => 'general',
                'site_list' => [],
                'cust_equip_id' => 'null',
                'file_type_id' => '2',
                'file' => UploadedFile::fake()->image('test_file.png'),
            ],
            [
                'dzuuid' => 'a1ae8f25-be4c-4a75-9e3d-c5f4abe7cba0',
                'dzchunkindex' => '3',
                'dztotalfilesize' => '16711880',
                'dzchunksize' => '5000000',
                'dztotalchunkcount' => '4',
                'dzchunkbyteoffset' => '15000000',
                'name' => 'test_file.png',
                'file_type' => 'general',
                'site_list' => [],
                'cust_equip_id' => 'null',
                'file_type_id' => '2',
                'file' => UploadedFile::fake()->image('test_file.png'),
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('customers.files.store', $customer->slug), $chunks[0]);

        $response->assertSuccessful();

        $response = $this->actingAs($user)
            ->post(route('customers.files.store', $customer->slug), $chunks[1]);

        $response->assertSuccessful();

        $response = $this->actingAs($user)
            ->post(route('customers.files.store', $customer->slug), $chunks[2]);

        $response->assertSuccessful();

        $response = $this->actingAs($user)
            ->post(route('customers.files.store', $customer->slug), $chunks[3]);

        $response->assertSuccessful();
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
    {
        $customer = Customer::factory()->create();
        $file = CustomerFile::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $response = $this->put(route('customers.files.update', [
            $customer->slug,
            $file->cust_file_id,
        ]), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        // Remove the 'Edit Customer File' permission from the Tech Role
        $this->changeRolePermission(4, 'Edit Customer File', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $file = CustomerFile::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.files.update', [
                $customer->slug,
                $file->cust_file_id,
            ]), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $file = CustomerFile::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.files.update', [
                $customer->slug,
                $file->cust_file_id,
            ]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.file.updated'));

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'name' => $data['name'],
            'cust_equip_id' => null,
        ]);
    }

    public function test_update_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $invalid = Customer::factory()->create();
        $file = CustomerFile::factory()
            ->create(['cust_id' => $customer->cust_id]);
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'general',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [],
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.files.update', [
                $invalid->slug,
                $file->cust_file_id,
            ]), $data);

        $response->assertStatus(404);
    }

    public function test_update_site_file(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();
        $sites = CustomerSite::factory()
            ->count(3)
            ->create(['cust_id' => $customer->cust_id])
            ->pluck('cust_site_id');
        $file = CustomerFile::factory()->create(['cust_id' => $customer->cust_id]);
        $file->Sites()->sync([$sites[0], $sites[1]]);
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'site',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [$sites[1], $sites[2]],
        ];

        $response = $this->actingAs($user)
            ->put(route('customers.files.update', [
                $customer->slug,
                $file->cust_file_id,
            ]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.file.updated'));

        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'name' => $data['name'],
        ]);

        $this->assertDatabaseMissing('customer_site_files', [
            'cust_file_id' => $file->cust_file_id,
            'cust_site_id' => $sites[0],
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_file_id' => $file->cust_file_id,
            'cust_site_id' => $sites[1],
        ]);

        $this->assertDatabaseHas('customer_site_files', [
            'cust_file_id' => $file->cust_file_id,
            'cust_site_id' => $sites[2],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Destroy Method
    |---------------------------------------------------------------------------
    */
    public function test_destroy_guest(): void
    {
        $data = CustomerFile::factory()->create();

        $response = $this->delete(route('customers.files.destroy', [
            $data->Customer->slug,
            $data->cust_file_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission(): void
    {
        // Remove the 'Add Customer File' permission from the Tech Role
        $this->changeRolePermission(4, 'Delete Customer File', false);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = CustomerFile::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('customers.files.destroy', [
                $data->Customer->slug,
                $data->cust_file_id,
            ]));

        $response->assertForbidden();
    }

    public function test_destroy(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = CustomerFile::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('customers.files.destroy', [
                $data->Customer->slug,
                $data->cust_file_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('cust.file.deleted'));

        $this->assertSoftDeleted('customer_files', $data->only([
            'file_id',
            'cust_file_id',
            'name',
        ]));
    }

    public function test_destroy_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $invalid = Customer::factory()->create();
        $data = CustomerFile::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('customers.files.destroy', [
                $invalid->slug,
                $data->cust_file_id,
            ]));

        $response->assertStatus(404);
    }

    /*
    |---------------------------------------------------------------------------
    | Restore Method
    |---------------------------------------------------------------------------
    */
    public function test_restore_guest(): void
    {
        $file = CustomerFile::factory()->create();

        $response = $this->get(route('customers.deleted-items.restore.files', [
            $file->Customer->slug,
            $file->cust_file_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_restore_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $file = CustomerFile::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.deleted-items.restore.files', [
                $file->Customer->slug,
                $file->cust_file_id,
            ]));

        $response->assertForbidden();
    }

    public function test_restore(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $file = CustomerFile::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.deleted-items.restore.files', [
                $file->Customer->slug,
                $file->cust_file_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('success', __('cust.file.restored'));

        $this->assertDatabaseHas('customer_files', $file->only([
            'file_id',
        ]));
    }

    public function test_restore_scope_bindings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $invalid = Customer::factory()->create();
        $file = CustomerFile::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('customers.deleted-items.restore.files', [
                $invalid->slug,
                $file->cust_file_id,
            ]));

        $response->assertStatus(404);
    }

    /**
     * Force Delete Method
     */
    public function test_force_delete_guest(): void
    {
        $file = CustomerFile::factory()->create();
        $file->delete();

        $response = $this->delete(route('customers.deleted-items.force-delete.files', [
            $file->Customer->slug,
            $file->cust_file_id,
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_force_delete_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $file = CustomerFile::factory()->create();
        $file->delete();

        $response = $this->actingAs($user)
            ->delete(route('customers.deleted-items.force-delete.files', [
                $file->Customer->slug,
                $file->cust_file_id,
            ]));

        $response->assertForbidden();
    }

    public function test_force_delete(): void
    {
        Event::fake(FileDataDeletedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $file = CustomerFile::factory()->create();
        $file->delete();

        $response = $this->actingAs($user)
            ->delete(route('customers.deleted-items.force-delete.files', [
                $file->Customer->slug,
                $file->cust_file_id,
            ]));

        $response->assertStatus(302)
            ->assertSessionHas('warning', __('cust.file.force_deleted'));

        $this->assertDatabaseMissing('customer_files', $file->only(['file_id']));

        Event::assertDispatched(FileDataDeletedEvent::class);
    }

    public function test_force_delete_scope_bindings(): void
    {
        Event::fake(FileDataDeletedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $invalid = Customer::factory()->create();
        $file = CustomerFile::factory()->create();
        $file->delete();

        $response = $this->actingAs($user)
            ->delete(route('customers.deleted-items.force-delete.files', [
                $invalid->slug,
                $file->cust_file_id,
            ]));

        $response->assertStatus(404);

        Event::assertNotDispatched(FileDataDeletedEvent::class);
    }
}

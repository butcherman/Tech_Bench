<?php

namespace Tests\Feature\Customer;

use App\Events\File\FileDataDeletedEvent;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\CustomerSite;
use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerFileTest extends TestCase
{
    /*
     *   Store Method
     */
    public function test_store_guest()
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
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
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

        UserRolePermission::where('role_id', 4)->where('perm_type_id', 20)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('customers.files.store', $customer->slug), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        // Event::fake();

        Storage::fake('customers');

        $customer = Customer::factory()->create();
        $fileName = 'This is a test file';
        $data = [
            'name' => json_encode($fileName),
            'file_type' => json_encode('general'),
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => json_encode(null),
            'site_list' => json_encode([]),
            'file' => UploadedFile::fake()->image('randomImage.png'),
        ];

        $response = $this->actingAs(User::factory()->create())
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

        // Event::assertDispatched(CustomerFileCreatedEvent::class);
    }

    public function test_store_site_file()
    {
        // Event::fake();

        Storage::fake('customers');

        $customer = Customer::factory()->create();
        $sites = CustomerSite::factory()
            ->count(3)
            ->create(['cust_id' => $customer->cust_id]);
        $fileName = 'This is a test file';
        $data = [
            'name' => json_encode($fileName),
            'file_type' => json_encode('site'),
            'file_type_id' => CustomerFileType::inRandomOrder()->first()->file_type_id,
            'cust_equip_id' => json_encode(null),
            'site_list' => json_encode($sites->pluck('cust_site_id')),
            'file' => UploadedFile::fake()->image('randomImage.png'),
        ];

        $response = $this->actingAs(User::factory()->create())
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

        // Event::assertDispatched(CustomerFileCreatedEvent::class);
    }

    /*
     *   Update Method
     */
    public function test_update_guest()
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
            $customer->cust_id,
            $file->cust_file_id,
        ]), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
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

        UserRolePermission::where('role_id', 4)->where('perm_type_id', 21)
            ->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.files.update', [
                $customer->cust_id,
                $file->cust_file_id,
            ]), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        // Event::fake();

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

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.files.update', [
                $customer->cust_id,
                $file->cust_file_id,
            ]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.file.updated'));
        $this->assertDatabaseHas('customer_files', [
            'cust_id' => $customer->cust_id,
            'name' => $data['name'],
        ]);

        // Event::assertDispatched(CustomerFileUpdatedEvent::class);
    }

    public function test_update_site_file()
    {
        // Event::fake();

        $customer = Customer::factory()->create();
        $sites = CustomerSite::factory()
            ->count(3)
            ->create(['cust_id' => $customer->cust_id])
            ->pluck('cust_site_id');
        $file = CustomerFile::factory()->create(['cust_id' => $customer->cust_id]);
        $file->CustomerSite()->sync([$sites[0], $sites[1]]);
        // CustomerFileSite::create([
        //     'cust_file_id' => $file->cust_file_id,
        //     'cust_site_id' => $sites[0],
        // ]);
        // CustomerFileSite::create([
        //     'cust_file_id' => $file->cust_file_id,
        //     'cust_site_id' => $sites[1],
        // ]);
        $data = [
            'name' => 'This is a test file',
            'file_type' => 'site',
            'file_type_id' => CustomerFileType::inRandomOrder()
                ->first()
                ->file_type_id,
            'cust_equip_id' => null,
            'site_list' => [$sites[1], $sites[2]],
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('customers.files.update', [
                $customer->cust_id,
                $file->cust_file_id,
            ]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.file.updated'));
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

        // Event::assertDispatched(CustomerFileUpdatedEvent::class);
    }

    /*
     *   Destroy Method
     */
    public function test_destroy_guest()
    {
        $data = CustomerFile::factory()->create();

        $response = $this->delete(route('customers.files.destroy', [
            $data->cust_id,
            $data->cust_file_id,
        ]));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_destroy_no_permission()
    {
        $data = CustomerFile::factory()->create();

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', 22)->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.files.destroy', [
                $data->cust_id,
                $data->cust_file_id,
            ]));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        // Event::fake();

        $data = CustomerFile::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.files.destroy', [
                $data->cust_id,
                $data->cust_file_id,
            ]));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.file.deleted'));
        $this->assertSoftDeleted('customer_files', $data->only([
            'file_id',
            'cust_file_id',
            'name',
        ]));

        // Event::assertDispatched(CustomerFileDeletedEvent::class);
    }

    /**
     * Restore Method
     */
    public function test_restore_guest()
    {
        $file = CustomerFile::factory()->create();

        $response = $this->get(route('customers.deleted-items.restore.files', [
            $file->cust_id,
            $file->file_id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_restore_no_permission()
    {
        $file = CustomerFile::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('customers.deleted-items.restore.files', [
                $file->cust_id,
                $file->file_id,
            ]));

        $response->assertStatus(403);
    }

    public function test_restore()
    {
        $file = CustomerFile::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('customers.deleted-items.restore.files', [
                $file->cust_id,
                $file->file_id,
            ]));

        $response->assertStatus(302);
        $response->assertSessionHas('success', __('cust.file.restored'));
        $this->assertDatabaseHas('customer_files', $file->only([
            'file_id',
        ]));
    }

    /**
     * Force Delete Method
     */
    public function test_force_delete_guest()
    {
        $file = CustomerFile::factory()->create();
        $file->delete();

        $response = $this->delete(route('customers.deleted-items.force-delete.files', [
            $file->cust_id,
            $file->file_id,
        ]));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_force_delete_no_permission()
    {
        $file = CustomerFile::factory()->create();
        $file->delete();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('customers.deleted-items.force-delete.files', [
                $file->cust_id,
                $file->file_id,
            ]));

        $response->assertStatus(403);
    }

    public function test_force_delete()
    {
        // Event::fake();

        $file = CustomerFile::factory()->create();
        $file->delete();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->delete(route('customers.deleted-items.force-delete.files', [
                $file->cust_id,
                $file->file_id,
            ]));

        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('cust.file.force_deleted'));
        $this->assertDatabaseMissing('customer_files', $file->only(['file_id']));

        // Event::assertDispatched(FileDataDeletedEvent::class);
    }
}

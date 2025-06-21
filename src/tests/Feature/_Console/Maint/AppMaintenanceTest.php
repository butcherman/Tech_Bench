<?php

namespace Tests\Feature\_Console\Maint;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\DataField;
use App\Models\EquipmentType;
use App\Models\FileUpload;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class AppMaintenanceTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_handle_no_problems(): void
    {
        $this->artisan('app:maintenance')->assertExitCode(0);
    }

    /**
     * User Check
     */
    public function test_handle_user_errors(): void
    {
        DB::table('users')->insert([
            'user_id' => 2,
            'role_id' => 2,
            'username' => 'testUser',
            'first_name' => 'test',
            'last_name' => 'user',
            'email' => 'test@noem.com',
        ]);

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('Users missing User Settings Data')
            ->assertExitCode(0);

        $this->assertDatabaseHas('user_settings', ['user_id' => 2]);
    }

    /**
     * Customer Check
     */
    public function test_customer_check(): void
    {
        Customer::factory()->count(10)->create();
        $lonely = Customer::create([
            'name' => 'Customer without children',
            'slug' => Str::slug('Customer without children'),
        ]);

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('Deleted 1 Customer Profiles')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('customers', [
            'cust_id' => $lonely->cust_id,
        ]);
    }

    public function test_customer_equipment_check(): void
    {
        $equip = EquipmentType::factory()->create();
        $equipList = CustomerEquipment::factory()
            ->count(10)
            ->create(['equip_id' => $equip->equip_id]);

        $field1 = DataField::create([
            'equip_id' => $equip->equip_id,
            'type_id' => 1,
            'order' => 0,
        ]);
        $field2 = DataField::create([
            'equip_id' => $equip->equip_id,
            'type_id' => 2,
            'order' => 1,
        ]);

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('Customer Equipment Data Fields Missing')
            ->assertExitCode(0);

        foreach ($equipList as $custEquip) {
            $this->assertDatabaseHas('customer_equipment_data', [
                'cust_equip_id' => $custEquip->cust_equip_id,
                'field_id' => $field1->field_id,
            ]);
            $this->assertDatabaseHas('customer_equipment_data', [
                'cust_equip_id' => $custEquip->cust_equip_id,
                'field_id' => $field2->field_id,
            ]);
        }
    }

    /**
     * Filesystem Check
     */
    public function test_handle_empty_folders(): void
    {
        Storage::fake('local');
        Storage::disk('local')->makeDirectory('empty_directory');
        Storage::disk('local')->makeDirectory('not_empty');
        Storage::disk('local')->put('not_empty/test.txt', 'test file');

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('The following directories are empty and can be deleted')
            ->assertExitCode(0);

        Storage::assertMissing('empty_directory');
        Storage::assertExists('not_empty');
    }

    public function test_handle_missing_files(): void
    {
        Storage::fake('local');
        Storage::disk('local')->makeDirectory('test_one');
        Storage::disk('local')->put('test_one/valid.txt', 'valid file');

        FileUpload::create([
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'valid.txt',
            'file_size' => 0,
            'public' => 0,
        ]);
        FileUpload::create([
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'invalid.txt',
            'file_size' => 0,
            'public' => 0,
        ]);

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('Found 1 files missing from filesystem.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('file_uploads', [
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'invalid.txt',
        ]);
    }

    public function test_handle_orphaned_files(): void
    {
        Storage::fake('local');
        Storage::makeDirectory('test_one');
        Storage::put('test_one/test.txt', 'test file');
        Storage::put('test_one/valid.txt', 'valid file');

        FileUpload::factory()->create([
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'valid.txt',
        ]);

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('Found 1 files without a database entry')
            ->assertExitCode(0);

        Storage::assertMissing('test_one/test.txt');
        Storage::assertExists('test_one/valid.txt');
    }

    public function test_all_file_maintenance_all_together(): void
    {
        Storage::fake('local');
        Storage::makeDirectory('test_one');
        Storage::makeDirectory('test_two');
        Storage::put('test_one/valid.txt', 'valid file');
        Storage::put('test_two/test.txt', 'test file');
        Storage::put('test_two/valid.txt', 'valid file');

        FileUpload::create([
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'valid.txt',
            'file_size' => 0,
            'public' => false,
        ]);

        FileUpload::create([
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'invalid.txt',
            'file_size' => 0,
            'public' => false,
        ]);

        FileUpload::create([
            'disk' => 'local',
            'folder' => 'test_two',
            'file_name' => 'valid.txt',
            'file_size' => 0,
            'public' => false,
        ]);

        $this->artisan('app:maintenance --fix')
            ->expectsOutput('Found 1 files missing from filesystem.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('file_uploads', [
            'disk' => 'local',
            'folder' => 'test_one',
            'file_name' => 'invalid.txt',
        ]);

        Storage::assertMissing('test_two/test.txt');
        Storage::assertExists('test_one/valid.txt');
        Storage::assertExists('test_two/valid.txt');
    }
}

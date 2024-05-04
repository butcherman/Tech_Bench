<?php

namespace Tests\Unit\Actions;

use App\Actions\DestroyCustomer;
use App\Events\File\FileDataDeletedEvent;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DestroyCustomerUnitTest extends TestCase
{
    public function test_destroy_customer()
    {
        Storage::fake('customers');
        Event::fake();

        $customer = Customer::factory()
            ->has(CustomerEquipment::factory())
            ->has(CustomerFile::factory())
            ->has(CustomerNote::factory())
            ->has(CustomerContact::factory())
            ->create();

        $equipId = $customer->CustomerEquipment[0]->cust_equip_id;
        $fileId = $customer->CustomerFile[0]->cust_file_id;
        $noteId = $customer->CustomerNote[0]->note_id;
        $contId = $customer->CustomerContact[0]->cont_id;

        $file = $customer->CustomerFile[0]->FileUpload;
        $filePath = $file->folder.DIRECTORY_SEPARATOR.$file->file_name;

        Storage::disk('customers')
            ->putFileAs(
                $file->folder,
                UploadedFile::fake()->image($file->file_name),
                $file->file_name
            );

        $obj = new DestroyCustomer($customer);

        $this->assertTrue($obj->wasSuccessful());

        $this->assertDatabaseMissing('customers', [
            'cust_id' => $customer->cust_id,
        ]);
        $this->assertDatabaseMissing('customer_sites', [
            'cust_site_id' => $customer->primary_site_id,
        ]);
        $this->assertDatabaseMissing('customer_equipment', [
            'cust_equip_id' => $equipId,
        ]);
        $this->assertDatabaseMissing('customer_files', [
            'cust_file_id' => $fileId,
        ]);
        $this->assertDatabaseMissing('customer_notes', [
            'note_id' => $noteId,
        ]);
        $this->assertDatabaseMissing('customer_contacts', [
            'cont_id' => $contId,
        ]);

        Storage::assertMissing($filePath);
        Event::assertDispatched(FileDataDeletedEvent::class);
    }
}

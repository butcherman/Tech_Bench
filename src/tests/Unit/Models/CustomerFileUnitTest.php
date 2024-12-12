<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerFileType;
use App\Models\CustomerSite;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CustomerFileUnitTest extends TestCase
{
    protected $model;

    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::factory()->create();
        $this->model = CustomerFile::factory()
            ->create(['cust_id' => $this->customer->cust_id])
            ->append('href');
        $this->model->CustomerSite()->sync([$this->customer->primary_site_id]);
    }

    /**
     * Model Attributes
     */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('file_type', $this->model->toArray());
        $this->assertArrayHasKey('created_stamp', $this->model->toArray());
        $this->assertArrayHasKey('uploaded_by', $this->model->toArray());
        $this->assertArrayHasKey('equip_name', $this->model->toArray());
        $this->assertArrayHasKey('href', $this->model->toArray());
    }

    /**
     * Model Relationships
     */
    public function test_file_upload_relationship(): void
    {
        $data = FileUpload::where('file_id', $this->model->file_id)->first();

        $this->assertEquals(
            $data->toArray(),
            $this->model->FileUpload->toArray()
        );
    }

    public function test_customer_site_relationship(): void
    {
        $data = CustomerSite::where('cust_id', $this->customer->cust_id)->get();

        $this->assertEquals(
            $data->toArray(),
            $this->model->CustomerSite->toArray()
        );
    }

    public function test_customer_file_type_relationship(): void
    {
        $data = CustomerFileType::where(
            'file_type_id',
            $this->model->file_type_id
        )->first();

        $this->assertEquals(
            $data->toArray(),
            $this->model->CustomerFileType->toArray()
        );
    }

    public function test_user_relationship(): void
    {
        $data = User::where('user_id', $this->model->user_id)->first();

        $this->assertEquals($data->toArray(), $this->model->User->toArray());
    }

    public function test_customer_equipment_relationship(): void
    {
        $data = CustomerEquipment::factory()
            ->create(['cust_id' => $this->customer->cust_id]);
        $this->model->update(['cust_equip_id' => $data->cust_equip_id]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model->fresh()->CustomerEquipment->toArray()
        );
    }

    /**
     * Prunable Models
     */
    public function test_prunable(): void
    {
        Event::fake();

        $models = CustomerFile::factory()
            ->count(5)
            ->create(['cust_id' => $this->customer->cust_id]);

        $models[0]->delete();
        $this->travel(30)->days(); // 120 days ago

        $models[1]->delete();
        $this->travel(30)->days(); // 90 days ago

        $models[2]->delete();
        $this->travel(30)->days(); // 60 days ago

        $models[3]->delete();
        $this->travel(30)->days(); // 30 days ago

        $models[4]->delete(); // now

        Artisan::call('model:prune', ['--model' => CustomerFile::class]);

        $totalContacts = CustomerFile::where('cust_id', $this->customer->cust_id)
            ->withTrashed()
            ->count();

        $this->assertEquals($totalContacts, 4);
    }

    public function test_prunable_disabled(): void
    {
        Event::fake();

        config(['customer.auto_purge' => false]);

        $models = CustomerFile::factory()
            ->count(5)
            ->create(['cust_id' => $this->customer->cust_id]);

        $models[0]->delete();
        $this->travel(30)->days(); // 120 days ago

        $models[1]->delete();
        $this->travel(30)->days(); // 90 days ago

        $models[2]->delete();
        $this->travel(30)->days(); // 60 days ago

        $models[3]->delete();
        $this->travel(30)->days(); // 30 days ago

        $models[4]->delete(); // now

        Artisan::call('model:prune', ['--model' => CustomerFile::class]);

        $totalContacts = CustomerFile::where('cust_id', $this->customer->cust_id)
            ->withTrashed()
            ->count();

        $this->assertEquals($totalContacts, 6);
    }
}

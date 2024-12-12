<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\EquipmentType;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CustomerEquipmentUnitTest extends TestCase
{
    protected $model;

    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::factory()->create();
        $this->model = CustomerEquipment::factory()
            ->create(['cust_id' => $this->customer->cust_id]);
        $this->model->CustomerSite()->sync([$this->customer->primary_site_id]);
    }

    /**
     * Model Attributes
     */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('equip_name', $this->model->toArray());
    }

    /**
     * Model Relationships
     */
    public function test_equipment_type_relationship(): void
    {
        $data = EquipmentType::where('equip_id', $this->model->equip_id)
            ->first();

        $this->assertEquals(
            $data->toArray(),
            $this->model->EquipmentType->toArray()
        );
    }

    public function test_customer_site_relationship(): void
    {
        $data = CustomerSite::where(
            'cust_site_id',
            $this->customer->primary_site_id
        )->get();

        $this->assertEquals(
            $data->toArray(),
            $this->model->CustomerSite->toArray()
        );
    }

    public function test_customer_note_relationship(): void
    {
        $data = CustomerNote::factory()
            ->create([
                'cust_id' => $this->customer->cust_id,
                'cust_equip_id' => $this->model->cust_equip_id,
            ]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model
                ->CustomerNote[0]
                ->makeHidden(['CustomerEquipment', 'deleted_at'])
                ->toArray()
        );
    }

    public function test_customer_file_relationship(): void
    {
        $data = CustomerFile::factory()
            ->create([
                'cust_id' => $this->customer->cust_id,
                'cust_equip_id' => $this->model->cust_equip_id,
            ]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model
                ->CustomerFile[0]
                ->makeHidden(['CustomerSite'])
                ->toArray()
        );
    }

    public function test_customer_equipment_data(): void
    {
        $data = CustomerEquipmentData::factory()
            ->count(2)
            ->create(['cust_equip_id' => $this->model->cust_equip_id]);

        $this->assertEquals(
            $data->toArray(),
            $this->model
                ->CustomerEquipmentData
                ->toArray()
        );
    }

    /**
     * Prunable Models
     */
    public function test_prunable(): void
    {
        $models = CustomerEquipment::factory()
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

        Artisan::call('model:prune', ['--model' => CustomerEquipment::class]);

        $totalContacts = CustomerEquipment::where(
            'cust_id',
            $this->customer->cust_id
        )
            ->withTrashed()
            ->count();

        $this->assertEquals($totalContacts, 4);
    }

    public function test_prunable_disabled(): void
    {
        config(['customer.auto_purge' => false]);

        $models = CustomerEquipment::factory()
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

        Artisan::call('model:prune', ['--model' => CustomerEquipment::class]);

        $totalContacts = CustomerEquipment::where(
            'cust_id',
            $this->customer->cust_id
        )
            ->withTrashed()
            ->count();

        $this->assertEquals($totalContacts, 6);
    }
}

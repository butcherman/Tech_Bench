<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CustomerNoteUnitTest extends TestCase
{
    protected $model;

    protected $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::factory()->create();
        $this->model = CustomerNote::factory()
            ->create([
                'cust_id' => $this->customer->cust_id,
                'updated_by' => User::factory(),
            ]);
        $this->model->CustomerSite()->sync([$this->customer->primary_site_id]);
    }

    /**
     * Model Attributes
     */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('author', $this->model->toArray());
        $this->assertArrayHasKey('updated_author', $this->model->toArray());
    }

    /**
     * Model Relationships
     */
    public function test_customer_site_relationship(): void
    {
        $data = CustomerSite::where('cust_id', $this->customer->cust_id)->get();

        $this->assertEquals(
            $data->toArray(),
            $this->model->CustomerSite->toArray()
        );
    }

    public function test_customer_equipment_relationship(): void
    {
        $data = CustomerEquipment::factory()
            ->create(['cust_id' => $this->customer->cust_id]);
        $this->model->update(['cust_equip_id' => $data->cust_equip_id]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model->CustomerEquipment->toArray()
        );
    }

    /**
     * Prunable Models
     */
    public function test_prunable(): void
    {
        $models = CustomerNote::factory()
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

        Artisan::call('model:prune', ['--model' => CustomerNote::class]);

        $totalContacts = CustomerNote::where('cust_id', $this->customer->cust_id)
            ->withTrashed()
            ->count();

        $this->assertEquals($totalContacts, 4);
    }

    public function test_prunable_disabled(): void
    {
        config(['customer.auto_purge' => false]);

        $models = CustomerNote::factory()
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

        Artisan::call('model:prune', ['--model' => CustomerNote::class]);

        $totalContacts = CustomerNote::where('cust_id', $this->customer->cust_id)
            ->withTrashed()
            ->count();

        $this->assertEquals($totalContacts, 6);
    }
}

<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Models\CustomerSite;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CustomerContactUnitTest extends TestCase
{
    protected $model;

    protected $customer;

    public function setUp(): void
    {
        parent::setUp();

        $this->customer = Customer::factory()->create();
        $this->model = CustomerContact::factory()
            ->create(['cust_id' => $this->customer->cust_id]);
        $this->model->CustomerSite()->sync([$this->customer->primary_site_id]);
    }

    /**
     * Model Relationships
     */
    public function test_customer_site_relationship()
    {
        $data = CustomerSite::where('cust_id', $this->customer->cust_id)->get();

        $this->assertEquals(
            $data->toArray(),
            $this->model->CustomerSite->toArray()
        );
    }

    public function test_customer_contact_phone_relationship()
    {
        $data = CustomerContactPhone::factory()
            ->create(['cont_id' => $this->model->cont_id]);

        $this->assertEquals(
            $data->toArray(),
            $this->model
                ->CustomerContactPhone[0]
                ->makeHidden(['PhoneNumberType'])
                ->toArray()
        );
    }

    /**
     * Prunable Models
     */
    public function test_prunable()
    {
        $models = CustomerContact::factory()
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

        Artisan::call('model:prune', ['--model' => CustomerContact::class]);
        $totalContacts = CustomerContact::where('cust_id', $this->customer->cust_id)
            ->withTrashed()
            ->count();

        $this->assertEquals($totalContacts, 4);
    }

    public function test_prunable_disabled()
    {
        config(['customer.auto_purge' => false]);

        $models = CustomerContact::factory()
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

        Artisan::call('model:prune', ['--model' => CustomerContact::class]);
        $totalContacts = CustomerContact::where('cust_id', $this->customer->cust_id)
            ->withTrashed()
            ->count();

        $this->assertEquals($totalContacts, 6);
    }
}
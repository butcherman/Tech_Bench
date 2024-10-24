<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerAlert;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CustomerUnitTest extends TestCase
{
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = Customer::factory()->create();
    }

    /***************************************************************************
     * Route Model Binding Key
     ***************************************************************************/
    public function test_route_binding_key()
    {
        $this->assertEquals(
            $this->model->resolveRouteBinding($this->model->cust_id)->toArray(),
            $this->model->toArray()
        );
        $this->assertEquals(
            $this->model->resolveRouteBinding($this->model->slug)->toArray(),
            $this->model->toArray()
        );
    }

    /***************************************************************************
     * Model Attributes
     ***************************************************************************/
    public function test_model_attributes()
    {
        $this->assertArrayHasKey('site_count', $this->model->toArray());
    }

    /***************************************************************************
     * Model Relationships
     ***************************************************************************/
    public function test_customer_site_relationship()
    {
        $data = CustomerSite::where('cust_site_id', $this->model->primary_site_id)
            ->first();

        $this->assertEquals(
            $data->toArray(),
            $this->model->CustomerSite[0]->toArray()
        );
    }

    public function test_customer_alert_relationship()
    {
        $data = CustomerAlert::factory()
            ->create(['cust_id' => $this->model->cust_id]);

        $this->assertEquals(
            $data->toArray(),
            $this->model->CustomerAlert[0]->toArray()
        );
    }

    public function test_customer_equipment_relationship()
    {
        $data = CustomerEquipment::factory()
            ->create(['cust_id' => $this->model->cust_id]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model->CustomerEquipment[0]->toArray()
        );
    }

    public function test_customer_contact_relationship()
    {
        $data = CustomerContact::factory()
            ->create(['cust_id' => $this->model->cust_id]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model
                ->CustomerContact[0]
                ->makeHidden(['CustomerContactPhone', 'CustomerSite'])
                ->toArray()
        );
    }

    public function test_customer_note_relationship()
    {
        $data = CustomerNote::factory()
            ->create(['cust_id' => $this->model->cust_id]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model
                ->CustomerNote[0]
                ->makeHidden([
                    'cust_equip_id',
                    'deleted_at',
                    'CustomerEquipment',
                ])->toArray()
        );
    }

    public function test_customer_file_relationship()
    {
        $data = CustomerFile::factory()
            ->create(['cust_id' => $this->model->cust_id]);
        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model
                ->CustomerFile[0]
                ->makeHidden(['CustomerSite'])
                ->toArray()
        );
    }

    public function test_bookmark_relationship()
    {
        $userList = User::factory()
            ->count(5)
            ->create()
            ->pluck('user_id')
            ->toArray();

        $this->model->Bookmarks()->sync($userList);

        $this->assertEquals(
            $userList,
            $this->model
                ->Bookmarks
                ->pluck('user_id')
                ->toArray()
        );
    }

    public function test_recent_relationship()
    {
        $userList = User::factory()
            ->count(5)
            ->create()
            ->pluck('user_id')
            ->toArray();

        $this->model->Recent()->sync($userList);

        $this->assertEquals(
            $userList,
            $this->model
                ->Recent
                ->pluck('user_id')
                ->toArray()
        );
    }

    /***************************************************************************
     * Model Methods
     ***************************************************************************/
    public function test_is_fav_true()
    {
        $user = User::factory()->create();
        $this->model->Bookmarks()->attach($user);

        $this->assertTrue($this->model->isFav($user));
    }

    public function test_is_fav_false()
    {
        $user = User::factory()->create();

        $this->assertFalse($this->model->isFav($user));
    }

    public function test_touch_recent()
    {
        $user = User::factory()->create();

        $this->model->touchRecent($user);

        $this->assertDatabaseHas('user_customer_recents', [
            'user_id' => $user->user_id,
            'cust_id' => $this->model->cust_id,
        ]);
    }

    public function test_touch_recent_again()
    {
        $user = User::factory()->create();
        DB::table('user_customer_recents')->insert([
            'user_id' => $user->user_id,
            'cust_id' => $this->model->cust_id,
        ]);

        $this->model->touchRecent($user);

        $this->assertDatabaseHas('user_customer_recents', [
            'user_id' => $user->user_id,
            'cust_id' => $this->model->cust_id,
        ]);
    }
}

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = Customer::factory()->create();

        $siteList = CustomerSite::factory()
            ->count(3)
            ->create(['cust_id' => $this->model->cust_id]);

        $siteList[0]->delete();

        $this->model->refresh();
    }

    /*
    |---------------------------------------------------------------------------
    | Route Model Binding Key
    |---------------------------------------------------------------------------
    */
    public function test_route_binding_key(): void
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

    public function test_get_route_key_name(): void
    {
        $this->assertEquals('slug', $this->model->getRouteKeyName());
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('site_count', $this->model->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_customer_site_relationship(): void
    {
        $data = CustomerSite::where('cust_id', $this->model->cust_id)
            ->get();

        $this->assertEquals(
            $data->toArray(),
            $this->model->Sites->toArray()
        );
    }

    public function test_customer_site_list_relationship(): void
    {
        $data = CustomerSite::where('cust_id', $this->model->cust_id)
            ->withTrashed()
            ->get();

        $this->assertEquals(
            $data->toArray(),
            $this->model->CustomerSiteList->toArray()
        );
    }

    public function test_customer_alert_relationship(): void
    {
        $data = CustomerAlert::factory()
            ->create(['cust_id' => $this->model->cust_id]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model->Alerts[0]->toArray()
        );
    }

    public function test_customer_equipment_relationship(): void
    {
        $data = CustomerEquipment::factory()
            ->create(['cust_id' => $this->model->cust_id]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model->Equipment[0]->toArray()
        );
    }

    public function test_customer_contact_relationship(): void
    {
        $data = CustomerContact::factory()
            ->create(['cust_id' => $this->model->cust_id]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model
                ->Contacts[0]
                ->makeHidden(['CustomerContactPhone', 'Sites'])
                ->toArray()
        );
    }

    public function test_customer_note_relationship(): void
    {
        $data = CustomerNote::factory()
            ->create(['cust_id' => $this->model->cust_id]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model
                ->Notes[0]
                ->makeHidden([
                    'cust_equip_id',
                    'deleted_at',
                    'CustomerEquipment',
                ])->toArray()
        );
    }

    public function test_customer_file_relationship(): void
    {
        $data = CustomerFile::factory()
            ->create(['cust_id' => $this->model->cust_id]);
        $this->assertEquals(
            $data->makeHidden(['Customer', 'Sites'])->toArray(),
            $this->model
                ->Files[0]
                ->makeHidden(['Sites'])
                ->toArray()
        );
    }

    public function test_bookmark_relationship(): void
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

    public function test_recent_relationship(): void
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

    /*
    |---------------------------------------------------------------------------
    | Model Methods
    |---------------------------------------------------------------------------
    */
    public function test_is_fav_true(): void
    {
        $user = User::factory()->create();
        $this->model->Bookmarks()->attach($user);

        $this->assertTrue($this->model->isFav($user));
    }

    public function test_is_fav_false(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($this->model->isFav($user));
    }

    public function test_touch_recent(): void
    {
        $user = User::factory()->create();

        $this->model->touchRecent($user);

        $this->assertDatabaseHas('user_customer_recents', [
            'user_id' => $user->user_id,
            'cust_id' => $this->model->cust_id,
        ]);
    }

    public function test_touch_recent_again(): void
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

    public function test_toggle_bookmark_on(): void
    {
        $user = User::factory()->create();

        $this->model->toggleBookmark($user, true);

        $this->assertDatabaseHas('user_customer_bookmarks', [
            'user_id' => $user->user_id,
            'cust_id' => $this->model->cust_id,
        ]);
    }

    public function test_toggle_bookmark_off(): void
    {
        $user = User::factory()->create();
        DB::table('user_customer_bookmarks')->insert([
            'user_id' => $user->user_id,
            'cust_id' => $this->model->cust_id,
        ]);

        $this->model->toggleBookmark($user, false);

        $this->assertDatabaseMissing('user_customer_bookmarks', [
            'user_id' => $user->user_id,
            'cust_id' => $this->model->cust_id,
        ]);
    }
}

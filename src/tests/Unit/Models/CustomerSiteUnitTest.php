<?php

namespace Tests\Unit\Models;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerEquipment;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Models\CustomerSite;
use Tests\TestCase;

class CustomerSiteUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = CustomerSite::factory()->createQuietly();
        $this->model->Customer->update([
            'primary_site_id' => $this->model->cust_site_id,
        ]);
    }

    /**
     * Route Model Binding Key
     */
    public function test_route_binding_key(): void
    {
        $this->assertEquals(
            $this->model->resolveRouteBinding($this->model->cust_site_id)
                ->toArray(),
            $this->model->toArray(),
        );
        $this->assertEquals(
            $this->model->resolveRouteBinding($this->model->site_slug)
                ->toArray(),
            $this->model->toArray(),
        );
    }

    public function test_get_route_key_name(): void
    {
        $this->assertEquals('site_slug', $this->model->getRouteKeyName());
    }

    /**
     * Model Attributes
     */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('is_primary', $this->model->toArray());
        $this->assertArrayHasKey(
            'href',
            $this->model->append('href')->toArray()
        );
    }

    public function test_is_primary_attribute(): void
    {
        $newSite = CustomerSite::factory()
            ->create(['cust_id' => $this->model->cust_id]);

        $this->assertTrue($this->model->is_primary);
        $this->assertFalse($newSite->is_primary);
    }

    /**
     * Model Relationships
     */
    public function test_customer_relationship(): void
    {
        $data = Customer::find($this->model->cust_id);

        $this->assertEquals(
            $data->toArray(),
            $this->model->Customer->toArray()
        );
    }

    public function test_site_equipment_relationship(): void
    {
        $data = CustomerEquipment::factory()
            ->createQuietly(['cust_id' => $this->model->cust_id]);
        $data->Sites()->sync([$this->model->cust_site_id]);

        $this->assertEquals(
            $data->toArray(),
            $this->model->SiteEquipment[0]->makeHidden('pivot')->toArray()
        );
    }

    public function test_site_contact_relationship(): void
    {
        $data = CustomerContact::factory()
            ->createQuietly(['cust_id' => $this->model->cust_id]);
        $data->Sites()->sync([$this->model->cust_site_id]);

        $this->assertEquals(
            $data->toArray(),
            $this->model
                ->SiteContact[0]
                ->makeHidden(['CustomerContactPhone', 'Sites', 'pivot'])
                ->toArray()
        );
    }

    public function test_site_note_relationship(): void
    {
        $data = CustomerNote::factory()
            ->createQuietly(['cust_id' => $this->model->cust_id]);
        $data->Sites()->sync([$this->model->cust_site_id]);

        $this->assertEquals(
            $data->toArray(),
            $this->model
                ->SiteNote[0]
                ->makeHidden([
                    'CustomerEquipment',
                    'cust_equip_id',
                    'deleted_at',
                    'pivot',
                ])->toArray()
        );
    }

    public function test_site_file_relationship(): void
    {
        $data = CustomerFile::factory()
            ->createQuietly(['cust_id' => $this->model->cust_id]);
        $data->Sites()->sync([$this->model->cust_site_id]);

        $this->assertEquals(
            $data->makeHidden(['Sites', 'FileUpload'])->toArray(),
            $this->model
                ->SiteFile[0]
                ->makeHidden(['Sites', 'pivot', 'FileUpload'])
                ->toArray()
        );
    }

    public function test_equipment_note_relationship(): void
    {
        $equip = CustomerEquipment::factory()
            ->createQuietly(['cust_id' => $this->model->cust_id]);
        $equip->Sites()->sync([$this->model->cust_site_id]);

        $data = CustomerNote::factory()->create([
            'cust_id' => $this->model->cust_id,
            'cust_equip_id' => $equip->cust_equip_id,
        ]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model
                ->EquipmentNote()[0]
                ->makeHidden(['CustomerEquipment', 'deleted_at'])
                ->toArray()
        );
    }

    public function test_equipment_file_relationship(): void
    {
        $equip = CustomerEquipment::factory()
            ->createQuietly(['cust_id' => $this->model->cust_id]);
        $equip->Sites()->sync([$this->model->cust_site_id]);

        $data = CustomerFile::factory()->create([
            'cust_id' => $this->model->cust_id,
            'cust_equip_id' => $equip->cust_equip_id,
        ]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model
                ->EquipmentFile()[0]
                ->makeHidden(['CustomerEquipment', 'Sites', 'FileUpload', 'deleted_at'])
                ->toArray()
        );
    }

    public function test_general_note_relationship(): void
    {
        $data = CustomerNote::factory()->createQuietly([
            'cust_id' => $this->model->cust_id,
        ]);

        $this->assertEquals(
            $data->makeHidden('Customer')->toArray(),
            $this->model
                ->GeneralNote()[0]
                ->makeHidden(['CustomerEquipment', 'cust_equip_id', 'deleted_at'])
                ->toArray()
        );
    }

    public function test_general_file_relationship(): void
    {
        $data = CustomerFile::factory()->createQuietly([
            'cust_id' => $this->model->cust_id,
        ]);

        $this->assertEquals(
            $data->makeHidden(['Customer', 'Sites'])->toArray(),
            $this->model
                ->GeneralFile()[0]
                ->makeHidden(['CustomerEquipment', 'Sites', 'FileUpload', 'deleted_at'])
                ->toArray()
        );
    }

    /**
     * Additional Methods
     */
    public function test_get_notes(): void
    {
        CustomerNote::factory()->createQuietly(['cust_id' => $this->model->cust_id]);
        CustomerNote::factory()->has(CustomerEquipment::factory())
            ->create(['cust_id' => $this->model->cust_id]);
        CustomerNote::factory()
            ->create(['cust_id' => $this->model->cust_id])
            ->Sites()
            ->sync([$this->model->cust_site_id]);

        $this->assertEquals($this->model->getNotes()->count(), 3);
    }

    public function test_get_files(): void
    {
        CustomerFile::factory()->createQuietly(['cust_id' => $this->model->cust_id]);
        CustomerFile::factory()->has(CustomerEquipment::factory())
            ->create(['cust_id' => $this->model->cust_id]);
        CustomerFile::factory()
            ->create(['cust_id' => $this->model->cust_id])
            ->Sites()
            ->sync([$this->model->cust_site_id]);

        $this->assertEquals($this->model->getFiles()->count(), 3);
    }
}

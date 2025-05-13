<?php

namespace Tests\Unit\Models;

use App\Models\EquipmentType;
use App\Models\FileUpload;
use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\TechTipEquipment;
use App\Models\TechTipFile;
use App\Models\TechTipType;
use App\Models\User;
use Tests\TestCase;

class TechTipUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = TechTip::factory()->create([
            'public' => true,
            'updated_id' => User::factory(),
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Route Model Binding Key
    |---------------------------------------------------------------------------
    */
    public function test_route_binding_key(): void
    {
        $this->assertEquals(
            $this->model
                ->resolveRouteBinding($this->model->tip_id)
                ->makeHidden('allow_comments')
                ->toArray(),
            $this->model->toArray()
        );
        $this->assertEquals(
            $this->model
                ->resolveRouteBinding($this->model->slug)
                ->makeHidden('allow_comments')
                ->toArray(),
            $this->model->toArray()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('href', $this->model->toArray());
        $this->assertArrayHasKey('public_href', $this->model->toArray());
        $this->assertArrayHasKey('equip_list', $this->model->toArray());
        $this->assertArrayHasKey('file_list', $this->model->toArray());

        // Depreciated Attributes - To Be Removed
        $this->assertArrayHasKey('equipList', $this->model->toArray());
        $this->assertArrayHasKey('fileList', $this->model->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_created_by_relationship(): void
    {
        $user = User::find($this->model->user_id);

        $this->assertEquals(
            $user->toArray(),
            $this->model->CreatedBy->toArray()
        );
    }

    public function test_updated_by_relationship(): void
    {
        $user = User::find($this->model->updated_id);

        $this->assertEquals(
            $user->toArray(),
            $this->model->UpdatedBy->toArray()
        );
    }

    public function test_equipment_relationship(): void
    {
        $equip = EquipmentType::factory()->create();
        TechTipEquipment::create([
            'tip_id' => $this->model->tip_id,
            'equip_id' => $equip->equip_id,
        ]);

        $this->assertEquals(
            $equip->toArray(),
            $this->model->Equipment[0]->toArray()
        );
    }

    public function test_public_equipment_relationship(): void
    {
        $equip1 = EquipmentType::factory()->create(['allow_public_tip' => false]);
        $equip2 = EquipmentType::factory()->create(['allow_public_tip' => true]);

        TechTipEquipment::create([
            'tip_id' => $this->model->tip_id,
            'equip_id' => $equip1->equip_id,
        ]);
        TechTipEquipment::create([
            'tip_id' => $this->model->tip_id,
            'equip_id' => $equip2->equip_id,
        ]);

        $this->assertEquals(
            [$equip2->toArray()],
            $this->model->PublicEquipment->toArray()
        );
    }

    public function test_file_upload_relationship(): void
    {
        $file = FileUpload::factory()->create();
        TechTipFile::create([
            'tip_id' => $this->model->tip_id,
            'file_id' => $file->file_id,
        ]);

        $this->assertEquals(
            $file->toArray(),
            $this->model->FileUpload[0]->makeHidden('pivot')->toArray()
        );
    }

    public function test_tech_tip_type_relationship(): void
    {
        $type = TechTipType::find($this->model->tip_type_id);

        $this->assertEquals(
            $type->toArray(),
            $this->model->TechTipType->toArray()
        );
    }

    public function test_tech_tip_comment_relationship(): void
    {
        $comment = TechTipComment::factory()
            ->create(['tip_id' => $this->model->tip_id]);

        $this->assertEquals(
            $comment->toArray(),
            $this->model->TechTipComment[0]->toArray()
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

    public function test_tech_tip_view_relationship(): void
    {
        $this->assertEquals(0, $this->model->TechTipView->views);
    }

    /*
    |---------------------------------------------------------------------------
    | Model Methods
    |---------------------------------------------------------------------------
    */
    public function test_was_viewed(): void
    {
        $this->model->wasViewed();

        $this->assertEquals(1, $this->model->TechTipView->views);
    }
}

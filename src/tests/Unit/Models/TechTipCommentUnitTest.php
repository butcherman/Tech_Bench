<?php

namespace Tests\Unit\Models;

use App\Models\TechTip;
use App\Models\TechTipComment;
use App\Models\TechTipCommentFlag;
use App\Models\User;
use Tests\TestCase;

class TechTipCommentUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = TechTipComment::factory()->create();
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('is_flagged', $this->model->toArray());
        $this->assertArrayHasKey('author', $this->model->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_tech_tip_relationship(): void
    {
        $tip = TechTip::find($this->model->tip_id)->first();

        $this->assertEquals($tip->toArray(), $this->model->TechTip->toArray());
    }

    public function test_flags_relationship(): void
    {
        $flag = TechTipCommentFlag::create([
            'comment_id' => $this->model->comment_id,
            'user_id' => User::factory()->create()->user_id,
        ]);

        $this->assertEquals(
            [$flag->makeHidden('TechTipComment')->toArray()],
            $this->model->Flags->toArray()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Additional Methods
    |---------------------------------------------------------------------------
    */
    public function test_flag_comment_method(): void
    {
        $this->model->flagComment(User::factory()->create());

        $this->assertDatabaseHas('tech_tip_comment_flags', [
            'comment_id' => $this->model->comment_id,
        ]);
    }
}

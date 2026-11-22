<?php

namespace Tests\Unit\Models;

use App\Models\TechTipComment;
use App\Models\TechTipCommentFlag;
use App\Models\User;
use Tests\TestCase;

class TechTipCommentFlagUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = TechTipCommentFlag::create([
            'comment_id' => TechTipComment::factory()->create()->comment_id,
            'user_id' => User::factory()->create()->user_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Model Attributes
    |---------------------------------------------------------------------------
    */
    public function test_model_attributes(): void
    {
        $this->assertArrayHasKey('flagged_by', $this->model->toArray());
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_tech_tip_comment_relationship(): void
    {
        $comment = TechTipComment::find($this->model->comment_id);

        $this->assertEquals(
            $comment->toArray(),
            $this->model->TechTipComment->toArray()
        );
    }
}

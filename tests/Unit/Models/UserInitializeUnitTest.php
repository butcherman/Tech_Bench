<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserInitializeUnitTest extends TestCase
{
    protected $initLink;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->initLink = UserInitialize::create([
            'username' => $this->user->username,
            'token' => Str::uuid(),
        ]);
    }

    /**
     * Test Route Model Binding
     */
    public function test_route_model_binding()
    {
        $this->assertEquals('token', $this->initLink->getRouteKeyName());
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $this->assertEquals($this->user->only(['user_id', 'username']), $this->initLink->User->only(['user_id', 'username']));
    }
}

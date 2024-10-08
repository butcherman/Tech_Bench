<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserInitializeUnitTest extends TestCase
{
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = UserInitialize::create([
            'username' => User::factory()->createQuietly()->username,
            'token' => Str::uuid(),
        ]);
    }

    /**
     * Route Model Binding Key
     */
    public function test_route_binding_key()
    {
        $this->assertEquals($this->model->getRouteKeyName(), 'token');
    }

    /**
     * Model Relationships
     */
    public function test_user_relationship()
    {
        $user = User::where('username', $this->model->username)->first();

        $this->assertEquals($this->model->User, $user);
    }
}

<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserInitializeUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
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
    public function test_route_binding_key(): void
    {
        $this->assertEquals($this->model->getRouteKeyName(), 'token');
    }

    /**
     * Model Relationships
     */
    public function test_user_relationship(): void
    {
        $user = User::where('username', $this->model->username)->first();

        $this->assertEquals($this->model->User, $user);
    }

    /**
     * Prunable Models
     */
    public function test_prunable(): void
    {
        UserInitialize::create([
            'username' => User::factory()->createQuietly()->username,
            'token' => Str::uuid(),
        ]);

        $this->travel(5)->days();
        UserInitialize::create([
            'username' => User::factory()->createQuietly()->username,
            'token' => Str::uuid(),
        ]);

        $this->travel(5)->days();
        UserInitialize::create([
            'username' => User::factory()->createQuietly()->username,
            'token' => Str::uuid(),
        ]);

        Artisan::call('model:prune', ['--model' => UserInitialize::class]);
        $linksLeft = UserInitialize::all()->count();

        $this->assertEquals(2, $linksLeft);
    }
}

<?php

namespace Tests\Unit\Actions\Admin;

use App\Actions\Admin\BuildAdminMenu;
use App\Models\User;
use Tests\TestCase;

class BuildAdminMenuUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke_as_installer(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);
        $shouldBe = $this->getBaseMenu();

        $testObj = new BuildAdminMenu;
        $menu = $testObj($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_invoke_as_admin(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 2]);
        $shouldBe = $this->getBaseMenu();

        unset($shouldBe['Application']);

        $testObj = new BuildAdminMenu;
        $menu = $testObj($user);

        $this->assertEquals($shouldBe, $menu);
    }

    public function test_invoke_without_access(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['role_id' => 3]);
        $shouldBe = [];

        $testObj = new BuildAdminMenu;
        $menu = $testObj($user);

        $this->assertEquals($shouldBe, $menu);
    }

    /**
     * Base Menu with all items
     */
    private function getBaseMenu(): array
    {
        return [
            'Application' => [
                [
                    'label' => 'Application Configuration',
                    'icon' => 'fa-server',
                    'route' => route('admin.basic-settings.edit'),
                ],
                [
                    'label' => 'Application Logo',
                    'icon' => 'fa-image',
                    'route' => route('admin.logo.edit'),
                ],
                [
                    'label' => 'Email Settings',
                    'icon' => 'fas fa-envelope',
                    'route' => route('admin.email-settings.edit'),
                ],
            ],
        ];
    }
}

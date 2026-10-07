<?php

namespace App\Actions\Misc;

use App\Features\TechTipCommentFeature;
use App\Models\User;
use App\Traits\AllowTrait;

class BuildAdminMenu
{
    use AllowTrait;

    protected User $user;

    /**
     * Build the Administration menu based on the user's
     * permissions and enabled application features.
     */
    public function __invoke(User $user): array
    {
        $this->user = $user;

        return array_filter([
            'Users & Access' => $this->usersAndAccess(),
            'Customers' => $this->customers(),
            'Content & Features' => $this->contentAndFeatures(),
            'Files & Equipment' => $this->filesAndEquipment(),
            'Application' => $this->application(),
            'Maintenance' => $this->maintenance(),
        ]);
    }

    /**
     * Users, authentication, authorization and security.
     */
    protected function usersAndAccess(): array
    {
        $menu = [];

        if ($this->can('Manage Users')) {
            $menu[] = $this->item(
                'Users',
                'fas fa-user-edit',
                'admin.user.index'
            );

            $menu[] = $this->item(
                'Create User',
                'fas fa-user-plus',
                'admin.user.create'
            );

            $menu[] = $this->item(
                'Disabled Users',
                'fas fa-store-alt-slash',
                'admin.user.deactivated'
            );

            $menu[] = $this->item(
                'Password Policy',
                'fas fa-user-lock',
                'admin.user.password-policy.edit'
            );

            $menu[] = $this->item(
                'User Security Settings',
                'cog',
                'admin.user.user-settings.edit'
            );
        }

        if ($this->can('Manage Permissions')) {
            $menu[] = $this->item(
                'Roles & Permissions',
                'fas fa-users-cog',
                'admin.user-roles.index'
            );
        }

        if ($this->can('App Settings')) {
            $menu[] = $this->item(
                'Security Settings',
                'fa-lock',
                'admin.security.index'
            );
        }

        return $menu;
    }

    /**
     * Customer-specific configuration and data.
     */
    protected function customers(): array
    {
        if (! $this->can('Manage Customers')) {
            return [];
        }

        return [
            $this->item(
                'Customer Settings',
                'cog',
                'customers.settings.edit'
            ),

            $this->item(
                'Disabled Customers',
                'ban',
                'customers.disabled.index'
            ),

            $this->item(
                'Customer Document Types',
                'file-import',
                'admin.file-types.index'
            ),

            $this->item(
                'Contact Phone Types',
                'phone',
                'admin.phone-types.index'
            ),

            $this->item(
                'Re-Assign Customer Site',
                'truck-moving',
                'customers.re-assign.edit'
            ),

            $this->item(
                'Customer Equipment Data',
                'fas fa-database',
                'equipment-data.index'
            ),
        ];
    }

    /**
     * Tech Tips and application feature management.
     */
    protected function contentAndFeatures(): array
    {
        $menu = [];

        if ($this->can('Manage Tech Tips')) {
            $menu[] = $this->item(
                'Tech Tip Settings',
                'cog',
                'admin.tech-tips.settings.edit'
            );

            $menu[] = $this->item(
                'Tech Tip Types',
                'file-alt',
                'admin.tech-tips.tip-types.index'
            );

            $menu[] = $this->item(
                'Disabled Tech Tips',
                'ban',
                'admin.tech-tips.deleted-tips'
            );

            if ($this->user->features()->active(TechTipCommentFeature::class)) {
                $menu[] = $this->item(
                    'Flagged Comments',
                    'flag',
                    'admin.tech-tips.flagged-comments.index'
                );
            }
        }

        if ($this->can('App Settings')) {
            $menu[] = $this->item(
                'Feature Management',
                'gears',
                'admin.features.edit'
            );
        }

        return $menu;
    }

    /**
     * Equipment configuration and file-related functionality.
     */
    protected function filesAndEquipment(): array
    {
        $menu = [];

        if ($this->can('Manage Equipment')) {
            $menu[] = $this->item(
                'Equipment Categories & Types',
                'fas fa-cogs',
                'equipment.index'
            );

            if (config('customer.enable_workbooks')) {
                if ($this->can('Manage Equipment Workbooks')) {
                    $menu[] = $this->item(
                        'Equipment Workbooks',
                        'fa-table',
                        'workbooks.index'
                    );
                }
            }
        }

        if (
            config('file-link.feature_enabled')
            && $this->can('Manage File Links')
        ) {
            $menu[] = $this->item(
                'File Link Settings',
                'cog',
                'admin.links.settings.edit'
            );

            $menu[] = $this->item(
                'Manage File Links',
                'tools',
                'admin.links.manage.index'
            );
        }

        return $menu;
    }

    /**
     * Application-wide configuration.
     */
    protected function application(): array
    {
        if (! $this->can('App Settings')) {
            return [];
        }

        return [
            $this->item(
                'Application Configuration',
                'fa-server',
                'admin.basic-settings.edit'
            ),

            $this->item(
                'Application Logo',
                'fa-image',
                'admin.logo.edit'
            ),

            $this->item(
                'Email Settings',
                'fas fa-envelope',
                'admin.email-settings.edit'
            ),
        ];
    }

    /**
     * Application maintenance and diagnostics.
     */
    protected function maintenance(): array
    {
        if (! $this->can('App Settings')) {
            return [];
        }

        return [
            $this->item(
                'System Status',
                'temperature-three-quarters',
                'maint.status.index'
            ),

            $this->item(
                'Logs',
                'fa-bug',
                'maint.logs.index'
            ),

            $this->item(
                'Backups',
                'fa-hdd',
                'maint.backups.index'
            ),
        ];
    }

    /**
     * Determine whether the current user has a permission.
     */
    protected function can(string $permission): bool
    {
        return $this->checkPermission($this->user, $permission);
    }

    /**
     * Build a menu item.
     */
    protected function item(string $label, string $icon, string $routeName): array
    {
        return [
            'label' => $label,
            'icon' => $icon,
            'route' => route($routeName),
        ];
    }
}

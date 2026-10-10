<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import UserRoleForm from "@/features/user/forms/UserRoleForm.vue";

const props = defineProps<{
    permissionList: UserRolePermissionCategory;
    permissionValues: UserRolePermission[];
    baseRole?: UserRole;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <Card>
            <template #title>
                <div class="flex gap-2">
                    <div class="flex items-center">
                        <h1>
                            <fa-icon icon="fa-users-cog" />
                        </h1>
                    </div>
                    <div class="grow">
                        <h3 class="text-black">Create New User Role</h3>
                        <p class="text-muted">
                            Enter a name and description for the role, then
                            select the necessary permissions.
                        </p>
                    </div>
                </div>
            </template>
            <UserRoleForm
                :permission-list="permissionList"
                :permission-values="permissionValues"
                :base-role="baseRole"
            />
        </Card>
    </div>
</template>

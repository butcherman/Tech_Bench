<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import Card from "@/core/components/Card.vue";
import UserRoleForm from "@/features/user/forms/UserRoleForm.vue";

defineOptions({ layout: AppLayout });

const props = defineProps<{
    permissionList: UserRolePermissionCategory;
    permissionValues: UserRolePermission[];
    baseRole?: UserRole;
}>();
</script>

<template>
    <div>
        <div class="border-b border-slate-300 pb-2 mb-2">
            <h1 class="text-black">Modify User Role</h1>
            <p class="text-muted">
                Enter a name and description for the role, then select the
                necessary permissions.
            </p>
        </div>
        <Card>
            <UserRoleForm
                :permission-list="permissionList"
                :permission-values="permissionValues"
                :edit-role="baseRole"
            />
        </Card>
    </div>
</template>

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
            <UserRoleForm
                :permission-list="permissionList"
                :permission-values="permissionValues"
                :base-role="baseRole"
            />
        </Card>
    </div>
</template>

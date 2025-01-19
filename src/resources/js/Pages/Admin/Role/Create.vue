<template>
    <Card title="Create New Role" class="w-full md:w-3/4 justify-self-center">
        <UserRoleForm
            :permission-list="permissionList"
            :permission-values="permissionValues"
            :base-role="baseRole"
        />
    </Card>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import UserRoleForm from "@/Forms/Admin/User/UserRoleForm.vue";

defineProps<{
    permissionList: userRolePermissionGroup;
    permissionValues: userRolePermission[];
    baseRole?: userRole;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

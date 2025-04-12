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

<template>
    <div class="flex justify-center">
        <Card title="Modify Role" class="tb-card">
            <UserRoleForm
                :permission-list="permissionList"
                :permission-values="permissionValues"
                :base-role="baseRole"
                edit
            />
        </Card>
    </div>
</template>

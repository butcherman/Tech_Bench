<template>
    <div class="row justify-content-center">
        <Head title="Create New Role" />
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Create New Role</div>
                    <UserRoleForm
                        :permission-list="permissionList"
                        :permission-values="permissionValues"
                        :base-role="baseRole"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
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

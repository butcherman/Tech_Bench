<template>
    <Head title="Create New Role" />
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Create New Role</div>
                    <RoleForm
                        :permission-list="permissionList"
                        :base-role="baseRole"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import RoleForm from "@/Forms/Admin/User/RoleForm.vue";

defineProps<{
    permissionList: { [key: string]: any[] };
    baseRole?: userRole;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

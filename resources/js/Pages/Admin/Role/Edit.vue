<template>
    <Head title="Edit Role" />
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Edit Role</div>
                    <RoleForm
                        :permission-list="permissionList"
                        :base-role="role"
                        edit
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
    role: userRole;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

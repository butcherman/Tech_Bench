<template>
    <div>
        <h5 class="text-center">Create New Role</h5>
        <p class="text-center">
            Use the following form to create a new User Role that can be
            assigned to users. This will determine the permissions that the user
            has throughout the Tech Bench.
        </p>
        <AdminUserUserRoleForm />
    </div>
</template>

<script setup lang="ts">
import AdminUserUserRoleForm from "../Forms/admin.user.userRoleForm.vue";
</script>

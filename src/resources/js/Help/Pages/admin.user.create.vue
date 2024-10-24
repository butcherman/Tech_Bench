<template>
    <div>
        <h5 class="text-center">Create User</h5>
        <p class="text-center">
            New Users can be created here. After a user is created, they will be
            sent an email with a link to setup their password and activate their
            account.
        </p>
        <AdminUserUserForm />
    </div>
</template>

<script setup lang="ts">
import AdminUserUserForm from "../Forms/admin.user.userForm.vue";
</script>

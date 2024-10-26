<template>
    <div>
        <h5 class="text-center">User Settings</h5>
        <p class="text-center">
            User Authentication Settings are modified here. Options for
            Two-Factor Authentication and Single Sign On via Office 365 can be
            enabled and configured here.
        </p>
        <AdminUserUserSettingsForm />
    </div>
</template>

<script setup lang="ts">
import AdminUserUserSettingsForm from "../Forms/admin.user.userSettingsForm.vue";
</script>

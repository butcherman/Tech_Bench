<template>
    <div>
        <h5 class="text-center">Email Settings</h5>
        <p class="text-center">
            Use this form to update the Email Settings for sending notifications
            to users.
        </p>
        <p class="text-center">
            Use the Send Test Email button to test the saved email settings.
        </p>
        <emailSettingsForm />
    </div>
</template>

<script setup lang="ts">
import emailSettingsForm from "../Forms/admin.config.emailSettingsForm.vue";
</script>

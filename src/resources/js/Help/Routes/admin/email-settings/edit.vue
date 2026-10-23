<script setup lang="ts">
import EmailConfigForm from "@/Help/Forms/Config/EmailConfigForm.vue";

const title = "Email Settings";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p>
            Use this form to update the Email Settings for sending notifications
            to users.
        </p>
        <p>Use the Send Test Email button to test the saved email settings.</p>
        <EmailConfigForm />
    </div>
</template>

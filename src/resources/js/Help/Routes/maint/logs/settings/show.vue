<script setup lang="ts">
import LogSettingsForm from "@/Help/Forms/Maintenance/LogSettingsForm.vue";

const title = "Log Settings";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p class="text-center">
            Use this form to modify settings for log files.
        </p>
        <LogSettingsForm />
    </div>
</template>

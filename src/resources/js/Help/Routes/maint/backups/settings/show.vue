<script setup lang="ts">
import BackupSettingsForm from "@/Help/Forms/Maintenance/BackupSettingsForm.vue";

const title = "Help";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <p class="text-center">
            All backup settings can be controlled through this form.
        </p>
        <BackupSettingsForm />
    </div>
</template>

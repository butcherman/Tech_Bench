<script setup lang="ts">
import FileLinkSettingsForm from "@/Help/Forms/FileLink/FileLinkSettingsForm.vue";

const title = "Help";

const getTitle = () => {
    return title;
};

defineExpose({ getTitle });
</script>

<template>
    <div class="flex flex-col items-center">
        <div class="md:w-3/4 text-center">
            <p class="text-center">
                Use this form to adjust the Global File Link Settings
            </p>
        </div>
        <FileLinkSettingsForm />
    </div>
</template>

<script setup lang="ts">
import BaseFileUploadInput from "@/core/forms/components/baseInputs/BaseFileUploadInput.vue";
import BaseVueForm from "@/core/forms/components/BaseVueForm.vue";
import { useTemplateRef } from "vue";

defineEmits<{
    success: [];
}>();

const dropzone = useTemplateRef("file-upload-input");

const processFileQueue = () => {
    dropzone.value?.processQueue();
};
</script>

<template>
    <BaseVueForm
        name="form"
        @success="$emit('success')"
        @submit="processFileQueue"
    >
        <BaseFileUploadInput
            ref="file-upload-input"
            purpose="logo"
            @success="$emit('success')"
        >
            <template #upload-message>
                <p class="font-medium">Drop your logo here</p>
                <p class="text-sm text-gray-500">or click to select a file</p>
                <p class="mt-2 text-xs text-gray-500">PNG, JPG, GIF, or BMP</p>
            </template>
        </BaseFileUploadInput>
    </BaseVueForm>
</template>

<script setup lang="ts">
import BaseFileUploadInput from "@/core/forms/components/baseInputs/BaseFileUploadInput.vue";
import BaseVueForm from "@/core/forms/components/BaseVueForm.vue";
import { router } from "@inertiajs/vue3";
import { store } from "@/wayfinder/routes/maint/backups/upload";
import { useTemplateRef } from "vue";

const dropzone = useTemplateRef<typeof BaseFileUploadInput>("dropzone-input");

const processFileQueue = () => {
    console.log("process");
    dropzone.value?.processQueue();
};

const onQueueCompleted = (fileList: string[]) => {
    console.log(fileList);

    const formData = {
        upload_id: fileList[0],
    };

    router.post(store.url(), formData, {
        onSuccess: () => dropzone.value?.resetInput(),
    });
};
</script>

<template>
    <BaseVueForm
        name="upload-backup-form"
        submit-text="Upload"
        @submit="processFileQueue"
    >
        <BaseFileUploadInput
            ref="dropzone-input"
            purpose="backup"
            :accepted-files="['application/x-zip-compressed']"
            @queue-completed="onQueueCompleted"
        >
            <template #upload-message>
                <p class="font-medium">Upload Backup by Dragging Here</p>
                <p class="text-sm text-gray-500">or click to select a file</p>
                <p class="mt-2 text-xs text-gray-500">ZIP</p>
            </template>
        </BaseFileUploadInput>
    </BaseVueForm>
</template>

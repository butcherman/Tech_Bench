<script setup lang="ts">
import BaseFileUploadInput from "@/core/forms/components/baseInputs/BaseFileUploadInput.vue";
import BaseVueForm from "@/core/forms/components/BaseVueForm.vue";
import { useTemplateRef } from "vue";
import { update } from "@/wayfinder/routes/admin/logo";
import { router } from "@inertiajs/vue3";

defineEmits<{
    success: [];
}>();

const dropzone = useTemplateRef("file-upload-input");

const processFileQueue = () => {
    dropzone.value?.processQueue();
};

const onQueueCompleted = (fileList: string[]) => {
    console.log(fileList);
    console.log("submitting new logo");

    const formData = {
        upload_id: fileList[0],
    };

    router.post(update.url(), formData, {
        // preserveScroll: true,
        // only: props.only,
        onSuccess: (res) => console.log("success", res),
        // onError: (errors) => handleErrors(errors),
        onFinish: () => {
            console.log("finished");
        },
    });
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
            class="h-full"
            purpose="logo"
            :accepted-files="[
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/bmp',
            ]"
            @queue-completed="onQueueCompleted"
        >
            <template #upload-message>
                <p class="font-medium">Drop new logo here</p>
                <p class="text-sm text-gray-500">or click to select a file</p>
                <p class="mt-2 text-xs text-gray-500">PNG, JPG, GIF, or BMP</p>
            </template>
        </BaseFileUploadInput>
    </BaseVueForm>
</template>

<script setup lang="ts">
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Collapse from "@/Components/_Base/Collapse.vue";
import DropzoneInput from "./DropzoneInput.vue";
import EllipsisLoader from "@/Components/_Base/Loaders/EllipsisLoader.vue";
import okModal from "@/Modules/okModal";
import Overlay from "../../Components/_Base/Loaders/Overlay.vue";
import { Message } from "primevue";
import { computed, ref, useTemplateRef } from "vue";
import { useForm } from "vee-validate";
import { handleAxiosError } from "@/Composables/axiosWrapper.module";
import type { DropzoneFile } from "dropzone";

interface formData {
    [key: string]: string;
}

interface dropzoneError {
    file?: DropzoneFile;
    status: number | undefined;
    message: laravelValidationErrors;
}

const emit = defineEmits<{
    submitting: [formData];
    success: [];
    hasErrors: dropzoneError[];
    canceled: [];
    fileAdded: [DropzoneFile];
    fileRemoved: [DropzoneFile];
}>();

const props = defineProps<{
    initialValues: { [key: string]: any };
    submitRoute: string;
    validationSchema: object;
    fullPageOverlay?: boolean;
    hideOverlay?: boolean;
    submitIcon?: string;
    submitText?: string;
    fileRequired?: boolean;
    maxFiles?: number;
    acceptedFiles?: [string];
    hideFileInput?: boolean;
    uploadMessage?: string;
}>();

/*
|-------------------------------------------------------------------------------
| Form State
|-------------------------------------------------------------------------------
*/
const isSubmitting = ref<boolean>(false);
const submitText = computed<string>(() => props.submitText ?? "Submit");
const isDirty = computed<boolean>(() => meta.value.dirty);

/*
|-------------------------------------------------------------------------------
| Dropzone File Data/Events
|-------------------------------------------------------------------------------
*/
const dropzoneInput = useTemplateRef("dropzone-input");

const totalUploadProgress = ref<number>(0);

const onTotalUploadProgress = (uploadProgress: {
    progress: number;
    totalBytes: number;
    bytesSent: number;
}): void => {
    totalUploadProgress.value = Math.floor(uploadProgress.progress);
};

const onCancelUpload = (): void => {
    dropzoneInput.value?.cancelUpload();
    okModal("Upload Canceled");
    isSubmitting.value = false;

    emit("canceled");
};

/*
|-------------------------------------------------------------------------------
| Vee-Validate
|-------------------------------------------------------------------------------
*/
const {
    handleSubmit,
    setFieldValue,
    setFieldError,
    values,
    resetForm,
    meta,
    handleReset,
} = useForm({
    validationSchema: props.validationSchema,
    initialValues: props.initialValues,
});

/*
|-------------------------------------------------------------------------------
| Submit the Form
|-------------------------------------------------------------------------------
*/
const onSubmit = handleSubmit((form: formData): void => {
    if (dropzoneInput.value?.validate()) {
        uncaughtErrors.value = [];
        isSubmitting.value = true;
        dropzoneInput.value.process(form);

        emit("submitting", form);
    }
});

/*
|-------------------------------------------------------------------------------
| Handle Errors
|-------------------------------------------------------------------------------
*/
const uncaughtErrors = ref<string[]>([]);

const handleErrors = (formErrors: dropzoneError): void => {
    emit("hasErrors", formErrors);

    isSubmitting.value = false;

    if (formErrors.status === 422) {
        for (const [key, value] of Object.entries(formErrors.message?.errors)) {
            let fieldVal = getFieldValue(key);
            if (fieldVal) {
                setFieldError(key, value);
            } else {
                for (const val of value) {
                    uncaughtErrors.value.push(val);
                }
            }
        }

        return;
    }

    if (formErrors.status === 0) {
        return;
    }

    handleAxiosError(formErrors);
};

/*
|---------------------------------------------------------------------------
| Expose Component Methods
|---------------------------------------------------------------------------
*/
const getFieldValue = (field: string): any => {
    return values[field as keyof typeof values];
};

defineExpose({
    getFieldValue,
    setFieldValue,
    setFieldError,
    resetForm,
    handleReset,
    isDirty,
    isSubmitting,
    values,
});
</script>

<template>
    <Overlay
        :loading="isSubmitting && !hideOverlay"
        :full-page="fullPageOverlay"
        class="h-full"
    >
        <template #loader>
            <div class="w-60 md:w-96">
                <AtomLoader />
                <div v-if="dropzoneInput?.hasFile">
                    <div class="flex justify-center">
                        <div class="flex items-center">
                            Uploading
                            <EllipsisLoader class="w-3" />
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-6">
                        <div
                            class="bg-blue-400 h-full rounded-full text-center text-white transition-[width] duration-700"
                            :style="`width: ${totalUploadProgress}%`"
                        >
                            {{ totalUploadProgress }}%
                        </div>
                    </div>
                    <BaseButton
                        class="w-full my-4"
                        icon="ban"
                        text="Cancel Upload"
                        variant="danger"
                        @click="onCancelUpload"
                    />
                </div>
            </div>
        </template>
        <form
            ref="file-form"
            class="vld-parent h-full flex flex-col z-10"
            novalidate
            v-focustrap
            @submit.prevent="onSubmit"
        >
            <div v-if="uncaughtErrors.length" class="flex-none my-4">
                <Message
                    v-for="err in uncaughtErrors"
                    severity="error"
                    size="large"
                >
                    {{ err }}
                </Message>
            </div>
            <div class="grow">
                <slot />
                <Collapse :show="!hideFileInput">
                    <DropzoneInput
                        ref="dropzone-input"
                        :accepted-files="acceptedFiles"
                        :max-files="maxFiles || 1"
                        :required="fileRequired"
                        :upload-url="submitRoute"
                        :upload-message="uploadMessage"
                        @error="handleErrors"
                        @file-added="$emit('fileAdded', $event)"
                        @file-removed="$emit('fileRemoved', $event)"
                        @success="$emit('success')"
                        @total-upload-progress="onTotalUploadProgress"
                    />
                </Collapse>
                <slot name="after-file" />
            </div>
            <div class="flex-none text-center mt-4">
                <BaseButton
                    class="w-3/4"
                    type="submit"
                    variant="primary"
                    :icon="submitIcon"
                    :text="submitText"
                >
                    <span v-if="isSubmitting">
                        <fa-icon icon="spinner" class="fa-spin-pulse" />
                    </span>
                </BaseButton>
            </div>
            <slot name="after-form" />
        </form>
    </Overlay>
</template>

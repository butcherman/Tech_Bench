<script setup lang="ts">
// TODO - Verify
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Collapse from "@/Components/_Base/Collapse.vue";
import DropzoneInput from "./DropzoneInput.vue";
import okModal from "@/Modules/okModal";
import Overlay from "../../Components/_Base/Loaders/Overlay.vue";
import { computed, ref, useTemplateRef } from "vue";
import { Message } from "primevue";
import { useForm } from "vee-validate";
import type { DropzoneFile } from "dropzone";

interface formData {
    [key: string]: string;
}

interface formError {
    message: {
        errors: { [key: string]: string[] };
        message: string;
    };
    status: number;
}

const emit = defineEmits([
    "submitting",
    "success",
    "has-errors",
    "canceled",
    "values",
    "file-added",
    "file-removed",
    "queue-complete",
]);

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
    acceptedFiles?: string[];
    hideFileInput?: boolean;
    inertiaSubmit?: boolean;
    uploadMessage?: string;
}>();

const isSubmitting = ref<boolean>(false);
const submitText = computed<string>(() => props.submitText ?? "Submit");
const isDirty = computed<boolean>(() => meta.value.dirty);

const showInput = computed<boolean>(() => !props.hideFileInput);

/*
|-------------------------------------------------------------------------------
| Dropzone File Upload
|-------------------------------------------------------------------------------
*/
const dropzoneInput = useTemplateRef("dropzone-input");
const onFileAdded = (file: DropzoneFile): void => {
    emit("file-added", file);
};
const onFileRemoved = (file: DropzoneFile): void => {
    emit("file-removed", file);
};
const onCancel = (): void => {
    dropzoneInput.value?.cancelUpload();
    okModal("Upload Canceled");
    isSubmitting.value = false;
    emit("canceled");
};

/**
 * Reset Form and remove dropzone files
 */
const resetFileForm = (): void => {
    isSubmitting.value = false;
    resetForm();
    dropzoneInput.value?.reset();
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
        emit("submitting");

        isSubmitting.value = true;
        dropzoneInput.value.process(form);
    }
});

/*
|-------------------------------------------------------------------------------
| Handle Errors
|-------------------------------------------------------------------------------
*/
const uncaughtErrors = ref<string[]>([]);
const handleErrors = (formErrors: formError) => {
    emit("has-errors", formErrors);

    isSubmitting.value = false;

    if (formErrors.status === 422) {
        for (const [key, value] of Object.entries(formErrors.message.errors)) {
            console.log(key, value);

            for (const v in value) {
                setFieldError(key, v);
            }
        }
    } else {
        alert(formErrors.message);
    }
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
    resetFileForm,
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
        <form
            class="vld-parent h-full flex flex-col"
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
                <Collapse :show="showInput">
                    <DropzoneInput
                        ref="dropzone-input"
                        paramName="file"
                        :accepted-files="acceptedFiles"
                        :max-files="maxFiles || 1"
                        :required="fileRequired"
                        :upload-url="submitRoute"
                        :upload-message="uploadMessage"
                        @error="handleErrors"
                        @file-added="onFileAdded"
                        @file-removed="onFileRemoved"
                        @success="$emit('success')"
                    />
                </Collapse>
                <div>
                    <slot name="after-file" />
                </div>
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
            <div class="flex-none text-center mt-4">
                <slot name="cancel">
                    <BaseButton
                        v-if="isSubmitting"
                        class="w-3/4 pointer"
                        text="Cancel Upload"
                        type="button"
                        variant="danger"
                        :disabled="!isSubmitting"
                        @click="onCancel"
                    />
                </slot>
            </div>
        </form>
    </Overlay>
</template>

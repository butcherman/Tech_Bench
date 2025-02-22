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
            </div>
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
            <div>
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

<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Overlay from "../../Components/_Base/Loaders/Overlay.vue";
import { Message } from "primevue";
import { computed, ref, useTemplateRef } from "vue";
import { useForm } from "vee-validate";
import { useForm as useInertiaForm } from "@inertiajs/vue3";
import DropzoneInput from "./DropzoneInput.vue";
import type { DropzoneFile } from "dropzone";
import okModal from "@/Modules/okModal";

interface formData {
    [key: string]: string;
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

/*
 |-------------------------------------------------------------------------------
 | Dropzone File Upload
 |-------------------------------------------------------------------------------
 */
const dropzoneInput = useTemplateRef("dropzone-input");
const onFileAdded = (file: DropzoneFile) => {
    emit("file-added", file);
};
const onFileRemoved = (file: DropzoneFile) => {
    emit("file-removed", file);
};
const onCancel = () => {
    dropzoneInput.value?.cancelUpload();
    okModal("Upload Canceled");
    isSubmitting.value = false;
    emit("canceled");
};

/**
 * Reset Form and remove dropzone files
 */
const resetFileForm = () => {
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
const handleErrors = (
    originalForm: formData,
    formErrors: Partial<Record<string | number, string>>
) => {
    emit("has-errors", formErrors);
    const formKeys = Object.keys(originalForm);

    // Cycle through errors and assign to proper field values
    for (const [key, value] of Object.entries(formErrors)) {
        if (value) {
            if (typeof value === "object") {
                handleErrors(originalForm, value);
            } else {
                if (formKeys.indexOf(key) != -1) {
                    setFieldError(key, value);
                } else {
                    // Errors that are not attache to input are displayed as alert
                    uncaughtErrors.value.push(value);
                }
            }
        }
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
});
</script>

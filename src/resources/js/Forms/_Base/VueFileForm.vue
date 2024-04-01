<template>
    <div>
        <form class="vld-parent" @submit.prevent="onSubmit" novalidate>
            <Loading
                :active="isSubmitting && !hideOverlay"
                :is-full-page="false"
            >
                <TrinityRingsLoader />
            </Loading>
            <div v-if="errorAlerts.length">
                <div
                    v-for="alert in errorAlerts"
                    class="alert alert-danger text-center"
                >
                    {{ alert }}
                </div>
            </div>
            <slot />
            <DropzoneInput
                ref="dropzoneInput"
                paramName="file"
                :upload-url="submitRoute"
                :max-files="maxFiles || 1"
                :required="fileRequired"
                @file-added="onFileAdded"
                @file-removed="onFileRemoved"
                @success="$emit('success')"
            />
            <slot name="after-file" />
            <slot name="submit">
                <SubmitButton
                    v-if="!hideSubmit"
                    :submitted="isSubmitting"
                    class="mt-auto"
                    :text="submitText"
                    :btn-variant="submitVariant"
                />
            </slot>
        </form>
        <slot name="cancel">
            <button
                type="button"
                class="btn btn-danger my-2 w-100"
                @click="onCancel"
            >
                Cancel Upload
            </button>
        </slot>
    </div>
</template>

<script setup lang="ts">
import SubmitButton from "@/Components/_Base/Buttons/SubmitButton.vue";
import Loading from "vue3-loading-overlay";
import TrinityRingsLoader from "@/Components/_Base/Loaders/TrinityRingsLoader.vue";
import DropzoneInput from "./DropzoneInput.vue";
import { ref, computed } from "vue";
import { useForm } from "vee-validate";

//  Overlay Styling
import "vue3-loading-overlay/dist/vue3-loading-overlay.css";
import { DropzoneFile } from "dropzone";
import okModal from "@/Modules/okModal";

interface formData {
    [key: string]: string;
}

const emit = defineEmits([
    "submitting",
    "success",
    "canceled",
    "has-errors",
    "values",
    "file-added",
    "file-removed",
]);
const props = defineProps<{
    validationSchema: object;
    initialValues: { [key: string]: any };
    submitRoute: string;
    submitText?: string;
    submitVariant?: "primary" | "info" | "success" | "danger" | "warning";
    hideOverlay?: boolean;
    hideSubmit?: boolean;
    testing?: boolean;
    fileRequired?: boolean;
    maxFiles?: number;
}>();

/*******************************************************************************
 * Dropzone File Upload
 *******************************************************************************/
const dropzoneInput = ref<InstanceType<typeof DropzoneInput> | null>(null);
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

/*******************************************************************************
 * Vee-Validate initialization
 *******************************************************************************/
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

const isDirty = computed(() => meta.value.dirty);

/*******************************************************************************
 * Handle the Form Submission
 *******************************************************************************/
const isSubmitting = ref<boolean>(false);
const onSubmit = handleSubmit((form): void => {
    clearErrorAlert();
    if (dropzoneInput.value?.validate()) {
        emit("submitting");
        isSubmitting.value = true;
        /**
         * During development, we can set the testing flag to only return the form
         * values, but not actually submit the form
         */
        if (props.testing) {
            emit("values", form);
            console.log("Form Route", props.submitRoute);
            console.log("Form Method", "post");
            console.log("Form Values", form);
            isSubmitting.value = false;
        } else {
            console.log("good to go");
            isSubmitting.value = true;
            dropzoneInput.value.process(form);
            console.log("uploading");
        }
    }
});

const handleErrors = (
    originalForm: formData,
    formErrors: Partial<Record<string | number, string>>
) => {
    emit("has-errors");
    const formKeys = Object.keys(originalForm);

    for (const [key, value] of Object.entries(formErrors)) {
        if (value) {
            console.log(typeof value);
            if (typeof value === "object") {
                handleErrors(originalForm, value);
            } else {
                if (formKeys.indexOf(key) != -1) {
                    setFieldError(key, value);
                } else {
                    pushErrorAlert(value);
                }
            }
        }
    }
};

const getFieldValue = (field: string): any => {
    return values[field as keyof typeof values];
};

/*******************************************************************************
 * Errors to be triggered above the form (used for errors without field name)
 *******************************************************************************/
const errorAlerts = ref<string[]>([]);
function pushErrorAlert(alert: string): void {
    errorAlerts.value.push(alert);
}
function clearErrorAlert(): void {
    errorAlerts.value = [];
}

defineExpose({
    getFieldValue,
    setFieldValue,
    setFieldError,
    resetForm,
    handleReset,
    isDirty,
    isSubmitting,
});
</script>

<template>
    <form class="vld-parent" @submit="onSubmit" novalidate>
        <Loading :active="isSubmitting && !hideOverlay" :is-full-page="false">
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
        <slot name="submit" v-if="!hideSubmit">
            <SubmitButton
                :submitted="isSubmitting"
                class="mt-auto"
                :text="submitText"
                :btn-variant="submitVariant"
            />
        </slot>
    </form>
</template>

<script setup lang="ts">
import SubmitButton from "@/Components/_Base/Buttons/SubmitButton.vue";
import Loading from "vue3-loading-overlay";
import TrinityRingsLoader from "@/Components/_Base/Loaders/TrinityRingsLoader.vue";
import { ref, computed } from "vue";
import { useForm } from "vee-validate";
//  Overlay Styling
import "vue3-loading-overlay/dist/vue3-loading-overlay.css";

const emit = defineEmits(["submit"]);
const props = defineProps<{
    validationSchema: object;
    initialValues: { [key: string]: any };
    submitText?: string;
    submitVariant?: "primary" | "info" | "success" | "danger" | "warning";
    manualSubmit?: boolean;
    hideSubmit?: boolean;
    hideOverlay?: boolean;
}>();

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

const onSubmit = handleSubmit((form: any): void => {
    if (!props.manualSubmit) {
        isSubmitting.value = true;
    }

    emit("submit", form);
});

const getFieldValue = (field: string): any => {
    return values[field as keyof typeof values];
};

const isDirty = computed(() => meta.value.dirty);

/*******************************************************************************
 * Submission status of the form
 *******************************************************************************/
const isSubmitting = ref<boolean>(false);
function triggerSubmit(): void {
    isSubmitting.value = true;
}
function endSubmit(): void {
    isSubmitting.value = false;
}

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

/*******************************************************************************
 * Errors triggered by server side validation
 *******************************************************************************/
function setValidationErrors(errorData: { [key: string]: string }): void {
    const errKeys = Object.keys(errorData);
    errKeys.forEach((field) => {
        setFieldError(field, errorData[field]);
    });
}

defineExpose({
    endSubmit,
    getFieldValue,
    setFieldValue,
    setFieldError,
    onSubmit,
    triggerSubmit,
    resetForm,
    handleReset,
    pushErrorAlert,
    clearErrorAlert,
    setValidationErrors,
    isDirty,
    isSubmitting,
});
</script>

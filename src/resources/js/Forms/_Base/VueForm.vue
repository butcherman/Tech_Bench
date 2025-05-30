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
</template>

<script setup lang="ts">
import SubmitButton from "@/Components/_Base/Buttons/SubmitButton.vue";
import Loading from "vue3-loading-overlay";
import TrinityRingsLoader from "@/Components/_Base/Loaders/TrinityRingsLoader.vue";
import { ref, computed } from "vue";
import { useForm } from "vee-validate";
import { useForm as useInertiaForm } from "@inertiajs/vue3";

//  Overlay Styling
import "vue3-loading-overlay/dist/vue3-loading-overlay.css";

interface formData {
    [key: string]: string;
}

const emit = defineEmits(["submitting", "success", "has-errors", "values"]);
const props = defineProps<{
    validationSchema: object;
    initialValues: { [key: string]: any };
    submitMethod: "post" | "put" | "delete";
    submitRoute: string;
    submitText?: string;
    submitVariant?: "primary" | "info" | "success" | "danger" | "warning";
    hideOverlay?: boolean;
    hideSubmit?: boolean;
    testing?: boolean;
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

const isDirty = computed(() => meta.value.dirty);

/*******************************************************************************
 * Handle the Form Submission
 *******************************************************************************/
const isSubmitting = ref<boolean>(false);
const onSubmit = handleSubmit((form): void => {
    isSubmitting.value = true;
    emit("submitting");
    const formData = useInertiaForm(form);
    clearErrorAlert();

    /**
     * During development, we can set the testing flag to only return the form
     * values, but not actually submit the form
     */
    if (props.testing) {
        emit("values", form);
        console.log("Form Route", props.submitRoute);
        console.log("Form Method", props.submitMethod);
        console.log("Form Values", form);
        isSubmitting.value = false;
    } else {
        formData.submit(props.submitMethod, props.submitRoute, {
            preserveScroll: true,
            onFinish: () => (isSubmitting.value = false),
            onSuccess: () => emit("success"),
            onError: () => handleErrors(form, formData.errors),
            headers: {
                "X-Socket-Id": Echo.socketId(),
            },
        });
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

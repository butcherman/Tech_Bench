<template>
    <form class="vld-parent" @submit.prevent="onSubmit" novalidate>
        <Overlay :loading="isSubmitting && !hideOverlay">
            <slot />
            <div class="w-full p-3">
                <slot name="submit">
                    <button
                        type="submit"
                        class="btn btn-blue w-full"
                        :disabled="isSubmitting"
                    >
                        {{ submitText }}
                    </button>
                </slot>
            </div>
        </Overlay>
    </form>
</template>

<script setup lang="ts">
import Overlay from "./Overlay.vue";
import { computed, ref } from "vue";
import { useForm } from "vee-validate";
import { useForm as useInertiaForm } from "@inertiajs/vue3";

interface formData {
    [key: string]: string;
}

const emit = defineEmits(["submitting", "success", "has-errors"]);
const props = defineProps<{
    validationSchema: object;
    initialValues: { [key: string]: any };
    submitMethod: "post" | "put" | "delete";
    submitRoute: string;
    submitText?: string;
    hideOverlay?: boolean;
}>();

const isSubmitting = ref<boolean>(false);
const submitText = computed(() => props.submitText ?? "Submit");
const isDirty = computed(() => meta.value.dirty);

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
const onSubmit = handleSubmit((form) => {
    isSubmitting.value = true;
    emit("submitting", form);

    const formData = useInertiaForm(form);
    formData.submit(props.submitMethod, props.submitRoute, {
        preserveScroll: true,
        onFinish: () => (isSubmitting.value = false),
        onSuccess: () => emit("success"),
        onError: () => handleErrors(form, formData.errors),
    });
});

/*
|-------------------------------------------------------------------------------
| Handle Errors
|-------------------------------------------------------------------------------
*/
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
                    // Values that are not part of the form are displayed as alert
                    // TODO - Push Alert
                    // pushErrorAlert(value);
                    console.log("field error - ", value);
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
    handleReset,
    isDirty,
    isSubmitting,
});
</script>

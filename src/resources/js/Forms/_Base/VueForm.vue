<template>
    <Overlay
        :loading="isSubmitting && !hideOverlay"
        :full-page="fullPageOverlay"
        class="h-full"
    >
        <form
            class="vld-parent h-full flex flex-col"
            @submit.prevent="onSubmit"
            novalidate
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
            <div class="flex-none text-center mt-4">
                <BaseButton
                    type="submit"
                    variant="primary"
                    class="w-3/4"
                    :text="submitText"
                    :icon="submitIcon"
                >
                    <span v-if="isSubmitting">
                        <fa-icon icon="spinner" class="fa-spin-pulse" />
                    </span>
                </BaseButton>
            </div>
        </form>
    </Overlay>
</template>

<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Overlay from "../../Components/_Base/Loaders/Overlay.vue";
import { Message } from "primevue";
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
    submitIcon?: string;
    hideOverlay?: boolean;
    fullPageOverlay?: boolean;
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
    uncaughtErrors.value = [];
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
    handleReset,
    isDirty,
    isSubmitting,
});
</script>

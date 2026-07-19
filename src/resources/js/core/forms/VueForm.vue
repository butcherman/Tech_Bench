<script setup lang="ts">
import SubmitButton from "../components/buttons/SubmitButton.vue";
import { computed, ref } from "vue";
import { useForm } from "vee-validate";
import { useForm as useInertiaForm } from "@inertiajs/vue3";

const emit = defineEmits<{
    submitting: [InertiaFormData];
    success: [];
}>();

const props = defineProps<{
    initialValues: InertiaFormData;
    name: string;
    submitMethod: "post" | "put" | "delete";
    submitRoute: string;
    validationSchema: object;

    fullPageOverlay?: boolean;
    hideOverlay?: boolean;
    only?: string[];
    submitIcon?: string;
    submitText?: string;
}>();

const isSubmitting = ref<boolean>(false);
const submitText = computed<string>(() => props.submitText ?? "Submit");
const isDirty = computed<boolean>(() => meta.value.dirty);
const uncaughtErrors = ref<string[]>([]);

const handleErrors = (
    formData: InertiaFormData,
    formErrors: InertiaFormErrors,
) => {
    const formKeys = Object.keys(formData);
    const errorList = Object.entries(formErrors);

    errorList.forEach((err) => {
        if (formKeys.includes(err[0])) {
            setFieldError(err[0], err[1]);
        } else {
            uncaughtErrors.value.push(err[1]);
        }
    });
};

/*
|-------------------------------------------------------------------------------
| Vee Validate Setup
|-------------------------------------------------------------------------------
*/
const { resetForm, setFieldError, setFieldValue, handleSubmit, meta, values } =
    useForm({
        validationSchema: props.validationSchema,
        initialValues: props.initialValues,
        name: props.name,
    });

/**
 * Return a specific field value
 */
const getFieldValue = (field: keyof InertiaFormData): any => {
    return values[field];
};

/*
|-------------------------------------------------------------------------------
| Handle the Submission of the Form
|-------------------------------------------------------------------------------
*/
const onSubmit = handleSubmit((form: InertiaFormData): void => {
    console.log("submitting");

    uncaughtErrors.value = [];
    isSubmitting.value = true;
    emit("submitting", form);

    // Use InertiaJS to Submit the form
    const formData = useInertiaForm<InertiaFormData>(form);
    formData.submit(props.submitMethod, props.submitRoute, {
        preserveScroll: true,
        only: props.only ?? [],
        onFinish: () => (isSubmitting.value = false),
        onSuccess: () => emit("success"),
        onError: () => handleErrors(form, formData.errors),
    });
});

/*
|-------------------------------------------------------------------------------
| Expose the necessary methods
|-------------------------------------------------------------------------------
*/
defineExpose({
    getFieldValue,
    setFieldError,
    setFieldValue,
    resetForm,
    isDirty,
    isSubmitting,
});
</script>

<template>
    <div>
        <form
            class="h-full flex flex-col"
            novalidate
            @submit.prevent="onSubmit"
        >
            <div v-if="uncaughtErrors.length">
                <div
                    v-for="err in uncaughtErrors"
                    :key="err"
                    class="bg-red-200 rounded-lg p-1 text-center my-2 text-red-800"
                >
                    {{ err }}
                </div>
            </div>
            <div class="grow">
                <slot />
            </div>
            <div>
                <SubmitButton
                    class="w-full"
                    :text="submitText"
                    :icon="submitIcon"
                >
                    <span v-if="isSubmitting">
                        <fa-icon icon="spinner" class="fa-spin-pulse" />
                    </span>
                </SubmitButton>
            </div>
        </form>
    </div>
</template>

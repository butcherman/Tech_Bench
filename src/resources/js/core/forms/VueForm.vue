<script setup lang="ts">
import { useForm } from "vee-validate";
import { useForm as useInertiaForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import SubmitButton from "../components/buttons/SubmitButton.vue";

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

const handleErrors = (formData: InertiaFormData, formErrors) => {
    console.log("handle errors", formData, formErrors);
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
            <div>uncaught errors go here...</div>
            <div class="grow">
                <slot />
            </div>
            <div>
                <SubmitButton
                    class="w-full"
                    :text="submitText"
                    :icon="submitIcon"
                />
            </div>
        </form>
    </div>
</template>

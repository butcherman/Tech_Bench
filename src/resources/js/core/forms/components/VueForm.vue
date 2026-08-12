<script setup lang="ts" generic="TFormData extends FormDataType<TFormData>">
import ValidatedVueForm from "./ValidatedVueForm.vue";
import { ref, useTemplateRef } from "vue";
import { GenericObject, useForm, Path } from "vee-validate";
import { router } from "@inertiajs/vue3";
import type { FormDataType, Errors } from "@inertiajs/core";

type FormDataErrors = Errors;
type InitialValues<T extends GenericObject> = NonNullable<
    Parameters<typeof useForm<T>>[0]
>["initialValues"];

const emit = defineEmits<{
    submitting: [TFormData];
    success: [];
}>();

const props = defineProps<{
    initialValues: InitialValues<TFormData>;
    name: string;
    submitMethod: "post" | "put" | "delete";
    submitRoute: string;
    validationSchema: object;

    // Optional
    doNotReset?: boolean;
    fullPageOverlay?: boolean;
    hideOverlay?: boolean;
    only?: string[];
    submitIcon?: string;
    submitText?: string;
}>();

const validatedForm = useTemplateRef("validated-form");
const isSubmitting = ref<boolean>(false);
const uncaughtErrors = ref<string[]>([]);

const handleErrors = (formErrors: FormDataErrors) => {
    for (const [field, message] of Object.entries(formErrors)) {
        if (typeof message === "string") {
            validatedForm.value?.setFieldError(
                field as Path<TFormData>,
                message,
            );
        } else {
            uncaughtErrors.value.push(String(message));
        }
    }
};

/*
|-------------------------------------------------------------------------------
| Handle the Submission of the Form
|-------------------------------------------------------------------------------
*/
const onSubmit = (form: TFormData): void => {
    uncaughtErrors.value = [];
    isSubmitting.value = true;
    emit("submitting", form);

    router[props.submitMethod](props.submitRoute, form, {
        preserveScroll: true,
        only: props.only,
        onSuccess,
        onError: (errors) => handleErrors(errors),
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
};

/*
|-------------------------------------------------------------------------------
| Handle successful completion of the form
|-------------------------------------------------------------------------------
*/
const onSuccess = () => {
    if (!props.doNotReset) {
        validatedForm.value?.resetForm();
    }
    emit("success");
};

/*
|-------------------------------------------------------------------------------
| Expose the necessary methods
|-------------------------------------------------------------------------------
*/
defineExpose({
    getFieldValue: validatedForm.value?.getFieldValue,
    setFieldError: validatedForm.value?.setFieldError,
    setValue: validatedForm.value?.setValue,
    resetForm: validatedForm.value?.resetForm,
    isDirty: validatedForm.value?.isDirty,
    values: validatedForm.value?.values,
    isSubmitting,
});
</script>

<template>
    <ValidatedVueForm
        v-bind="props"
        ref="validated-form"
        :is-submitting="isSubmitting"
        @submit="onSubmit"
    >
        <template v-for="(_, slot) of $slots" #[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </ValidatedVueForm>
</template>

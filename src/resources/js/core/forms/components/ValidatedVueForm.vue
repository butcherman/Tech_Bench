<script setup lang="ts" generic="TFormData extends FormDataType<TFormData>">
import BaseVueForm from "./BaseVueForm.vue";
import { computed, readonly } from "vue";
import { GenericObject, useForm, Path, PathValue } from "vee-validate";
import type { FormDataType } from "@inertiajs/core";

type InitialValues<T extends GenericObject> = NonNullable<
    Parameters<typeof useForm<T>>[0]
>["initialValues"];

const emit = defineEmits<{
    submit: [TFormData];
}>();

const props = defineProps<{
    initialValues: InitialValues<TFormData>;
    name: string;
    isSubmitting: boolean;
    validationSchema: object;

    // Optional
    doNotReset?: boolean;
    errorList?: string[];
    fullPageOverlay?: boolean;
    hideOverlay?: boolean;
    only?: string[];
    submitIcon?: string;
    submitText?: string;
}>();

const isDirty = computed<boolean>(() => meta.value.dirty);

/*
|-------------------------------------------------------------------------------
| Vee Validate Setup
|-------------------------------------------------------------------------------
*/
const { resetForm, setFieldError, setFieldValue, handleSubmit, meta, values } =
    useForm<TFormData>({
        validationSchema: props.validationSchema,
        initialValues: props.initialValues,
        name: props.name,
    });

/**
 * Return a specific field value
 */
const getFieldValue = <K extends keyof TFormData>(field: K): TFormData[K] =>
    values[field];

/**
 * Assign a specific field value
 */
const setValue = (
    field: Path<TFormData>,
    value: PathValue<TFormData, Path<TFormData>>,
) => {
    setFieldValue(field, value);
};

/*
|-------------------------------------------------------------------------------
| Handle the Submission of the Form
|-------------------------------------------------------------------------------
*/
const onSubmit = handleSubmit((form): void => {
    emit("submit", form);
});

/*
|-------------------------------------------------------------------------------
| Expose the necessary methods
|-------------------------------------------------------------------------------
*/
defineExpose({
    getFieldValue,
    setFieldError,
    setValue,
    resetForm,
    isDirty,
    values: readonly(values),
});
</script>

<template>
    <BaseVueForm v-bind="props" @submit="onSubmit">
        <template v-for="(_, slot) of $slots" #[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </BaseVueForm>
</template>

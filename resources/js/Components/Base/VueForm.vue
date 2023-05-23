<template>
    <form class="vld-parent" @submit="onSubmit" novalidate>
        <Loading :active="isSubmitting" :is-full-page="false">
            <HalfCircleLoader />
        </Loading>
        <slot />
        <slot name="submit">
            <SubmitButton
                :submitted="isSubmitting"
                :text="submitText ? submitText : 'Submit'"
                class="mt-auto"
            />
        </slot>
    </form>
</template>

<script setup lang="ts">
import SubmitButton from "@/Components/Base/Input/SubmitButton.vue";
import Loading from "vue3-loading-overlay";
import HalfCircleLoader from "@/Components/Base/Loader/HalfCircleLoader.vue";
import { ref } from "vue";
import { useForm, useFieldArray } from "vee-validate";

import "vue3-loading-overlay/dist/vue3-loading-overlay.css";

const emit = defineEmits(["submit"]);
const props = defineProps<{
    validationSchema: object;
    initialValues?: object;
    submitText?: string;
}>();

const isSubmitting = ref(false);
const { handleSubmit, setFieldValue, setFieldError, values, resetForm } =
    useForm({
        validationSchema: props.validationSchema,
        initialValues: props.initialValues ? props.initialValues : {},
    });

const onSubmit = handleSubmit((form) => {
    isSubmitting.value = true;
    emit("submit", form);
});

const getFieldValue = (field: string) => {
    return values[field as keyof typeof values];
};

function _endSubmit() {
    isSubmitting.value = false;
}

defineExpose({
    endSubmit: _endSubmit,
    getFieldValue,
    setFieldValue,
    useFieldArray,
    setFieldError,
    onSubmit,
    resetForm,
});
</script>

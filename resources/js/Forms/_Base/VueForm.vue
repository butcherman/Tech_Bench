<template>
    <form class="vld-parent" @submit="onSubmit" novalidate>
        <Loading :active="isSubmitting" :is-full-page="false">
            <TrinityRingsLoader />
        </Loading>
        <slot />
        <slot name="submit">
            <SubmitButton
                :submitted="isSubmitting"
                class="mt-auto"
                :text="submitText"
            />
        </slot>
    </form>
</template>

<script setup lang="ts">
import SubmitButton from "@/Components/_Base/Buttons/SubmitButton.vue";
import Loading from "vue3-loading-overlay";
import TrinityRingsLoader from "@/Components/_Base/Loaders/TrinityRingsLoader.vue";
import { ref, computed } from "vue";
import { useForm, useFieldArray } from "vee-validate";
//  Overlay Styling
import "vue3-loading-overlay/dist/vue3-loading-overlay.css";

const emit = defineEmits(["submit"]);
const props = defineProps<{
    validationSchema: object;
    initialValues: { [key: string]: any };
    submitText?: string;
}>();

const isSubmitting = ref<boolean>(false);
const { handleSubmit, setFieldValue, setFieldError, values, resetForm, meta, handleReset } =
    useForm({
        validationSchema: props.validationSchema,
        initialValues: props.initialValues,
    });

const onSubmit = handleSubmit((form: any): void => {
    isSubmitting.value = true;
    emit("submit", form);
});

const getFieldValue = (field: string): string => {
    return values[field as keyof typeof values];
};

const isDirty = (computed(() => meta.value.dirty ));

function endSubmit(): void {
    isSubmitting.value = false;
}

defineExpose({
    endSubmit,
    getFieldValue,
    setFieldValue,
    useFieldArray,
    setFieldError,
    onSubmit,
    resetForm,
    handleReset,
    isDirty,
});
</script>

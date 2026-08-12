<script setup lang="ts">
import Overlay from "@/core/components/Overlay.vue";
import SubmitButton from "@/core/components/buttons/SubmitButton.vue";
import { computed } from "vue";

const emit = defineEmits<{
    submit: [];
}>();

const props = defineProps<{
    name: string;

    // Optional
    errorList?: string[];
    fullPageOverlay?: boolean;
    hideOverlay?: boolean;
    isSubmitting?: boolean;
    submitIcon?: string;
    submitText?: string;
}>();

const submitText = computed<string>(() => props.submitText ?? "Submit");
/*
|-------------------------------------------------------------------------------
| Handle the Submission of the Form
|-------------------------------------------------------------------------------
*/
const onSubmit = () => {
    emit("submit");
};
</script>

<template>
    <Overlay
        :loading="isSubmitting"
        :full-page="fullPageOverlay"
        class="h-full"
    >
        <form
            class="h-full flex flex-col gap-2"
            :name="name"
            novalidate
            @submit.prevent="onSubmit"
        >
            <div v-if="errorList && errorList.length">
                <div
                    v-for="err in errorList"
                    :key="err"
                    class="bg-red-200 rounded-lg p-1 text-center my-2 text-red-800"
                >
                    {{ err }}
                </div>
            </div>
            <div class="grow flex flex-col gap-2">
                <slot />
            </div>
            <div>
                <slot name="submit-button">
                    <SubmitButton
                        class="w-full"
                        :text="submitText"
                        :icon="submitIcon"
                    >
                        <span v-if="isSubmitting">
                            <fa-icon icon="spinner" class="fa-spin-pulse" />
                        </span>
                    </SubmitButton>
                </slot>
            </div>
        </form>
    </Overlay>
</template>

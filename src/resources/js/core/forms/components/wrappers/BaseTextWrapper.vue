<script setup lang="ts">
import InputWrapper from "./InputWrapper.vue";
import { useBaseInputHelper } from "../../composables/baseInputHelper";
import { useFormInputHelper } from "../../composables/formInputHelper";

const emit = defineEmits<{
    focus: [];
    blur: [];
    change: [any];
}>();

const props = defineProps<{
    name: string;

    label?: string;
    helpMessage?: string;
    variant?: InputVariant;
    placeholder?: string;
    helpVisible?: boolean;
    autocomplete?: string;
    disabled?: boolean;
}>();

const { hasFocus, errorMessage } = useBaseInputHelper(props, emit);
const { appendVariantStyle, prependVariantStyle } = useFormInputHelper(props);
</script>

<template>
    <InputWrapper
        :error-message="errorMessage"
        :help-message="helpMessage"
        :has-focus="hasFocus"
        :help-visible="helpVisible"
    >
        <div class="flex">
            <div
                v-if="$slots['prepend-input']"
                class="form-input-prepend text-muted"
                :class="[
                    prependVariantStyle,
                    { invalid: errorMessage?.length, 'has-focus': hasFocus },
                ]"
            >
                <slot name="prepend-input" />
            </div>
            <div class="grow">
                <div class="relative">
                    <slot />
                </div>
            </div>
            <div
                v-if="$slots['append-input']"
                class="form-input-append text-muted"
                :class="[
                    appendVariantStyle,
                    { invalid: errorMessage?.length, 'has-focus': hasFocus },
                ]"
            >
                <slot name="append-input" />
            </div>
        </div>
    </InputWrapper>
</template>

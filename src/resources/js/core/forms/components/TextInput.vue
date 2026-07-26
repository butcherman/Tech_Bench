<script setup lang="ts">
import InputWrapper from "./InputWrapper.vue";
import { useBaseInputHelper } from "../composables/baseInputHelper.js";
import { useFormInputHelper } from "../composables/formInputHelper.js";
import { useId } from "vue";

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

const inputId = useId();

const { hasFocus, onBlur, onFocus, errorMessage, value } = useBaseInputHelper(
    props,
    emit,
);
const { inputVariantStyle, appendVariantStyle, prependVariantStyle } =
    useFormInputHelper(props);
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
                    <input
                        v-model="value"
                        class="block peer form-input-base"
                        type="text"
                        :autocomplete="name"
                        :class="[inputVariantStyle]"
                        :disabled="disabled"
                        :id="inputId"
                        :placeholder="placeholder ?? ''"
                        :name="name"
                        @focus="onFocus"
                        @blur="onBlur"
                        @change="$emit('change', value)"
                    />
                    <label
                        :for="inputId"
                        class="form-label-base"
                        :class="{ 'bg-white!': variant === 'outlined' }"
                    >
                        {{ label }}
                    </label>
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

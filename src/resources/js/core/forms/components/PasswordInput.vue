<script setup lang="ts">
import InputWrapper from "./InputWrapper.vue";
import { useBaseInputHelper } from "../composables/baseInputHelper.js";
import { useFormInputHelper } from "../composables/formInputHelper.js";
import { computed, ref, useId } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
    change: [unknown];
}>();

const props = defineProps<{
    name: string;

    label?: string;
    helpMessage?: string;
    hideUnmask?: boolean;
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
const { inputVariantStyle } = useFormInputHelper(props);

/*
|-------------------------------------------------------------------------------
| Visibility of password
|-------------------------------------------------------------------------------
*/
const showPass = ref<boolean>(false);
const maskType = computed<"text" | "password">(() =>
    showPass.value ? "text" : "password",
);
const maskIcon = computed<"eye-slash" | "eye">(() =>
    showPass.value ? "eye-slash" : "eye",
);
</script>

<template>
    <InputWrapper
        :error-message="errorMessage"
        :help-message="helpMessage"
        :has-focus="hasFocus"
        :help-visible="helpVisible"
    >
        <div class="relative">
            <input
                v-model="value"
                class="block peer form-input-base"
                :type="maskType"
                :autocomplete="name"
                :class="[inputVariantStyle]"
                :id="inputId"
                :placeholder="placeholder ?? ''"
                :name="name"
                @focus="onFocus"
                @blur="onBlur"
                @change="$emit('change', value)"
            />
            <div
                v-if="!hideUnmask"
                class="absolute inset-e-1.5 bottom-1.5 text-muted pointer"
                @click="showPass = !showPass"
            >
                <fa-icon :icon="maskIcon" />
            </div>
            <label
                :for="inputId"
                class="form-label-base"
                :class="{ 'bg-white!': variant === 'outlined' }"
            >
                {{ label }}
            </label>
        </div>
    </InputWrapper>
</template>

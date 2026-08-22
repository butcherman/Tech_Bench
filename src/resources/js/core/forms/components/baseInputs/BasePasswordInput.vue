<script setup lang="ts">
import InputWrapper from "../wrappers/InputWrapper.vue";
import { computed, ref, useId } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";

defineSlots<{
    [key: string]: any;
}>();

const emit = defineEmits<{
    "update:value": [string];
    focus: [];
    blur: [];
    change: [string];
}>();

const props = defineProps<{
    name: string;
    value: string;

    autocomplete?: string;
    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    hideUnmask?: boolean;
    label?: string;
    placeholder?: string;
    variant?: InputVariant;
}>();

const { hasFocus, onFocus, onBlur, inputVariantStyle } = useInputHelper(
    props,
    emit,
);

const inputId = useId();
const inputValue = computed({
    get: () => props.value,
    set: (value) => emit("update:value", value),
});

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
    <InputWrapper v-bind="props" :has-focus="hasFocus" class="relative">
        <input
            v-model="inputValue"
            class="block peer form-input-base"
            :type="maskType"
            :autocomplete="name"
            :class="[
                inputVariantStyle,
                { invalid: errorMessage?.length, 'has-focus': hasFocus },
            ]"
            :disabled="disabled"
            :id="inputId"
            :placeholder="placeholder ?? ''"
            :name="name"
            @focus="onFocus"
            @blur="onBlur"
            @change="$emit('change', inputValue)"
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
    </InputWrapper>
</template>

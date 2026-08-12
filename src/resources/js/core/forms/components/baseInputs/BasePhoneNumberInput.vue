<script setup lang="ts">
import TextInputWrapper from "../wrappers/TextInputWrapper.vue";
import { computed, useId } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";

defineSlots<{
    [key: string]: any;
}>();

const emit = defineEmits<{
    "update:value": [string];
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;
    value: string;

    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
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
    get: () => formatPhone(props.value),
    set: (value) => emit("update:value", cleanPhone(value)),
});

const inputPlaceholder = computed(() =>
    props.placeholder ? props.placeholder : "(XXX) XXX-XXXX",
);

/**
 * Remove all extra values from the phone number
 */
const cleanPhone = (value: string) => {
    return value.replace(/\D/g, "").slice(0, 10);
};

/**
 * Format the phone number to a readable format
 */
const formatPhone = (value: string) => {
    const digits = cleanPhone(value);

    if (digits.length === 0) return "";

    if (digits.length <= 3) {
        return `(${digits}`;
    }

    if (digits.length <= 6) {
        return `(${digits.slice(0, 3)}) ${digits.slice(3)}`;
    }

    return `(${digits.slice(0, 3)}) ${digits.slice(3, 6)}-${digits.slice(6)}`;
};
</script>

<template>
    <TextInputWrapper v-bind="props" :has-focus="hasFocus">
        <template v-for="(_, slot) of $slots" #[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
        <input
            v-model="inputValue"
            autocomplete="tel"
            class="block peer form-input-base"
            inputmode="numeric"
            type="tel"
            :class="[
                inputVariantStyle,
                { invalid: errorMessage?.length, 'has-focus': hasFocus },
            ]"
            :disabled="disabled"
            :id="inputId"
            :placeholder="inputPlaceholder"
            :name="name"
            @focus="onFocus"
            @blur="onBlur"
        />
        <label
            :for="inputId"
            class="form-label-base"
            :class="{ 'bg-white!': variant === 'outlined' }"
        >
            {{ label }}
        </label>
    </TextInputWrapper>
</template>

<script setup lang="ts">
import InputWrapper from "../wrappers/InputWrapper.vue";
import { computed, useId } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";
import { useVariantHelper } from "@/core/composables/variantHelper.js";

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
    min: number;
    max: number;
    value: string;

    autocomplete?: string;
    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    hideValue?: boolean;
    label?: string;
    placeholder?: string;
    rangeVariant?: VariantType;
    valueText?: string;
}>();

const { getVariantBase } = useVariantHelper();
const { hasFocus, onFocus, onBlur } = useInputHelper(props, emit);

const inputId = useId();
const inputValue = computed({
    get: () => props.value,
    set: (value) => emit("update:value", value),
});
</script>

<template>
    <InputWrapper v-bind="props" :has-focus="hasFocus">
        <label :for="inputId" class="block mb-2 text-sm font-medium text-muted">
            {{ label }}
        </label>
        <input
            v-model="inputValue"
            v-tooltip.bottom="inputValue"
            class="w-full"
            type="range"
            :autocomplete="name"
            :class="[
                `accent-${getVariantBase(rangeVariant ?? 'primary')}`,
                { invalid: errorMessage?.length, 'has-focus': hasFocus },
            ]"
            :disabled="disabled"
            :id="inputId"
            :placeholder="placeholder ?? ''"
            :name="name"
            :min="min"
            :max="max"
            @focus="onFocus"
            @blur="onBlur"
            @change="$emit('change', inputValue)"
        />
        <div v-if="!hideValue" class="text-muted">
            <span v-if="valueText">{{ valueText }}</span>
            {{ inputValue }}
        </div>
    </InputWrapper>
</template>

<script setup lang="ts">
import InputWrapper from "./wrappers/InputWrapper.vue";
import { useId } from "vue";
import { useBaseInputHelper } from "../composables/baseInputHelper";
import { useVariantHelper } from "@/core/composables/variantHelper";

const emit = defineEmits<{
    focus: [];
    blur: [];
    change: [any];
}>();

const props = defineProps<{
    max: number;
    min: number;
    name: string;

    label?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    disabled?: boolean;
    valueText?: string;
    hideValue?: boolean;
    // TODO - Rename this variable
    switchVariant?: variantType;
}>();

const inputId = useId();

const { hasFocus, onBlur, onFocus, errorMessage, value } = useBaseInputHelper(
    props,
    emit,
);

const { getVariantBase } = useVariantHelper();
</script>

<template>
    <InputWrapper
        :error-message="errorMessage"
        :help-message="helpMessage"
        :has-focus="hasFocus"
        :help-visible="helpVisible"
    >
        <div>
            <label
                :for="inputId"
                class="block mb-2 text-sm font-medium text-muted"
            >
                {{ label }}
            </label>
            <input
                v-model="value"
                class="w-full"
                :class="`accent-${getVariantBase(switchVariant ?? 'primary')}`"
                type="range"
                :id="inputId"
                :min="min"
                :max="max"
                :disabled="disabled"
                @focus="onFocus"
                @blur="onBlur"
                @change="$emit('change', value)"
            />
            <div v-if="!hideValue" class="text-muted">
                <span v-if="valueText">{{ valueText }}</span>
                {{ value }}
            </div>
        </div>
    </InputWrapper>
</template>

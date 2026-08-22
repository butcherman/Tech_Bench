<script setup lang="ts">
import BaseRangeSliderInput from "../baseInputs/BaseRangeSliderInput.vue";
import { useValidationHelper } from "../../composables/validationHelper.js";
import { toRef } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;
    min: number;
    max: number;

    autocomplete?: string;
    disabled?: boolean;
    helpMessage?: string;
    helpVisible?: boolean;
    hideValue?: boolean;
    label?: string;
    placeholder?: string;
    rangeVariant?: VariantType;
    valueText?: string;
}>();

const { errorMessage, value } = useValidationHelper<string>(
    toRef(props, "name"),
);
</script>

<template>
    <BaseRangeSliderInput
        v-bind="props"
        v-model:value="value"
        :error-message="errorMessage"
        @focus="$emit('focus')"
        @blur="$emit('blur')"
    />
</template>

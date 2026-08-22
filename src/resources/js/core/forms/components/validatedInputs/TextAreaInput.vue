<script setup lang="ts">
import BaseTextAreaInput from "../baseInputs/BaseTextAreaInput.vue";
import { useValidationHelper } from "../../composables/validationHelper.js";
import { toRef } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;

    autocomplete?: string;
    disabled?: boolean;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    placeholder?: string;
    rows?: number;
    variant?: InputVariant;
}>();

const { errorMessage, value } = useValidationHelper<string>(
    toRef(props, "name"),
);
</script>

<template>
    <BaseTextAreaInput
        v-bind="props"
        v-model:value="value"
        :error-message="errorMessage"
        @focus="$emit('focus')"
        @blur="$emit('blur')"
    />
</template>

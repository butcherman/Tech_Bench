<script setup lang="ts">
import BasePhoneNumberInput from "../baseInputs/BasePhoneNumberInput.vue";
import { useValidationHelper } from "../../composables/validationHelper.js";
import { toRef } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;
    value: string;

    disabled?: boolean;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    placeholder?: string;
    variant?: InputVariant;
}>();

const { errorMessage, value } = useValidationHelper<string>(toRef(props.name));
</script>

<template>
    <BasePhoneNumberInput
        v-bind="props"
        v-model:value="value"
        :error-message="errorMessage"
        @focus="$emit('focus')"
        @blur="$emit('blur')"
    />
</template>

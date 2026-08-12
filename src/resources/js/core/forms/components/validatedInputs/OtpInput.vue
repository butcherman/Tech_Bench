<script setup lang="ts">
import BaseOtpInput from "../baseInputs/BaseOtpInput.vue";
import { useValidationHelper } from "../../composables/validationHelper.js";
import { toRef } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;

    disabled?: boolean;
    helpMessage?: string;
    helpVisible?: boolean;
    length?: number;
    variant?: InputVariant;
}>();

const { errorMessage, value } = useValidationHelper<string>(toRef(props.name));
</script>

<template>
    <BaseOtpInput
        v-bind="props"
        v-model:value="value"
        :error-message="errorMessage"
        @focus="$emit('focus')"
        @blur="$emit('blur')"
    />
</template>

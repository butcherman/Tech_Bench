<script setup lang="ts">
import BaseSwitchInput from "../baseInputs/BaseSwitchInput.vue";
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
    label?: string;
    switchVariant?: VariantType;
}>();

const { errorMessage, value } = useValidationHelper<boolean>(
    toRef(props, "name"),
);
</script>

<template>
    <BaseSwitchInput
        v-bind="props"
        v-model:value="value"
        :error-message="errorMessage"
        @focus="$emit('focus')"
        @blur="$emit('blur')"
    />
</template>

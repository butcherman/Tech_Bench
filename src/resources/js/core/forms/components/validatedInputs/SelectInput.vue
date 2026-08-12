<script
    setup
    lang="ts"
    generic="TOption extends string | Record<string, unknown>"
>
import BaseSelectInput from "../baseInputs/BaseSelectInput.vue";
import { useValidationHelper } from "../../composables/validationHelper.js";
import { toRef } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;
    list: TOption[];

    autocomplete?: string;
    disabled?: boolean;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    placeholder?: string;
    textField?: TOption extends string ? never : keyof TOption;
    valueField?: TOption extends string ? never : keyof TOption;
    variant?: InputVariant;
}>();

const { errorMessage, value } = useValidationHelper<string>(toRef(props.name));
</script>

<template>
    <BaseSelectInput
        v-bind="props"
        v-model:value="value"
        :error-message="errorMessage"
        @focus="$emit('focus')"
        @blur="$emit('blur')"
    />
</template>

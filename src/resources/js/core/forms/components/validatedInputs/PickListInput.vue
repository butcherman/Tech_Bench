<script
    setup
    lang="ts"
    generic="TOption extends string | Record<string, unknown>"
>
import BasePickListInput from "../baseInputs/BasePickListInput.vue";
import { useValidationHelper } from "../../composables/validationHelper.js";
import { toRef } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;
    list: TOption[];

    disabled?: boolean;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    selectSize?: number;
    textField?: TOption extends string ? never : keyof TOption;
    valueField?: TOption extends string ? never : keyof TOption;
}>();

const { errorMessage, value } = useValidationHelper<string[]>(
    toRef(props.name),
);
</script>

<template>
    <BasePickListInput
        v-bind="props"
        v-model:value="value"
        :error-message="errorMessage"
        @focus="$emit('focus')"
        @blur="$emit('blur')"
    />
</template>

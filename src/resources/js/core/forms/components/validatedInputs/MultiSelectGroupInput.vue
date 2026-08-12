<script
    setup
    lang="ts"
    generic="
        TGroup extends Record<string, unknown>,
        TOption extends string | Record<string, unknown>
    "
>
import BaseMultiSelectGroupInput from "../baseInputs/BaseMultiSelectGroupInput.vue";
import { useValidationHelper } from "../../composables/validationHelper.js";
import { toRef } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;
    list: TGroup[];

    autocomplete?: string;
    disabled?: boolean;
    groupTextField: keyof TGroup;
    groupListField: ArrayProperty<TGroup, TOption>;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    placeholder?: string;
    textField?: TOption extends string ? never : keyof TOption;
    valueField?: TOption extends string ? never : keyof TOption;
    variant?: InputVariant;
}>();

const { errorMessage, value } = useValidationHelper<string[]>(
    toRef(props.name),
);
</script>

<template>
    <BaseMultiSelectGroupInput
        v-bind="props"
        v-model:value="value"
        :error-message="errorMessage"
        @focus="$emit('focus')"
        @blur="$emit('blur')"
    />
</template>

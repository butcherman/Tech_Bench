<script
    setup
    lang="ts"
    generic="TOption extends string | Record<string, unknown>"
>
import InputWrapper from "../wrappers/InputWrapper.vue";
import {  useId } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";
import { useSelectHelper } from "../../composables/selectHelper.js";

defineSlots<{
    [key: string]: any;
}>();

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;
    list: TOption[];

    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    textField?: TOption extends string ? never : keyof TOption;
    valueField?: TOption extends string ? never : keyof TOption;
}>();

const inputValue = defineModel<string | null>({
    required: true,
});

const { getValue, getOptionText } = useSelectHelper(props);
const { hasFocus, onFocus, onBlur } = useInputHelper(props, emit);

const inputId = useId();
</script>

<template>
    <InputWrapper v-bind="props" :has-focus="hasFocus">
        <div v-if="label" class="text-muted">{{ label }}</div>
        <div v-for="item in list" class="flex gap-2">
            <input
                v-model="inputValue"
                type="radio"
                :name="name"
                :id="`${inputId}-${getOptionText(item)}`"
                :value="getValue(item)"
                @focus="onFocus"
                @blur="onBlur"
            />
            <label :for="`${inputId}-${getOptionText(item)}`">
                {{ getOptionText(item) }}
            </label>
        </div>
    </InputWrapper>
</template>

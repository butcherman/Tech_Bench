<script
    setup
    lang="ts"
    generic="TOption extends string | Record<string, unknown>"
>
import InputWrapper from "./wrappers/InputWrapper.vue";
import { useBaseInputHelper } from "../composables/baseInputHelper.js";
import { useFormInputHelper } from "../composables/formInputHelper.js";
import { useId } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
    change: [TOption | string];
}>();

const props = defineProps<{
    name: string;
    list: TOption[];

    label?: string;
    helpMessage?: string;
    variant?: InputVariant;
    placeholder?: string;
    helpVisible?: boolean;
    disabled?: boolean;
    textField?: TOption extends string ? never : keyof TOption;
    valueField?: TOption extends string ? never : keyof TOption;
}>();

const inputId = useId();

const { hasFocus, onBlur, onFocus, errorMessage, value } = useBaseInputHelper(
    props,
    emit,
);
const { inputVariantStyle } = useFormInputHelper(props);

/**
 * Get the value of the list item
 */
const getValue = (opt: TOption) => {
    if (typeof opt === "string") {
        return opt;
    }

    return props.valueField ? opt[props.valueField] : opt;
};

/**
 * Get the text to be displayed
 */
const getOptionText = (item: TOption): string => {
    if (typeof item === "string") {
        return item;
    }

    return props.textField ? String(item[props.textField]) : String(item);
};
</script>

<template>
    <InputWrapper
        :error-message="errorMessage"
        :help-message="helpMessage"
        :has-focus="hasFocus"
        :help-visible="helpVisible"
    >
        <div>
            <div class="relative">
                <select
                    v-model="value"
                    class="block peer form-input-base"
                    :class="[inputVariantStyle]"
                    :disabled="disabled"
                    :id="inputId"
                    :name="name"
                    :placeholder="placeholder ?? ''"
                    @focus="onFocus"
                    @blur="onBlur"
                    @change="$emit('change', value)"
                >
                    <option
                        v-for="opt in list"
                        :key="getOptionText(opt)"
                        :value="getValue(opt)"
                    >
                        {{ getOptionText(opt) }}
                    </option>
                </select>
                <div class="absolute inset-e-1.5 bottom-1.5 text-muted pointer">
                    <fa-icon icon="caret-down" />
                </div>
                <label
                    :for="inputId"
                    class="form-label-base"
                    :class="{ 'bg-white!': variant === 'outlined' }"
                >
                    {{ label }}
                </label>
            </div>
        </div>
    </InputWrapper>
</template>

<script
    setup
    lang="ts"
    generic="TOption extends string | Record<string, unknown>"
>
import InputWrapper from "./wrappers/InputWrapper.vue";
import { useBaseInputHelper } from "../composables/baseInputHelper.js";
import { useFormInputHelper } from "../composables/formInputHelper.js";
import { useSelectHelper } from "../composables/selectHelper.js";
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
const { getValue, getOptionText } = useSelectHelper(props);
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

<script setup lang="ts" generic="TOption extends string | object">
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
    change: [modelValue: string | null];
}>();

const props = defineProps<{
    name: string;
    list: TOption[];

    autocomplete?: string;
    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    placeholder?: string;
    textField?: TOption extends string ? never : keyof TOption;
    valueField?: TOption extends string ? never : keyof TOption;
    variant?: InputVariant;
}>();

const inputValue = defineModel<string | null>({
    required: true,
});

const { hasFocus, onFocus, onBlur, inputVariantStyle } = useInputHelper(
    props,
    emit,
);
const { getValue, getOptionText } = useSelectHelper(props);

const inputId = useId();
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
                    v-model="inputValue"
                    class="block peer form-input-base"
                    :class="[
                        inputVariantStyle,
                        {
                            invalid: errorMessage?.length,
                            'has-focus': hasFocus,
                        },
                    ]"
                    :disabled="disabled"
                    :id="inputId"
                    :name="name"
                    :placeholder="placeholder ?? ''"
                    @focus="onFocus"
                    @blur="onBlur"
                    @change="$emit('change', inputValue)"
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

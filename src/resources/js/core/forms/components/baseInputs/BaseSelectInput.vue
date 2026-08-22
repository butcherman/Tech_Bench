<script
    setup
    lang="ts"
    generic="TOption extends string | Record<string, unknown>"
>
import InputWrapper from "../wrappers/InputWrapper.vue";
import { computed, useId } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";
import { useSelectHelper } from "../../composables/selectHelper.js";

defineSlots<{
    [key: string]: any;
}>();

const emit = defineEmits<{
    "update:value": [string];
    focus: [];
    blur: [];
    change: [string];
}>();

const props = defineProps<{
    name: string;
    list: TOption[];
    value: string;

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

const { hasFocus, onFocus, onBlur, inputVariantStyle } = useInputHelper(
    props,
    emit,
);
const { getValue, getOptionText } = useSelectHelper(props);

const inputId = useId();
const inputValue = computed({
    get: () => props.value,
    set: (value) => emit("update:value", value),
});
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

<script
    setup
    lang="ts"
    generic="
        TGroup extends Record<string, unknown>,
        TOption extends string | Record<string, unknown>
    "
>
import InputWrapper from "./InputWrapper.vue";
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
    list: TGroup[];

    label?: string;
    helpMessage?: string;
    variant?: InputVariant;
    placeholder?: string;
    helpVisible?: boolean;
    disabled?: boolean;
    textField?: TOption extends string ? never : keyof TOption;
    valueField?: TOption extends string ? never : keyof TOption;
    groupTextField: keyof TGroup;
    groupListField: ArrayProperty<TGroup, TOption>;
    // groupIconField?: string;
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

/**
 * Get the label for the Option Group
 */
const getGroupText = (group: TGroup): string => {
    return String(group[props.groupTextField]);
};

/**
 * Get the list of grouped items for the Option Group
 */
const getGroupItems = (group: TGroup): readonly TOption[] => {
    return group[props.groupListField] as readonly TOption[];
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
                    <optgroup
                        v-for="group in list"
                        :key="getGroupText(group)"
                        :label="getGroupText(group)"
                    >
                        <option
                            v-for="opt in getGroupItems(group)"
                            :key="getOptionText(opt)"
                            :value="getValue(opt)"
                        >
                            {{ getOptionText(opt) }}
                        </option>
                    </optgroup>
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

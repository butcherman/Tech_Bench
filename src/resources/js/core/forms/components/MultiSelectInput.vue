<script
    setup
    lang="ts"
    generic="TOption extends string | Record<string, unknown>"
>
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import InputWrapper from "./wrappers/InputWrapper.vue";
import { onMounted, ref, useId } from "vue";
import { useBaseInputHelper } from "../composables/baseInputHelper.js";
import { useFormInputHelper } from "../composables/formInputHelper.js";
import { useSelectHelper } from "../composables/selectHelper.js";

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
const showSelect = ref(false);

const { hasFocus, onBlur, onFocus, errorMessage, value } = useBaseInputHelper(
    props,
    emit,
);
const { inputVariantStyle } = useFormInputHelper(props);
const { getValue, getOptionText } = useSelectHelper(props);

/**
 * Process that an option was selected, but do not allow duplicate entries
 */
const valueSelected = (
    selected: TOption | TOption[TOption extends string ? never : keyof TOption],
) => {
    // Check to see if the value already exists
    if (!value.value.includes(selected)) {
        value.value.push(selected);
    }
};

/**
 * Process that an option needs to be removed
 */
const valueRemoved = (
    removable:
        | TOption
        | TOption[TOption extends string ? never : keyof TOption],
) => {
    let index = value.value.findIndex((v: TOption) => v === removable);
    value.value.splice(index, 1);
    showSelect.value = false;
};

const triggerFocus = () => {
    showSelect.value = true;
    onFocus();
};

const triggerBlur = () => {
    showSelect.value = false;
    onBlur();
};

/**
 * Make sure that the value is in array form
 */
onMounted(() => {
    if (value.value === undefined) {
        value.value = [];
    }
});
</script>

<template>
    <InputWrapper
        :error-message="errorMessage"
        :help-message="helpMessage"
        :has-focus="hasFocus"
        :help-visible="helpVisible"
    >
        <div class="relative" v-on-click-outside="triggerBlur">
            <div
                class="form-input-base h-10"
                :class="[inputVariantStyle, { 'has-focus': hasFocus }]"
                @click="triggerFocus"
            >
                <div class="flex">
                    <div v-for="val in value" :key="val">
                        <BaseBadge class="mx-1 py-1" circle>
                            <span class="px-1">
                                {{ val }}
                            </span>
                            <fa-icon
                                icon="circle-xmark"
                                class="text-xs text-white pointer"
                                @click.stop="valueRemoved(val)"
                            />
                        </BaseBadge>
                    </div>
                </div>
            </div>
            <label
                :for="inputId"
                class="form-label-base"
                :class="{ 'bg-white!': variant === 'outlined' }"
            >
                {{ label }}
            </label>
            <div
                v-if="showSelect"
                class="absolute top-10 left-0 right-0 z-50 p-2 bg-white rounded-lg border border-slate-300"
            >
                <div
                    v-for="opt in list"
                    class="p-1 hover:bg-blue-400 rounded-lg pointer"
                    :class="{
                        'bg-blue-200':
                            Array.isArray(value) && value.includes(opt),
                    }"
                    :key="getOptionText(opt)"
                    @click="valueSelected(getValue(opt))"
                >
                    {{ getOptionText(opt) }}
                </div>
            </div>
            <div class="absolute inset-e-1.5 bottom-1.5 text-muted pointer">
                <fa-icon icon="caret-down" />
            </div>
            <select
                v-model="value"
                class="sr-only"
                multi
                :disabled="disabled"
                :id="inputId"
                :name="name"
            />
        </div>
    </InputWrapper>
</template>

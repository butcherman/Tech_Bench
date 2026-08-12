<script
    setup
    lang="ts"
    generic="TOption extends string | Record<string, unknown>"
>
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import InputWrapper from "../wrappers/InputWrapper.vue";
import { computed, ref, useId } from "vue";
import { useDataHelper } from "@/core/composables/dataHelper.js";
import { useInputHelper } from "../../composables/inputHelper.js";
import { useSelectHelper } from "../../composables/selectHelper.js";

defineSlots<{
    [key: string]: any;
}>();

const emit = defineEmits<{
    "update:value": [string[]];
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;
    list: TOption[];
    value: string[];

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

const { indexData } = useDataHelper();
const { getValue, getOptionText, getOptionTextFromValue } =
    useSelectHelper(props);
const { hasFocus, onFocus, onBlur, inputVariantStyle } = useInputHelper(
    props,
    emit,
);

const inputId = useId();
const showSelect = ref(false);
const inputValue = computed({
    get: () => props.value,
    set: (value) => emit("update:value", value),
});

/*
|-------------------------------------------------------------------------------
| Handle value changes
|-------------------------------------------------------------------------------
*/
const onValueSelected = (selected: TOption) => {
    let selectedValue = getValue(selected);

    // Check to see if the value already exists
    if (!inputValue.value.includes(selectedValue)) {
        inputValue.value.push(selectedValue);
    }
};

const onValueRemoved = (removable: unknown) => {
    let index = inputValue.value.findIndex((v) => v === removable);
    inputValue.value.splice(index, 1);
    showSelect.value = false;
};

/*
|-------------------------------------------------------------------------------
| Component focus state
|-------------------------------------------------------------------------------
*/
const triggerFocus = () => {
    showSelect.value = true;
    onFocus();
};

const triggerBlur = () => {
    showSelect.value = false;
    onBlur();
};
</script>

<template>
    <InputWrapper
        v-bind="props"
        :has-focus="hasFocus"
        v-on-click-outside="triggerBlur"
    >
        <div class="relative">
            <div
                class="form-input-base h-10"
                :class="[inputVariantStyle, { 'has-focus': hasFocus }]"
                @click="triggerFocus"
            >
                <div class="flex gap-1">
                    <div v-for="val in indexData(inputValue)" :key="val.id">
                        <BaseBadge>
                            <span class="px-1">
                                {{ getOptionTextFromValue(val.data) }}
                            </span>
                            <fa-icon
                                icon="circle-xmark"
                                class="text-xs text-white pointer"
                                @click.stop="onValueRemoved(val.data)"
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
                    :key="getOptionText(opt)"
                    class="p-1 hover:bg-blue-400 rounded-lg pointer"
                    :class="{
                        'bg-blue-200': inputValue.includes(getValue(opt)),
                    }"
                    @click="onValueSelected(opt)"
                >
                    {{ getOptionText(opt) }}
                </div>
            </div>
            <div class="absolute inset-e-1.5 bottom-1.5 text-muted pointer">
                <fa-icon icon="caret-down" />
            </div>
            <select
                v-model="inputValue"
                class="sr-only"
                multi
                :disabled="disabled"
                :id="inputId"
                :name="name"
            />
        </div>
    </InputWrapper>
</template>

<script
    setup
    lang="ts"
    generic="TOption extends string | Record<string, unknown>"
>
import InputWrapper from "../wrappers/InputWrapper.vue";
import { computed, ref, useId } from "vue";
import { useSelectHelper } from "../../composables/selectHelper.js";
import { useInputHelper } from "../../composables/inputHelper.js";

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

    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    selectSize?: number;
    textField?: TOption extends string ? never : keyof TOption;
    valueField?: TOption extends string ? never : keyof TOption;
}>();

const { getValue, getOptionText } = useSelectHelper(props);
const { hasFocus, onFocus, onBlur } = useInputHelper(props, emit);

const inputId = useId();
const selectSize = computed(() => props.selectSize ?? 10);

/**
 * Values selected in the Available and Selected lists.
 */
const availableSelected = ref<string[]>([]);
const assignedSelected = ref<string[]>([]);

const availableList = computed(() =>
    props.list.filter((option) => !props.value.includes(getValue(option))),
);
const assignedList = computed(() =>
    props.list.filter((option) => props.value.includes(getValue(option))),
);

/**
 * Update the value without mutating props.
 */
const updateValue = (value: string[]) => {
    emit("update:value", value);
};

/**
 * Add selected items from the Available list.
 */
const onAddItems = (all = false) => {
    const valuesToAdd = all
        ? availableList.value.map(getValue)
        : availableSelected.value;

    if (!valuesToAdd.length) {
        return;
    }

    updateValue([
        ...props.value,
        ...valuesToAdd.filter((value) => !props.value.includes(value)),
    ]);

    availableSelected.value = [];
};

/**
 * Remove selected items from the Selected list.
 */
const onRemoveItems = (all = false) => {
    if (all) {
        updateValue([]);
    } else {
        if (!assignedSelected.value.length) {
            return;
        }

        updateValue(
            props.value.filter(
                (value) => !assignedSelected.value.includes(value),
            ),
        );
    }

    assignedSelected.value = [];
};

/**
 * Move an individual Available option by double-clicking it.
 */
const onAvailableDoubleClick = (option: TOption) => {
    const value = getValue(option);

    if (!props.value.includes(value)) {
        updateValue([...props.value, value]);
    }

    availableSelected.value = [];
};

/**
 * Move an individual Selected option by double-clicking it.
 */
const onAssignedDoubleClick = (value: string) => {
    updateValue(props.value.filter((item) => item !== value));

    assignedSelected.value = [];
};
</script>

<template>
    <InputWrapper v-bind="props" :has-focus="hasFocus">
        <div v-if="label" class="text-muted">
            {{ label }}
        </div>

        <div class="flex flex-row justify-center gap-2">
            <!-- Available -->
            <div class="basis-5/12">
                <label :for="`${inputId}-available`" class="block text-muted">
                    Available
                </label>

                <select
                    :id="`${inputId}-available`"
                    v-model="availableSelected"
                    :name="`${name}-available`"
                    :size="selectSize"
                    :disabled="disabled"
                    class="w-full border border-slate-300 rounded-lg overflow-auto p-2 focus:outline-blue-400"
                    multiple
                    @focus="onFocus"
                    @blur="onBlur"
                    @dblclick="
                        availableSelected.length === 1 &&
                        onAvailableDoubleClick(
                            availableList.find(
                                (option) =>
                                    getValue(option) === availableSelected[0],
                            )!,
                        )
                    "
                >
                    <option
                        v-for="option in availableList"
                        :key="getValue(option)"
                        :value="getValue(option)"
                    >
                        {{ getOptionText(option) }}
                    </option>
                </select>
            </div>

            <!-- Controls -->
            <div
                class="basis-2/12 flex flex-col justify-center text-muted gap-2"
            >
                <button
                    type="button"
                    class="px-2 py-1 bg-slate-200 border border-slate-200 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="disabled || availableList.length === 0"
                    title="Add all"
                    aria-label="Add all available items"
                    @click="onAddItems(true)"
                >
                    <fa-icon icon="angles-right" />
                </button>

                <button
                    type="button"
                    class="px-2 py-1 bg-slate-200 border border-slate-200 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="disabled || availableSelected.length === 0"
                    title="Add selected"
                    aria-label="Add selected items"
                    @click="onAddItems()"
                >
                    <fa-icon icon="angle-right" />
                </button>

                <button
                    type="button"
                    class="px-2 py-1 bg-slate-200 border border-slate-200 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="disabled || assignedSelected.length === 0"
                    title="Remove selected"
                    aria-label="Remove selected items"
                    @click="onRemoveItems()"
                >
                    <fa-icon icon="angle-left" />
                </button>

                <button
                    type="button"
                    class="px-2 py-1 bg-slate-200 border border-slate-200 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="disabled || props.value.length === 0"
                    title="Remove all"
                    aria-label="Remove all selected items"
                    @click="onRemoveItems(true)"
                >
                    <fa-icon icon="angles-left" />
                </button>
            </div>

            <!-- Selected -->
            <div class="basis-5/12">
                <label :for="`${inputId}-selected`" class="block text-muted">
                    Selected
                </label>

                <select
                    :id="`${inputId}-selected`"
                    v-model="assignedSelected"
                    :name="`${name}-selected`"
                    :size="selectSize"
                    :disabled="disabled"
                    class="w-full border border-slate-300 rounded-lg overflow-auto p-2 focus:outline-blue-400"
                    multiple
                    @focus="onFocus"
                    @blur="onBlur"
                    @dblclick="
                        assignedSelected.length === 1 &&
                        onAssignedDoubleClick(assignedSelected[0])
                    "
                >
                    <option
                        v-for="option in assignedList"
                        :key="getValue(option)"
                        :value="getValue(option)"
                    >
                        {{ getOptionText(option) }}
                    </option>
                </select>
            </div>
        </div>
    </InputWrapper>
</template>

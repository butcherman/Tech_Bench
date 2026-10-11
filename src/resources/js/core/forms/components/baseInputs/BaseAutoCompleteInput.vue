<script setup lang="ts">
import TextInputWrapper from "../wrappers/TextInputWrapper.vue";
import {  useId } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";

const emit = defineEmits<{
    focus: [];
    blur: [];
    change: [value: string | null];
}>();

const props = defineProps<{
    name: string;
    list: string[];

    autocomplete?: string;
    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    placeholder?: string;
    variant?: InputVariant;
}>();

const inputValue = defineModel<string | null>({
    required: true,
});

const { hasFocus, onFocus, onBlur, inputVariantStyle } = useInputHelper(
    props,
    emit,
);

const inputId = useId();
const datalistId = useId();
</script>

<template>
    <TextInputWrapper v-bind="props" :has-focus="hasFocus">
        <template v-for="(_, slot) of $slots" #[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
        <input
            v-model="inputValue"
            class="block peer form-input-base"
            type="text"
            :autocomplete="name"
            :class="[inputVariantStyle]"
            :disabled="disabled"
            :id="inputId"
            :list="datalistId"
            :placeholder="placeholder ?? ''"
            :name="name"
            @focus="onFocus"
            @blur="onBlur"
            @change="$emit('change', inputValue)"
        />
        <label
            :for="inputId"
            class="form-label-base"
            :class="{ 'bg-white!': variant === 'outlined' }"
        >
            {{ label }}
        </label>
        <datalist :id="datalistId">
            <option v-for="opt in list" :key="opt" :value="opt">
                {{ opt }}
            </option>
        </datalist>
    </TextInputWrapper>
</template>

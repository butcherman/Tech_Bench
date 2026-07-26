<script setup lang="ts">
import BaseTextWrapper from "./wrappers/BaseTextWrapper.vue";
import { useBaseInputHelper } from "../composables/baseInputHelper.js";
import { useFormInputHelper } from "../composables/formInputHelper.js";
import { useId } from "vue";

const emit = defineEmits<{
    focus: [];
    blur: [];
    change: [any];
}>();

const props = defineProps<{
    name: string;
    list: string[];

    label?: string;
    helpMessage?: string;
    variant?: InputVariant;
    placeholder?: string;
    helpVisible?: boolean;
    autocomplete?: string;
    disabled?: boolean;
}>();

const inputId = useId();
const datalistId = useId();

const { onBlur, onFocus, value } = useBaseInputHelper(props, emit);
const { inputVariantStyle } = useFormInputHelper(props);
</script>

<template>
    <BaseTextWrapper v-bind="props">
        <template v-for="name of Object.keys($slots)" v-slot:[name]="data">
            <slot :name="name" v-bind="data" />
        </template>
        <input
            v-model="value"
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
            @change="$emit('change', value)"
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
    </BaseTextWrapper>
</template>

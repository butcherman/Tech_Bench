<script setup lang="ts">
import TextInputWrapper from "../wrappers/TextInputWrapper.vue";
import { computed, useId } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";

defineSlots<{
    [key: string]: any;
}>();

const emit = defineEmits<{
    "update:value": [string];
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;
    value: string;

    autocomplete?: string;
    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    placeholder?: string;
    variant?: InputVariant;
}>();

const { hasFocus, onFocus, onBlur, inputVariantStyle } = useInputHelper(
    props,
    emit,
);

const inputId = useId();
const inputValue = computed({
    get: () => props.value,
    set: (value) => emit("update:value", value),
});
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
            :class="[
                inputVariantStyle,
                { invalid: errorMessage?.length, 'has-focus': hasFocus },
            ]"
            :disabled="disabled"
            :id="inputId"
            :placeholder="placeholder ?? ''"
            :name="name"
            @focus="onFocus"
            @blur="onBlur"
        />
        <label
            :for="inputId"
            class="form-label-base"
            :class="{ 'bg-white!': variant === 'outlined' }"
        >
            {{ label }}
        </label>
    </TextInputWrapper>
</template>

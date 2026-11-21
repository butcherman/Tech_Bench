<script setup lang="ts">
import InputWrapper from "../wrappers/InputWrapper.vue";
import { useId } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";

defineSlots<{
    [key: string]: any;
}>();

const emit = defineEmits<{
    focus: [];
    blur: [];
}>();

const props = defineProps<{
    name: string;

    appendText?: string;
    autocomplete?: string;
    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    placeholder?: string;
    prependText?: string;
}>();

const inputValue = defineModel<string | null | undefined>({
    required: true,
});

const { hasFocus, onFocus, onBlur } = useInputHelper(props, emit);

const inputId = useId();
</script>

<template>
    <InputWrapper :error-message :help-message :has-focus :help-visible>
        <label v-if="label" :for="inputId" class="form-label-base">
            {{ label }}
        </label>
        <div class="flex form-input-wrapper" :class="{ 'has-focus': hasFocus }">
            <div
                v-if="$slots['prepend-input'] || prependText"
                class="border-e border-slate-300 p-2"
                :class="{ 'has-focus': hasFocus }"
            >
                <slot name="prepend-input">{{ prependText }}</slot>
            </div>
            <div class="grow">
                <input
                    v-model="inputValue"
                    type="text"
                    class="w-full p-2 form-input"
                    :autocomplete="name"
                    :disabled="disabled"
                    :id="inputId"
                    :placeholder="placeholder ?? ''"
                    :name="name"
                    @focus="onFocus"
                    @blur="onBlur"
                />
            </div>
            <div
                v-if="$slots['append-input'] || appendText"
                class="border-s border-slate-300 p-2"
                :class="{ 'has-focus': hasFocus }"
            >
                <slot name="append-input">{{ appendText }}</slot>
            </div>
        </div>
    </InputWrapper>
</template>

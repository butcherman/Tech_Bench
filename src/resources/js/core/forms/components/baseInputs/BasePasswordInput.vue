<script setup lang="ts">
import InputWrapper from "../wrappers/InputWrapper.vue";
import { computed, ref, useId } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";

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

    autocomplete?: string;
    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    hideUnmask?: boolean;
    label?: string;
    placeholder?: string;
    variant?: InputVariant;
}>();

const inputValue = defineModel<string | null>({
    required: true,
});

const { hasFocus, onFocus, onBlur } = useInputHelper(props, emit);

const inputId = useId();

/*
|-------------------------------------------------------------------------------
| Visibility of password
|-------------------------------------------------------------------------------
*/
const showPass = ref<boolean>(false);
const maskType = computed<"text" | "password">(() =>
    showPass.value ? "text" : "password",
);
const maskIcon = computed<"eye-slash" | "eye">(() =>
    showPass.value ? "eye-slash" : "eye",
);
</script>

<template>
    <InputWrapper :error-message :help-message :has-focus :help-visible>
        <label
            v-if="label"
            :for="inputId"
            class="form-label-base"
            :class="{ 'has-focus': hasFocus }"
        >
            {{ label }}
        </label>
        <div
            class="flex form-input-wrapper relative"
            :class="{ 'has-focus': hasFocus }"
        >
            <input
                v-model="inputValue"
                :type="maskType"
                class="w-full p-2 form-input"
                :autocomplete="name"
                :disabled="disabled"
                :id="inputId"
                :placeholder="placeholder ?? ''"
                :name="name"
                @focus="onFocus"
                @blur="onBlur"
                @change="$emit('change', inputValue)"
            />
            <div
                v-if="!hideUnmask"
                class="absolute inset-e-1.5 bottom-1.5 text-muted pointer"
                @click="showPass = !showPass"
            >
                <fa-icon :icon="maskIcon" />
            </div>
        </div>
    </InputWrapper>
</template>

<script setup lang="ts">
import InputWrapper from "../wrappers/InputWrapper.vue";
import { computed, ref, useId, useTemplateRef, watch } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";

type OTPDigit = "" | `${string}`;

const emit = defineEmits<{
    "update:value": [string];
    focus: [];
    blur: [];
    change: [string];
}>();

const props = defineProps<{
    name: string;
    value: string;

    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    length?: number;
    variant?: InputVariant;
}>();

const { hasFocus, onFocus, onBlur, inputVariantStyle } = useInputHelper(
    props,
    emit,
);

const inputValue = computed({
    get: () => props.value,
    set: (value) => emit("update:value", value),
});

const otpInput = useTemplateRef<HTMLDivElement>("otp-wrapper");
const otpLength = computed<number>(() => props.length ?? 4);
const inputIds = Array.from({ length: otpLength.value }, () => useId());
const otpDigits = ref<OTPDigit[]>(
    Array.from({ length: otpLength.value }, () => ""),
);

/**
 * Watch the input digits and assign them to the value
 */
watch(
    otpDigits,
    () => {
        inputValue.value = otpDigits.value.join("");
        emit("change", inputValue.value);
    },
    { deep: true },
);

/**
 * Move to a specific OTP Input Box
 */
const focusInput = (index: number) => {
    if (!otpInput.value) return;

    const input = otpInput.value.children[index] as
        | HTMLInputElement
        | undefined;

    input?.focus();
};

/**
 * Handle keydown and other input events to smooth out input
 */
const onKeyDown = (event: KeyboardEvent, index: number) => {
    if (
        event.key !== "Tab" &&
        event.key !== "ArrowRight" &&
        event.key !== "ArrowLeft" &&
        event.key !== "Enter"
    ) {
        event.preventDefault();
    }

    if (event.key === "Backspace") {
        otpDigits.value[index] = "";

        if (index != 0 && otpInput.value) {
            focusInput(index - 1);
        }

        return;
    }

    if (/^\d$/.test(event.key)) {
        otpDigits.value[index] = event.key;

        if (index != otpLength.value - 1 && otpInput.value) {
            focusInput(index + 1);
        }
    }
};
</script>

<template>
    <InputWrapper
        :error-message="errorMessage"
        :help-message="helpMessage"
        :has-focus="hasFocus"
        :help-visible="helpVisible"
    >
        <div ref="otp-wrapper" class="flex justify-center gap-4">
            <input
                v-for="(n, index) in otpLength"
                v-model="otpDigits[index]"
                autocomplete="off"
                class="form-input-base text-center w-10!"
                inputmode="numeric"
                maxlength="1"
                pattern="[0-9]*"
                type="text"
                :class="[inputVariantStyle]"
                :disabled="disabled"
                :id="inputIds[index]"
                :key="n"
                :name="name"
                @focus="onFocus"
                @blur="onBlur"
                @keydown="onKeyDown($event, index)"
            />
        </div>
    </InputWrapper>
</template>

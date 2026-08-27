<script setup lang="ts">
import InputWrapper from "../wrappers/InputWrapper.vue";
import { computed, useId } from "vue";
import { useInputHelper } from "../../composables/inputHelper.js";
import { useVariantHelper } from "@/core/composables/variantHelper";

const emit = defineEmits<{
    focus: [];
    blur: [];
    change: [modelValue: boolean];
}>();

const props = defineProps<{
    name: string;

    disabled?: boolean;
    errorMessage?: string;
    helpMessage?: string;
    helpVisible?: boolean;
    label?: string;
    switchVariant?: VariantType;
}>();

const inputValue = defineModel<boolean>({
    required: true,
});

const { hasFocus, onBlur, onFocus, switchSize, switchInputSize } =
    useInputHelper(props, emit);

const { getBackgroundClass } = useVariantHelper();

const inputId = useId();

const variantClass = computed(() => {
    if (inputValue) {
        return getBackgroundClass(props.switchVariant ?? "primary");
    }

    return "bg-slate-300";
});
</script>

<template>
    <InputWrapper
        :error-message="errorMessage"
        :help-message="helpMessage"
        :has-focus="hasFocus"
        :help-visible="helpVisible"
    >
        <label :for="inputId" class="inline-flex gap-2 pointer">
            <div class="relative my-auto">
                <input
                    v-model="inputValue"
                    class="sr-only peer"
                    type="checkbox"
                    :checked="inputValue"
                    :disabled="disabled"
                    :id="inputId"
                    :name="name"
                    @focus="onFocus"
                    @blur="onBlur"
                    @change="$emit('change', inputValue)"
                />
                <div
                    class="rounded-full transition-colors duration-200"
                    :class="[
                        variantClass,
                        switchSize,
                        { 'opacity-60': disabled },
                    ]"
                />
                <span
                    class="dot absolute left-1 top-1 bg-white rounded-full transition-transform duration-200 ease-in-out"
                    :class="switchInputSize"
                />
            </div>
            {{ label }}
        </label>
    </InputWrapper>
</template>

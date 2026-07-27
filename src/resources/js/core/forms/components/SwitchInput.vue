<script setup lang="ts">
import InputWrapper from "./wrappers/InputWrapper.vue";
import { computed, useId } from "vue";
import { useBaseInputHelper } from "../composables/baseInputHelper";
import { useFormInputHelper } from "../composables/formInputHelper";
import { useVariantHelper } from "@/core/composables/variantHelper";

const emit = defineEmits<{
    focus: [];
    blur: [];
    change: [any];
}>();

const props = defineProps<{
    name: string;

    label?: string;
    helpMessage?: string;
    switchVariant?: variantType;
    helpVisible?: boolean;
    disabled?: boolean;
}>();

const inputId = useId();

const { hasFocus, onBlur, onFocus, errorMessage, value } = useBaseInputHelper(
    props,
    emit,
);

const { getBackgroundClass } = useVariantHelper();
const { switchSize, switchInputSize } = useFormInputHelper(props);

const variantClass = computed(() => {
    if (value.value) {
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
                    v-model="value"
                    class="sr-only peer"
                    type="checkbox"
                    :checked="value"
                    :disabled="disabled"
                    :id="inputId"
                    :name="name"
                    @focus="onFocus"
                    @blur="onBlur"
                    @change="$emit('change', value)"
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

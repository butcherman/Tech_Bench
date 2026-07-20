<script setup lang="ts">
import { computed, Ref, ref, toRef } from "vue";
import { useField } from "vee-validate";
import { useVariantHelper } from "../composables/variantHelper";

const emit = defineEmits<{
    focus: [];
    blur: [];
    change: [boolean];
}>();

const props = defineProps<{
    id: string;
    name: string;
    // Optional
    center?: boolean;
    label?: string;
    size?: componentSize;
    variant?: variantType;
}>();

const { getBackgroundClass } = useVariantHelper();

/**
 * Background of switch when checked.
 */
const switchClass = computed<string>(() => {
    let vClass = getBackgroundClass(props.variant ?? "primary");

    return `peer-checked:${vClass}`;
});

/**
 * Size of the switch
 */
const switchSizeClass = computed<string>(() => {
    switch (props.size) {
        case "large":
            return "w-15 h-8";
        case "small":
            return "w-8 h-4";
        default:
            return "w-10 h-6";
    }
});

const toggleSizeClass = computed<string>(() => {
    switch (props.size) {
        case "large":
            return "w-6 h-6 peer-checked:translate-x-7";
        case "small":
            return "w-3 h-3 top-1.5 peer-checked:translate-x-3";
        default:
            return "w-4 h-4 peer-checked:translate-x-4";
    }
});

/*
|-------------------------------------------------------------------------------
| Input State
|-------------------------------------------------------------------------------
*/
const hasError = computed<boolean>(() =>
    errorMessage.value?.length ? true : false,
);
const hasFocus = ref<boolean>(false);
const onFocus = (): void => {
    hasFocus.value = true;
    emit("focus");
};
const onBlur = () => {
    hasFocus.value = false;
    emit("blur");
};

/*
|-------------------------------------------------------------------------------
| Vee-Validate
|-------------------------------------------------------------------------------
*/
const nameRef = toRef(props, "name");
const {
    errorMessage,
    value,
}: {
    errorMessage: Ref<string | undefined, string | undefined>;
    value: Ref<boolean>;
} = useField(nameRef);
</script>

<template>
    <div
        class="my-2 flex flex-wrap gap-12"
        :class="{ 'items-center justify-center': center }"
    >
        <label class="relative inline-flex items-center cursor-pointer gap-3">
            <input
                v-model="value"
                class="sr-only peer"
                type="checkbox"
                :class="{
                    invalid: hasError,
                }"
                :name="name"
                @focus="onFocus"
                @blur="onBlur"
                @change="$emit('change', value)"
            />
            <div
                class="rounded-full peer bg-slate-300 transition-colors duration-200"
                :class="[switchClass, switchSizeClass]"
            />
            <span
                class="dot absolute left-1 top-1 bg-white rounded-full transition-transform duration-200 ease-in-out"
                :class="toggleSizeClass"
            />
            {{ label }}
        </label>
        <div class="text-xs text-danger">{{ errorMessage }}</div>
    </div>
</template>

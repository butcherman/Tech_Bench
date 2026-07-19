<script setup lang="ts">
import { computed } from "vue";
import { useVariantHelper } from "@/core/store/variantHelper";

const props = defineProps<{
    flat?: boolean;
    icon?: string;
    pill?: boolean;
    text?: string;
    size?: componentSize;
    variant?: variantType;
}>();

/**
 * Background class of the button
 */
const { getVariantClass } = useVariantHelper();

/**
 * Styling and other button data
 */
const variantClass = computed(() => getVariantClass(props.variant));
const buttonText = computed(() => props.text ?? "Submit");

/**
 * Button Size
 */
const sizeClass = computed<string>(() => {
    switch (props.size) {
        case "small":
            return "px-2 py-1";
        case "large":
            return "px-3 py-4";
        case "normal":
        default:
            return "px-3 py-2";
    }
});
</script>

<template>
    <button
        type="submit"
        :class="[
            sizeClass,
            variantClass,
            { 'rounded-full!': pill, 'shadow-xl': !flat },
        ]"
        class="rounded-lg inline-block text-center pointer"
    >
        <slot>
            <fa-icon v-if="icon" :icon="icon" />
            {{ buttonText }}
        </slot>
    </button>
</template>

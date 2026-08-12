<script setup lang="ts">
import { computed } from "vue";
import { useVariantHelper } from "@/core/composables/variantHelper";

const props = defineProps<{
    flat?: boolean;
    icon?: string;
    pill?: boolean;
    text?: string;
    size?: ComponentSize;
    variant?: VariantType;
}>();

const { getVariantClass } = useVariantHelper();

/**
 * Class and font color for the button
 */
const variantClass = computed(() =>
    getVariantClass(props.variant ?? "primary"),
);

/**
 * Button Size
 */
const sizeClass = computed<string>(() => {
    return {
        sm: "px-2 py-1",
        md: "px-3 py-4",
        lg: "px-3 py-6",
    }[props.size ?? "md"];
});
</script>

<template>
    <button
        :class="[
            sizeClass,
            variantClass,
            { 'rounded-full!': pill, 'shadow-xl': !flat },
        ]"
        class="rounded-lg inline-block text-center pointer"
        type="submit"
    >
        <slot>
            <fa-icon v-if="icon" :icon="icon" />
            {{ text }}
        </slot>
    </button>
</template>

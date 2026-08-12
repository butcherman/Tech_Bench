<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { useVariantHelper } from "@/core/composables/variantHelper";

const props = defineProps<{
    circle?: boolean;
    href?: string;
    icon?: string;
    pointer?: boolean;
    size?: ComponentSize;
    text?: string;
    variant?: VariantType;
}>();

const { getVariantClass } = useVariantHelper();

/**
 * If the href prop is populated, treat click as link component to allow
 * additional event handling
 */
const buttonType = computed<typeof Link | "button">(() =>
    props.href ? Link : "button",
);

/**
 * Determine if the badge should be rounded
 */
const borderClass = computed(() =>
    props.circle ? "rounded-full" : "rounded-md",
);

/**
 * Button Size
 */
const sizeClass = computed(() => {
    return {
        sm: "px-1 py-1",
        md: "px-2 py-1",
        lg: "px-3 py-4",
    }[props.size ?? "md"];
});
</script>

<template>
    <component
        :is="buttonType"
        :href="href"
        :class="[
            getVariantClass(variant ?? 'primary'),
            sizeClass,
            borderClass,
            { pointer: pointer },
        ]"
        class="inline-flex items-center text-xs font-medium inset-ring inset-ring-green-600/20"
    >
        <slot>
            <fa-icon v-if="icon" :icon="icon" />
            <span v-if="text">{{ text }}</span>
        </slot>
    </component>
</template>

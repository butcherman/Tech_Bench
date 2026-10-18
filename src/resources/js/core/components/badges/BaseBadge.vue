<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { useVariantHelper } from "@/core/composables/variantHelper";

const props = defineProps<{
    rounded?: boolean;
    href?: string;
    icon?: string;
    light?: boolean;
    pointer?: boolean;
    size?: ComponentSize;
    text?: string;
    variant?: VariantType;
}>();

const { getLightVariantClass, getVariantClass } = useVariantHelper();

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
    props.rounded ? "rounded-full" : "rounded-md",
);

/**
 * Variant color
 */
const variantColor = computed<string | void>(() => {
    if (props.variant) {
        if (props.light) {
            return getLightVariantClass(props.variant);
        }

        return getVariantClass(props.variant);
    }

    return;
});

/**
 * Button Size
 */
const sizeClass = computed(() => {
    return {
        sm: "px-1 py-1",
        md: "px-2 py-1",
        lg: "px-3 py-4",
    }[props.size ?? "sm"];
});
</script>

<template>
    <component
        :is="buttonType"
        :href="href"
        :class="[variantColor, sizeClass, borderClass, { pointer: pointer }]"
        class="inline-flex items-center justify-center text-xs font-bold"
    >
        <slot>
            <fa-icon v-if="icon" :icon="icon" />
            <span v-if="text">{{ text }}</span>
        </slot>
    </component>
</template>

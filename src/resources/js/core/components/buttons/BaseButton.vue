<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { useVariantHelper } from "@/core/composables/variantHelper";
import type { IconDefinition } from "@fortawesome/fontawesome-svg-core";

const props = defineProps<{
    active?: boolean;
    async?: boolean;
    disabled?: boolean;
    flat?: boolean;
    href?: string;
    icon?: string | IconDefinition;
    pill?: boolean;
    text?: string;
    size?: ComponentSize;
    variant?: VariantType;
}>();

const { getVariantClass, getActiveVariantClass } = useVariantHelper();

/**
 * If the href prop is populated, treat click as link component to allow
 * additional event handling
 */
const buttonType = computed<typeof Link | "button">(() =>
    props.href ? Link : "button",
);

/**
 * Class and font color for the button
 */
const variantClass = computed(() =>
    getVariantClass(props.variant ?? "primary"),
);

/**
 * Class and color when the button is active
 */
const activeClass = computed(() =>
    props.active ? getActiveVariantClass(props.variant ?? "primary") : "",
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
    <component
        :is="buttonType"
        :async="async"
        :href="href"
        :class="[
            sizeClass,
            variantClass,
            activeClass,
            { 'rounded-full!': pill, 'shadow-xl': !flat, pointer: !disabled },
        ]"
        class="rounded-lg inline-block text-center"
        type="button"
    >
        <slot>
            <fa-icon v-if="icon" :icon="icon" />
            {{ text }}
        </slot>
    </component>
</template>

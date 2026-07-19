<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { useVariantHelper } from "@/core/store/variantHelper";

const props = defineProps<{
    async?: boolean;
    flat?: boolean;
    href?: string;
    icon?: string;
    pill?: boolean;
    text?: string;
    size?: componentSize;
    variant?: variantType;
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
 * Class and font color for the button
 */
const variantClass = computed(() => getVariantClass(props.variant));

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
    <component
        :is="buttonType"
        :async="async"
        :href="href"
        :class="[
            sizeClass,
            variantClass,
            { 'rounded-full!': pill, 'shadow-xl': !flat },
        ]"
        class="rounded-lg inline-block text-center pointer"
        type="button"
    >
        <slot>
            <fa-icon v-if="icon" :icon="icon" />
            {{ text }}
        </slot>
    </component>
</template>

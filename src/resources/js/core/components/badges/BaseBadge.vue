<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { useVariantHelper } from "@/core/composables/variantHelper";

const props = defineProps<{
    href?: string;
    icon?: string;
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
 * Badge Size
 */
const badgeSize = computed(() => {
    switch (props.size) {
        case "large":
            return "w-15 h-15 text-4xl";
        case "small":
            return "w-5 h-5 text-sm";
        default:
            return "w-7 h-7";
    }
});
</script>

<template>
    <component
        :is="buttonType"
        :href="href"
        :class="[getVariantClass(variant), badgeSize, { pointer: href }]"
        class="dot rounded-full items-center justify-center inline-flex"
    >
        <fa-icon v-if="icon" :icon="icon" />
    </component>
</template>

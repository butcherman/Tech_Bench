<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { useVariantHelper } from "@/core/composables/variantHelper";

const props = defineProps<{
    href?: string;
    icon?: string;
    circle?: boolean;
    text?: string;
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
 * Determine if the badge should be rounded
 */
const sizeClass = computed(() =>
    props.circle ? "rounded-full p-1" : "rounded-md px-2 py-1 ",
);
</script>

<template>
    <component
        :is="buttonType"
        :href="href"
        :class="[getVariantClass(variant), sizeClass]"
        class="inline-flex items-center text-xs font-medium inset-ring inset-ring-green-600/20"
    >
        <fa-icon v-if="icon" :icon="icon" />
        <span v-if="text">{{ text }}</span>
    </component>
</template>

<template>
    <span
        class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset ring-gray-500/10 pointer"
        :class="variantClass"
        @click="handleClick"
    >
        <fa-icon v-if="icon" :icon="icon" />
        {{ text }}
    </span>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { handleLinkClick } from "@/Composables/links.module";
import { getVariantClass } from "@/Composables/styleData.module";

const props = defineProps<{
    href?: string;
    icon?: string;
    text?: string;
    variant?:
        | "danger"
        | "dark"
        | "error"
        | "help"
        | "info"
        | "light"
        | "primary"
        | "secondary"
        | "success"
        | "warning";
}>();

/*
|-------------------------------------------------------------------------------
| If href prop is populated, treat click as a link.
|-------------------------------------------------------------------------------
*/
const handleClick = (event: MouseEvent): void => {
    if (props.href) {
        handleLinkClick(event, props.href);
    }
};

/*
|-------------------------------------------------------------------------------
| Background Color
|-------------------------------------------------------------------------------
*/
const variantClass = computed<string>(() => getVariantClass(props.variant));
</script>

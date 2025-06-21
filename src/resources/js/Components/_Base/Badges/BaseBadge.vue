<script setup lang="ts">
import { computed } from "vue";
import { handleLinkClick } from "@/Composables/links.module";

const props = defineProps<{
    href?: string;
    icon?: string;
    text?: string;
    variant?: elementVariant;
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
const variantClass = computed<string>(() => {
    switch (props.variant) {
        case "danger":
            return "bg-rose-600 text-white";
        case "dark":
            return "bg-gray-900 text-white";
        case "error":
            return "bg-red-500 text-white";
        case "help":
            return "bg-violet-600 text-white";
        case "info":
            return "bg-blue-400 text-white";
        case "light":
            return "bg-neutral-300";
        case "primary":
            return "bg-blue-500 text-white";
        case "secondary":
            return "bg-blue-300";
        case "success":
            return "bg-green-500 text-white";
        case "warning":
            return "bg-yellow-400";
        default:
            return "bg-blue-500 text-white";
    }
});
</script>

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

<template>
    <button
        type="button"
        class="rounded-lg"
        :class="[
            sizeClass,
            variantClass,
            { '!rounded-full': pill, 'shadow-xl': !flat },
        ]"
        @click="handleClick"
    >
        <slot>
            <fa-icon v-if="icon" :icon="icon" />
            {{ text }}
        </slot>
    </button>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { handleLinkClick } from "@/Composables/links.module";

const props = defineProps<{
    flat?: boolean;
    href?: string;
    icon?: string;
    pill?: boolean;
    text?: string;
    size?: "small" | "normal" | "large";
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
|---------------------------------------------------------------------------
| If href prop is populated, treat click as a link.
|---------------------------------------------------------------------------
*/
const handleClick = (event: MouseEvent) => {
    if (props.href) {
        handleLinkClick(event, props.href);
    }
};

/*
|---------------------------------------------------------------------------
| Button Size
|---------------------------------------------------------------------------
*/
const sizeClass = computed(() => {
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

/*
|---------------------------------------------------------------------------
| Background Color
|---------------------------------------------------------------------------
*/
const variantClass = computed(() => {
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

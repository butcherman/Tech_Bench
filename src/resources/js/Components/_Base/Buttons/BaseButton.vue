<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps<{
    flat?: boolean;
    href?: string;
    icon?: string;
    pill?: boolean;
    text?: string;
    size?: "small" | "normal" | "large";
    variant?: elementVariant;
}>();

/*
|-------------------------------------------------------------------------------
| If href prop is populated, treat click as a link.
|-------------------------------------------------------------------------------
*/
const buttonType = computed<typeof Link | "button">(() =>
    props.href ? Link : "button"
);

/*
|-------------------------------------------------------------------------------
| Button Size
|-------------------------------------------------------------------------------
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
    <component
        :is="buttonType"
        :href="href"
        :class="[
            sizeClass,
            variantClass,
            { '!rounded-full': pill, 'shadow-xl': !flat },
        ]"
        class="rounded-lg inline-block"
        type="button"
    >
        <slot>
            <fa-icon v-if="icon" :icon="icon" />
            {{ text }}
        </slot>
    </component>
</template>

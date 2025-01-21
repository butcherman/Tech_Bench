<template>
    <button
        class="rounded-lg"
        type="button"
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
import { getVariantClass } from "@/Composables/styleData.module";

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
const variantClass = computed<string>(() => getVariantClass(props.variant));
</script>

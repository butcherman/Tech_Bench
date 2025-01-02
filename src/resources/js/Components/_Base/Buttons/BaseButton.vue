<template>
    <Button class="px-4 py-2" :class="variantClass">
        <slot>
            <fa-icon v-if="icon" :icon="icon" />
            {{ text }}
        </slot>
    </Button>
</template>

<script setup lang="ts">
import { Button } from "primevue";
import { computed } from "vue";

const props = defineProps<{
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
            return "bg-blue-300";
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

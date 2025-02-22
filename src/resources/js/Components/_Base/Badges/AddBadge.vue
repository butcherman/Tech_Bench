<script setup lang="ts">
import BaseBadge from "./BaseBadge.vue";

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
</script>

<template>
    <BaseBadge
        v-bind="props"
        :icon="icon ?? 'plus'"
        :variant="variant ?? 'info'"
    />
</template>

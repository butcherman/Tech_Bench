<script setup lang="ts">
import BaseBadge from "./BaseBadge.vue";

const props = defineProps<{
    href?: string;
    icon?: string;
    text?: string;
    variant?: elementVariant;
}>();
</script>

<template>
    <BaseBadge
        v-bind="props"
        :icon="icon ?? 'pencil'"
        :variant="variant ?? 'warning'"
    />
</template>

<script setup lang="ts">
import BaseBadge from "./BaseBadge.vue";
import { computed } from "vue";

const props = defineProps<{
    expanded: boolean;

    circle?: boolean;
    variant?: VariantType;
}>();

const badgeIcon = computed(() =>
    props.expanded
        ? "fa-up-right-and-down-left-from-center"
        : "fa-down-left-and-up-right-to-center",
);
</script>

<template>
    <BaseBadge v-bind="props" :variant="variant ?? 'info'" class="pointer">
        <fa-icon :icon="badgeIcon" />
    </BaseBadge>
</template>

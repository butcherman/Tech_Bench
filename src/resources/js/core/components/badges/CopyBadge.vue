<script setup lang="ts">
import BaseBadge from "./BaseBadge.vue";
import { computed } from "vue";

const props = defineProps<{
    href?: string;
    icon?: string;
    circle?: boolean;
    text?: string;
    variant?: VariantType;
    pointer?: boolean;
}>();

const badgeIcon = computed(() => props.icon ?? "copy");
</script>

<template>
    <BaseBadge v-bind="props" :variant="variant ?? 'warning'">
        <slot>
            <fa-icon v-if="badgeIcon" :icon="badgeIcon" />
            <span v-if="text">{{ text }}</span>
        </slot>
    </BaseBadge>
</template>

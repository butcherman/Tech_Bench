<script setup lang="ts">
import BaseBadge from "./BaseBadge.vue";
import { computed } from "vue";

const props = defineProps<{
    value: boolean;

    circle?: boolean;
}>();

const badgeIcon = computed(() => (props.value ? "check" : "xmark"));
const variant = computed(() => (props.value ? "success" : "danger"));
</script>

<template>
    <BaseBadge v-bind="props" :variant="variant">
        <slot>
            <fa-icon v-if="badgeIcon" :icon="badgeIcon" />
        </slot>
    </BaseBadge>
</template>

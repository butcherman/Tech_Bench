<template>
    <span
        class="pointer"
        :class="variant"
        :title="title"
        v-tooltip
        @click="$emit('clicked')"
    >
        <fa-icon icon="triangle-exclamation" />
    </span>
</template>

<script setup lang="ts">
import { computed } from "vue";

defineEmits(["clicked"]);
const props = defineProps<{
    textVariant?: "warning" | "danger";
    title?: string;
}>();

const variant = computed(() => {
    if (props.textVariant) {
        return `text-${props.textVariant}`;
    }

    return "text-danger";
});
</script>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    value: boolean;
}>();

const valueIcon = computed(() => (props.value ? "check" : "xmark"));
const valueColor = computed(() =>
    props.value ? "text-success" : "text-danger",
);
</script>

<template>
    <div>
        <fa-icon :icon="valueIcon" :class="valueColor" />
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps<{
    value: boolean;
}>();

const boolIcon = computed(() => (props.value ? "check" : "xmark"));
const textColor = computed(() =>
    props.value ? "text-success" : "text-danger",
);
</script>

<template>
    <div>
        <fa-icon :icon="boolIcon" :class="textColor" />
    </div>
</template>

<script setup lang="ts">
defineProps<{
    tooltip?: string;
}>();
</script>

<template>
    <span
        class="badge bg-warning rounded-pill pointer mx-1"
        :title="tooltip ? tooltip : 'Check'"
        v-tooltip
    >
        <fa-icon icon="check" />
    </span>
</template>

<template>
    <span
        class="badge bg-danger rounded-pill pointer mx-1"
        :title="tooltip ? tooltip : 'Delete'"
        v-tooltip
    >
        <fa-icon icon="fa-trash-can" />
    </span>
</template>

<script setup lang="ts">
defineProps<{
    tooltip?: string;
}>();
</script>

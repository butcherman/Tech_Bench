<template>
    <span
        class="badge bg-warning rounded-pill pointer"
        :title="tooltip ? tooltip : 'Edit'"
        v-tooltip
    >
        <fa-icon icon="fa-edit" />
    </span>
</template>

<script setup lang="ts">
defineProps<{
    tooltip?: string;
}>();
</script>

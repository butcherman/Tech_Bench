<script setup lang="ts">
defineProps<{
    tooltip?: string;
}>();
</script>

<template>
    <span
        class="badge bg-primary rounded-pill pointer mx-1"
        :title="tooltip ? tooltip : 'Restore'"
        v-tooltip
    >
        <fa-icon icon="fa-trash-restore" />
    </span>
</template>

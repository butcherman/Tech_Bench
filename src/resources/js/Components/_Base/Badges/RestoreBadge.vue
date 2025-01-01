<template>
    <v-chip
        class="pointer"
        color="blue"
        size="x-small"
        v-tooltip="tooltip ?? 'Restore'"
    >
        <font-awesome-icon icon="trash-restore" />
    </v-chip>
</template>

<script setup lang="ts">
defineProps<{
    tooltip?: string;
}>();
</script>

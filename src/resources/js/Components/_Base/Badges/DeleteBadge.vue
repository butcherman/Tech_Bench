<template>
    <v-chip
        class="pointer"
        color="red"
        size="x-small"
        v-tooltip="tooltip ?? 'Delete'"
    >
        <font-awesome-icon icon="trash-can" />
    </v-chip>
</template>

<script setup lang="ts">
defineProps<{
    tooltip?: string;
}>();
</script>

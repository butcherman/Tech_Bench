<template>
    <v-chip color="orange" size="small" v-tooltip="tooltip ?? 'Edit'">
        <font-awesome-icon icon="pencil" />
    </v-chip>
</template>

<script setup lang="ts">
defineProps<{
    tooltip?: string;
}>();
</script>

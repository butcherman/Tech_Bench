<template>
    <div>
        <fa-icon v-if="column.icon" :icon="column.icon" />
        {{ column.label }}
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    column: {
        label: string;
        field: string;
        icon?: string;
    };
}>();
</script>

<script setup lang="ts" generic="T">
defineProps<{
    label?: string;
    meta?: {
        icon?: string;
    };
}>();
</script>

<template>
    <div>
        <fa-icon v-if="meta?.icon" :icon="meta?.icon" />
        {{ label }}
    </div>
</template>

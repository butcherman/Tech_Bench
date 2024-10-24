<template>
    <button
        type="button"
        class="btn btn-info mx-1"
        :class="{ 'rounded-5': pill, 'btn-sm': small }"
    >
        <fa-icon icon="fa-plus" class="me-1" />
        <slot>{{ text || "Add" }}</slot>
    </button>
</template>

<script setup lang="ts">
defineProps<{
    pill?: boolean;
    small?: boolean;
    text?: string;
}>();
</script>

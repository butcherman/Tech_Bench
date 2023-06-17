<template>
    <button class="btn btn-warning mx-1" :class="{ 'btn-pill': pill }">
        <fa-icon icon="edit" class="me-1" />
        <slot>Edit</slot>
    </button>
</template>

<script setup lang="ts">
defineProps<{
    pill?: boolean;
}>();
</script>

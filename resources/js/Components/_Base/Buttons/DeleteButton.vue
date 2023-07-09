<template>
    <button type="button" class="btn btn-danger mx-1" :class="{ 'btn-pill': pill }">
        <fa-icon icon="fa-trash-can" class="me-1" />
        <slot>Delete</slot>
    </button>
</template>

<script setup lang="ts">
defineProps<{
    pill?: boolean;
}>();
</script>

<template>
    <button class="btn btn-danger mx-1" :class="{ 'btn-pill': pill }">
        <fa-icon icon="fa-trash-can" />
        Delete
    </button>
</template>

<script setup lang="ts">
defineProps<{
    pill?: boolean;
}>();
</script>

<template>
    <button class="btn btn-info" :class="{ 'btn-pill': pill, 'btn-sm': small }">
        <fa-icon icon="fa-plus" />
        Add
    </button>
</template>

<script setup lang="ts">
defineProps<{
    pill?: boolean;
    small?: boolean;
}>();
</script>

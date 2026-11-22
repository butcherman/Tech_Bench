<script setup lang="ts">
const props = defineProps<{
    recordStart: number;
    recordEnd: number;
    totalRecords: number;
}>();
</script>

<template>
    <div class="flex justify-end">
        <span class="hidden lg:inline"> Showing </span>
        {{ recordStart }} - {{ recordEnd }} of
        {{ totalRecords }}
        <span class="hidden lg:inline"> Results </span>
    </div>
</template>

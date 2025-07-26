<script setup lang="ts">
defineProps<{
    isEmpty: boolean;
}>();
</script>

<template>
    <div v-if="isEmpty" class="absolute top-5 w-full">
        <h4 class="text-center text-muted opacity-50">Drag Element Here</h4>
    </div>
</template>

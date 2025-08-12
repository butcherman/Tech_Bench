<script setup lang="ts">
//
const props = defineProps<{
    element: workbookElement;
    container: workbookElement[];
}>();
</script>

<template>
    <div>
        {{ element }}
    </div>
</template>

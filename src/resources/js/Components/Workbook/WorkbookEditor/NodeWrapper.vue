<script setup lang="ts">
const props = defineProps<{
    node: workbookNode;
}>();
</script>

<template>
    <div>
        {{ node }}
    </div>
</template>

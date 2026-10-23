<script setup lang="ts">
const props = defineProps<{
    class: string;
    text: string;
    tag: string;
    index: string;
}>();
</script>

<template>
    <component :is="tag" :class="class">{{ text }}</component>
</template>

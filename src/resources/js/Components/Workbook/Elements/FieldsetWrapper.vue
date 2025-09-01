<script setup lang="ts">
import ElementContainer from "./ElementContainer.vue";

defineProps<{
    element: workbookElement;
}>();
</script>

<template>
    <component :key="element.index" :is="element.tag" :class="element.class">
        <legend>{{ element.text }}</legend>
        <ElementContainer
            v-if="element.container"
            :element-list="element.container"
        />
    </component>
</template>

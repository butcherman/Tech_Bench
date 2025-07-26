<script setup lang="ts">
import ComponentListContainer from "./ComponentListContainer.vue";

defineProps<{
    element: workbookElement;
}>();
</script>

<template>
    <component :is="element.tag" :class="element.class" class="relative">
        <legend>{{ element.text }}</legend>
        <template v-if="element.container">
            <ComponentListContainer :component-list="element.container" />
        </template>
    </component>
</template>

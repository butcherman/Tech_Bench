<script setup lang="ts">
import ComponentListContainer from "./ComponentListContainer.vue";

defineProps<{
    gridWrapper: workbookElement;
}>();
</script>

<template>
    <div :class="gridWrapper.class" class="py-3">
        <component
            v-for="grid in gridWrapper.container"
            :key="grid.index"
            :is="grid.tag"
            :class="grid.class"
        >
            <template v-if="grid.container">
                <ComponentListContainer :component-list="grid.container" />
            </template>
        </component>
    </div>
</template>

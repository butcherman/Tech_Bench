<script setup lang="ts">
import ElementContainer from "./ElementContainer.vue";

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
            <ElementContainer
                v-if="grid.container"
                :element-list="grid.container"
            />
        </component>
    </div>
</template>

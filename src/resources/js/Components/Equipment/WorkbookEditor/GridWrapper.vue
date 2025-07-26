<script setup lang="ts">
import ComponentListContainer from "./ComponentListContainer.vue";
import ComponentOptions from "./ComponentOptions.vue";
import EmptyContainer from "./EmptyContainer.vue";

defineProps<{
    gridWrapper: workbookElement;
    container: workbookElement[];
}>();
</script>

<template>
    <div :class="gridWrapper.class" class="py-3 relative group/wrapper">
        <ComponentOptions
            :component="gridWrapper"
            :container="container"
            class="hidden group-hover/wrapper:flex"
        />
        <component
            v-for="grid in gridWrapper.container"
            :key="grid.index"
            :is="grid.tag"
            :class="grid.class"
            class="border border-slate-300 border-dashed rounded-md relative"
        >
            <template v-if="grid.container">
                <EmptyContainer :isEmpty="grid.container.length === 0" />
                <ComponentListContainer :component-list="grid.container" />
            </template>
        </component>
    </div>
</template>

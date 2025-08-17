<script setup lang="ts">
import ElementOptions from "../Canvas/ElementOptions.vue";
import EmptyContainer from "./EmptyContainer.vue";
import ListContainer from "./ListContainer.vue";
import {
    cloneComponent,
    deleteElement,
    editElement,
} from "@/Composables/Equipment/WorkbookEditor.module";

defineProps<{
    gridWrapper: workbookElement;
    container: workbookElement[];
}>();
</script>

<template>
    <div :class="gridWrapper.class" class="py-3 relative group/wrapper">
        <ElementOptions
            class="hidden group-hover/wrapper:flex"
            :can-edit="false"
            @clone="cloneComponent(gridWrapper, container)"
            @delete="deleteElement(gridWrapper, container)"
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
                <ListContainer :container-list="grid.container" />
            </template>
        </component>
    </div>
</template>

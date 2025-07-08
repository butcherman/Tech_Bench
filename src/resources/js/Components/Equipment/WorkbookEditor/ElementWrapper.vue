<script setup lang="ts">
import draggableComponent from "vuedraggable";
import ElementData from "./ElementData.vue";
import {
    deleteElement,
    editElement,
} from "@/Composables/Equipment/WorkbookEditor";

defineProps<{
    component: workbookEntry;
    container: workbookEntry[];
}>();
</script>

<template>
    <template v-if="component.type === 'wrapper' && component.container">
        <component
            :is="component.tag"
            :class="component.class"
            class="relative"
        >
            <legend>{{ component.text }}</legend>
            <div
                class="text-xs absolute end-0 -top-4 group-hover:block pointer"
            >
                <span
                    class="text-warning me-2"
                    v-tooltip="'Edit'"
                    @click="editElement(component)"
                >
                    <fa-icon icon="pencil" />
                </span>
                <span
                    class="text-danger"
                    v-tooltip="'Delete'"
                    @click="deleteElement(component, container)"
                >
                    <fa-icon icon="trash-alt" />
                </span>
            </div>
            <div v-if="!component.container.length">
                <h4 class="text-center text-muted opacity-50">
                    Drag Element Here to Start Building Workbook
                </h4>
            </div>
            <draggableComponent
                :list="component.container"
                :group="{ name: 'workbook', put: true }"
                item-key="index"
                class="min-h-10"
            >
                <template #item="{ element }">
                    <ElementWrapper
                        :component="element"
                        :container="container"
                    />
                </template>
            </draggableComponent>
        </component>
    </template>
    <template v-else>
        <ElementData :elem="component" :container="container" />
    </template>
</template>

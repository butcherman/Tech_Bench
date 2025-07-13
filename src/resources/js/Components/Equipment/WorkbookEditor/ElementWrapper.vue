<script setup lang="ts">
import draggableComponent from "vuedraggable";
import ElementData from "./ElementData.vue";
import GridWrapper from "./GridWrapper.vue";
import {
    deleteElement,
    editElement,
    updatePreview,
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
            class="relative group"
        >
            <legend>{{ component.text }}</legend>
            <div
                class="hidden text-xs absolute end-0 -top-4 group-hover:block pointer"
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
            <div
                v-if="!component.container.length"
                class="absolute top-0 w-full"
            >
                <h4 class="text-center text-muted opacity-50">
                    Drag Element Here
                </h4>
            </div>
            <draggableComponent
                :list="component.container"
                :group="{ name: 'workbook', put: true }"
                item-key="index"
                class="min-h-10"
                @change="updatePreview()"
            >
                <template #item="{ element }">
                    <GridWrapper
                        v-if="element.type === 'grid-wrapper'"
                        :grid-row="element"
                        :container="container"
                    />
                    <ElementWrapper
                        v-else
                        :component="element"
                        :container="component.container"
                    />
                </template>
            </draggableComponent>
        </component>
    </template>
    <template v-else>
        <ElementData :elem="component" :container="container" />
    </template>
</template>

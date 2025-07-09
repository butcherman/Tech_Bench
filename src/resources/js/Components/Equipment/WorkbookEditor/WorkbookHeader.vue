<script setup lang="ts">
import draggableComponent from "vuedraggable";
import ElementData from "./ElementData.vue";
import okModal from "@/Modules/okModal";
import { workbookData } from "@/Composables/Equipment/WorkbookEditor";

/**
 * Only these element 'types' are allowed to be dropped in the header.
 */
const allowedInHeader: string[] = ["text", "static"];

/**
 * Verify that the element dropped is allowed in the header.
 */
const onHeaderDrop = (event: workbookDropEvent) => {
    if (event.added) {
        if (!allowedInHeader.includes(event.added.element.type)) {
            okModal("Only Text Elements are Allowed in the Header");
            workbookData.value.header.splice(event.added.newIndex, 1);
        }
    }
};
</script>

<template>
    <div
        class="border border-dashed border-slate-300 rounded-lg relative hover:border-dotted group/header"
    >
        <div
            class="hidden group-hover/header:block text-xs absolute -top-4 right-0 border-t border-s border-e border-slate-300 px-1 rounded-md text-muted"
        >
            Header
        </div>
        <div v-if="!workbookData.header.length" class="absolute top-5 w-full">
            <h4 class="text-center text-muted opacity-50">
                Drag Element Here to Start Building Header
            </h4>
        </div>
        <draggableComponent
            :list="workbookData.header"
            :group="{ name: 'workbook', put: true }"
            class="min-h-20"
            item-key="index"
            @change="onHeaderDrop"
        >
            <template #item="{ element }">
                <ElementData :elem="element" :container="workbookData.header" />
            </template>
        </draggableComponent>
    </div>
</template>

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
const onFooterDrop = (event: workbookDropEvent) => {
    if (event.added) {
        if (!allowedInHeader.includes(event.added.element.type)) {
            okModal("Only Text Elements are Allowed in the Footer");
            workbookData.value.footer.splice(event.added.newIndex, 1);
        }
    }
};
</script>

<template>
    <div
        class="border border-dashed border-slate-300 rounded-lg relative mb-2 hover:border-dotted group/footer"
    >
        <div
            class="hidden group-hover/footer:block text-xs absolute -top-4 right-0 border-t border-s border-e border-slate-300 px-1 rounded-md text-muted"
        >
            Footer
        </div>
        <div v-if="!workbookData.footer.length" class="absolute top-5 w-full">
            <h4 class="text-center text-muted opacity-50">
                Drag Element Here to Start Building Footer
            </h4>
        </div>
        <draggableComponent
            :list="workbookData.footer"
            :group="{ name: 'workbook', put: true }"
            class="min-h-20"
            item-key="index"
            @change="onFooterDrop"
        >
            <template #item="{ element }">
                <ElementData :elem="element" :container="workbookData.footer" />
            </template>
        </draggableComponent>
    </div>
</template>

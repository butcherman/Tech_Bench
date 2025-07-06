<script setup lang="ts">
import draggableComponent from "vuedraggable";
import okModal from "@/Modules/okModal";
import {
    editElement,
    workbookData,
} from "@/Composables/Equipment/WorkbookEditor";

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

/**
 * Delete a Header Row
 */
const deleteHeaderRow = (rowIndex: number): void => {
    workbookData.value.header.splice(rowIndex, 1);
};
</script>

<template>
    <draggableComponent
        :list="workbookData.header"
        :group="{ name: 'workbook', put: true }"
        item-key="index"
        @change="onHeaderDrop"
    >
        <template #item="{ element, index }">
            <div class="group relative">
                <div
                    class="hidden text-xs fixed end-8 group-hover:block pointer"
                >
                    <span
                        v-if="element.type !== 'static'"
                        class="text-warning me-2"
                        v-tooltip="'Edit'"
                        @click="editElement(element)"
                    >
                        <fa-icon icon="pencil" />
                    </span>
                    <span
                        class="text-danger"
                        v-tooltip="'Delete'"
                        @click="deleteHeaderRow(index)"
                    >
                        <fa-icon icon="trash-alt" />
                    </span>
                </div>
                <component
                    :is="element.tag"
                    :class="element.class"
                    class="group-hover:border group-hover:border-green-300"
                    v-bind="element.props"
                    v-html="element.text"
                />
            </div>
        </template>
    </draggableComponent>
</template>

<script setup lang="ts">
import draggableComponent from "vuedraggable";
import ElementWrapper from "./ElementWrapper.vue";
import okModal from "@/Modules/okModal";
import {
    deleteElement,
    updatePreview,
} from "@/Composables/Equipment/WorkbookEditor";

defineProps<{
    gridRow: workbookEntry;
    container: workbookEntry[];
}>();

const onListChange = (
    event: workbookDropEvent,
    container?: workbookEntry[]
) => {
    if (event.added) {
        if (event.added.element.type === "grid-wrapper" && container?.length) {
            okModal("Cannot Place Grid Element Inside Grid Element");
            container.splice(event.added.newIndex, 1);
        }

        updatePreview();
    }
};
</script>

<template>
    <div
        :class="gridRow.class"
        class="relative group/row border-dashed hover:border hover:border-slate-300 rounded-lg p-1"
    >
        <div
            class="absolute -top-4 right-0 text-xs hidden group-hover/row:block"
        >
            <span
                class="text-danger pointer"
                v-tooltip="'Delete Grid Row'"
                @click="deleteElement(gridRow, container)"
            >
                <fa-icon icon="trash-alt" />
            </span>
        </div>
        <div
            v-for="col in gridRow.container"
            :class="col.class"
            class="border border-dashed border-slate-300 rounded-lg"
        >
            <div v-if="col.container" class="h-full relative">
                <div v-if="!col.container.length" class="absolute top-5 w-full">
                    <h4 class="text-center text-muted opacity-50">
                        Drag Element Here
                    </h4>
                </div>
                <draggableComponent
                    :list="col.container"
                    :group="{ name: 'workbook', put: true }"
                    item-key="index"
                    class="h-full w-full"
                    @change="onListChange($event, col.container)"
                >
                    <template #item="{ element }">
                        <div>
                            <ElementWrapper
                                :component="element"
                                :container="col.container"
                            />
                        </div>
                    </template>
                </draggableComponent>
            </div>
        </div>
    </div>
</template>

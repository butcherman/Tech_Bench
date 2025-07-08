<script setup lang="ts">
import draggableComponent from "vuedraggable";
import ElementWrapper from "./ElementWrapper.vue";
import okModal from "@/Modules/okModal";

defineProps<{
    gridRow: workbookEntry;
}>();

const onListChange = (event: workbookDropEvent, container: workbookEntry[]) => {
    if (event.added) {
        if (event.added.element.type === "grid-wrapper") {
            okModal("Cannot Place Grid Element Inside Grid Element");
            container.splice(event.added.newIndex, 1);
        }
    }
};
</script>

<template>
    <div :class="gridRow.class">
        <div
            v-for="col in gridRow.container"
            :class="col.class"
            class="border border-dotted border-slate-300 rounded-lg"
        >
            <div class="h-full">
                <draggableComponent
                    :list="col.container"
                    :group="{ name: 'workbook', put: true }"
                    item-key="index"
                    class="h-full w-full"
                    @change="onListChange($event, col.container)"
                >
                    <template #item="{ element }">
                        <div>
                            <ElementWrapper :component="element" />
                        </div>
                    </template>
                </draggableComponent>
            </div>
        </div>
    </div>
</template>

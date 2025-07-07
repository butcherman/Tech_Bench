<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import draggableComponent from "vuedraggable";
import { elementList } from "@/Composables/Equipment/WorkbookElements";
import { v4 } from "uuid";

/**
 * Make a copy of the pulled element with a unique ID attached.
 */
const cloneElement = (element: workbookElement): workbookEntry => {
    let newElement = { ...element };
    delete newElement.componentData;

    newElement.index = v4();

    // If this is a grid wrapper, create unique ID's on the grid elements
    if (element.type === "grid-wrapper") {
        element.container?.forEach((elem) => (elem.index = v4()));
    }

    return newElement;
};
</script>

<template>
    <Card class="h-full" title="Workbook Elements">
        <div class="text-center text-muted">Drag Element to Canvas</div>
        <draggableComponent
            :list="elementList"
            :group="{ name: 'workbook', pull: 'clone', put: false }"
            item-key="index"
            :clone="cloneElement"
        >
            <template #item="{ element }">
                <div
                    class="border border-slate-300 rounded-lg my-2 flex gap-2 pointer"
                >
                    <div class="w-8 pt-2 ms-1">
                        <button
                            class="border border-slate-400 bg-slate-300 rounded-md w-full pointer"
                        >
                            <span v-if="element.componentData.buttonIcon">
                                <fa-icon
                                    :icon="element.componentData.buttonIcon"
                                />
                            </span>
                            <span v-else>
                                {{ element.componentData.buttonText }}
                            </span>
                        </button>
                    </div>
                    <div class="grow flex flex-col">
                        <strong>{{ element.componentData.label }}</strong>
                        <small>{{ element.componentData.help }}</small>
                    </div>
                </div>
            </template>
        </draggableComponent>
    </Card>
</template>

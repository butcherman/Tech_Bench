<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import draggableComponent from "vuedraggable";
import { elementList } from "@/Composables/Equipment/WorkbookElements";
import { v4 } from "uuid";
import { ref } from "vue";
import { imDirty } from "../../../Composables/Equipment/WorkbookEditor";

/**
 * Make a copy of the pulled element with a unique ID attached.
 */
const cloneElement = (element: workbookElement): workbookEntry => {
    let newElement = JSON.parse(JSON.stringify(element));
    delete newElement.componentData;

    newElement.index = v4();

    // If this element has children, create unique ID's on the child elements
    newElement.container?.forEach((elem: workbookEntry) => (elem.index = v4()));

    imDirty();

    return newElement;
};

const activeName = ref("formatting");
const activeList = ref(elementList.value.formatting);

const setActive = (activeType: "formatting" | "textInput" | "specialInput") => {
    activeName.value = activeType;
    activeList.value = elementList.value[activeType];
};
</script>

<template>
    <Card class="h-full" title="Workbook Elements">
        <div class="text-center text-muted">Drag Element to Canvas</div>
        <div class="grid grid-cols-3 gap-1">
            <button
                type="button"
                class="border border-slate-300 rounded-sm bg-slate-200 pointer"
                :class="{
                    'bg-slate-400!': activeName === 'formatting',
                }"
                @click="setActive('formatting')"
            >
                Formatting
            </button>
            <button
                type="button"
                class="border border-slate-300 rounded-sm bg-slate-200 pointer"
                :class="{
                    'bg-slate-400': activeName === 'textInput',
                }"
                @click="setActive('textInput')"
            >
                Text Inputs
            </button>
            <button
                type="button"
                class="border border-slate-300 rounded-sm bg-slate-200 pointer"
                :class="{
                    'bg-slate-400': activeName === 'specialInput',
                }"
                @click="setActive('specialInput')"
            >
                Other Inputs
            </button>
        </div>
        <draggableComponent
            :list="activeList"
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

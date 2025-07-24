<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import draggableComponent from "vuedraggable";
import { cloneElement } from "@/Composables/Equipment/WorkbookEditor.module";
import { inputElements } from "@/Composables/Equipment/FormattingInputElements.module";
import { pageElements } from "@/Composables/Equipment/FormattingPageElements.module";
import { ref } from "vue";

/*
|-------------------------------------------------------------------------------
| Active list of components visible to add to the canvas
|-------------------------------------------------------------------------------
*/
const activeList = ref<workbookElement[]>(pageElements);
const activeName = ref<"page" | "inputs">("page");
const setActive = (type: "page" | "inputs"): void => {
    activeName.value = type;

    switch (type) {
        case "page":
            activeList.value = pageElements;
            break;
        case "inputs":
            activeList.value = inputElements;
            break;
    }
};
</script>

<template>
    <Card class="h-full" title="Workbook Components">
        <div class="sticky top-14">
            <div class="grid grid-cols-2">
                <button
                    type="button"
                    class="border border-slate-300 rounded-s-sm pointer"
                    :class="{ 'bg-slate-200': activeName === 'page' }"
                    @click="setActive('page')"
                >
                    Page
                </button>
                <button
                    type="button"
                    class="border border-slate-300 rounded-e-sm pointer"
                    :class="{ 'bg-slate-200': activeName === 'inputs' }"
                    @click="setActive('inputs')"
                >
                    Inputs
                </button>
            </div>
            <p class="text-center text-muted">
                Drag Component to Workbook Canvas
            </p>
            <draggableComponent
                :list="activeList"
                :group="{
                    name: 'workbook',
                    pull: 'clone',
                    put: false,
                }"
                :sort="false"
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
        </div>
    </Card>
</template>

<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import draggableComponent from "vuedraggable";
import { computed, ref } from "vue";
import { designNodes } from "@/Composables/Workbook/Canvas/DesignNodes";
import { formNodes } from "@/Composables/Workbook/Canvas/FormNodes";
import { tableNodes } from "@/Composables/Workbook/Canvas/TableNodes";
import { getClonedNode } from "@/Composables/Workbook/WorkbookEditor.module";

type nodeType = "design" | "form" | "table";

const activeListName = ref<nodeType>("design");
const activeList = computed(() => {
    switch (activeListName.value) {
        case "design":
            return designNodes;
        case "form":
            return formNodes;
        case "table":
            return tableNodes;
    }
});
</script>

<template>
    <Card class="h-full" title="Workbook Elements">
        <div class="sticky top-14">
            <select
                v-model="activeListName"
                class="w-full border border-slate-400 rounded-lg p-1"
            >
                <option value="design">Design Elements</option>
                <option value="form">Form Elements</option>
                <option value="table">Data Table Elements</option>
            </select>
        </div>
        <p class="text-center text-muted py-2">
            Drag Element to Workbook Canvas
        </p>
        <div>
            <draggableComponent
                :clone="getClonedNode"
                :list="activeList"
                :sort="false"
                :group="{
                    name: 'workbook',
                    pull: 'clone',
                    put: false,
                }"
                item-key="index"
            >
                <template #item="{ element }">
                    <div
                        class="border border-slate-300 rounded-lg my-2 flex gap-2 pointer"
                    >
                        <div class="w-8 pt-2 ms-1">
                            <button
                                class="border border-slate-400 bg-slate-300 rounded-md w-full pointer"
                            >
                                <span v-if="element.nodeLabel.buttonIcon">
                                    <fa-icon
                                        :icon="element.nodeLabel.buttonIcon"
                                    />
                                </span>
                                <span v-else>
                                    {{ element.nodeLabel.buttonText }}
                                </span>
                            </button>
                        </div>
                        <div class="grow flex flex-col">
                            <strong>{{ element.nodeLabel.label }}</strong>
                            <small>{{ element.nodeLabel.help }}</small>
                        </div>
                    </div>
                </template>
            </draggableComponent>
        </div>
    </Card>
</template>

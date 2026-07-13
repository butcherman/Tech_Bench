<script setup lang="ts">
import draggableComponent from "vuedraggable";
import EmptyContainer from "./EmptyContainer.vue";
import NodeWrapper from "./NodeWrapper.vue";
import { TabPanels, TabPanel } from "primevue";
import { workbookData } from "@/Composables/Workbook/WorkbookEditor.module.js";
</script>

<template>
    <TabPanels
        class="h-full relative rounded-lg border border-dashed border-slate-400 hover:border-dotted group/body p-1"
    >
        <div
            class="absolute -top-5 right-4 rounded-t-md border-t border-s border-e border-dotted border-slate-400 py-0 px-1 text-xs text-muted hidden group-hover/body:block"
        >
            Body
        </div>
        <TabPanel
            v-for="page in workbookData.body"
            :key="page.page"
            :value="page.page"
            class="h-full relative"
        >
            <EmptyContainer :is-empty="page.contents.length === 0" />
            <draggableComponent
                class="h-full"
                :list="page.contents"
                :group="{
                    name: 'workbook',
                    put: true,
                }"
                item-key="index"
            >
                <template #item="{ element }">
                    <NodeWrapper :node="element" />
                </template>
            </draggableComponent>
        </TabPanel>
    </TabPanels>
</template>

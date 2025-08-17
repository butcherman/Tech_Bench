<script setup lang="ts">
import draggableComponent from "vuedraggable";
import PageBody from "./PageBody.vue";
import PageTab from "./PageTab.vue";
import { Tab, Tabs, TabList, TabPanels } from "primevue";
import {
    workbookData,
    activePage,
    addBlankPage,
} from "@/Composables/Equipment/WorkbookEditor.module";
</script>

<template>
    <Tabs v-model:value="activePage">
        <TabList>
            <draggableComponent
                :list="workbookData.body"
                item-key="page"
                :group="{ name: 'page-list', pull: false }"
            >
                <template #item="{ element }">
                    <PageTab :tab-data="element" />
                </template>
            </draggableComponent>

            <Tab
                value="9999"
                pt:root:class="border! border-slate-300! rounded-t-lg p-1!"
                @click.stop="addBlankPage()"
            >
                <fa-icon icon="plus" v-tooltip="'New Page'" />
            </Tab>
        </TabList>
        <TabPanels
            class="h-full relative rounded-lg border border-dashed border-slate-400 hover:border-dotted group/body p-1"
        >
            <div
                class="absolute -top-5 right-4 rounded-t-md border-t border-s border-e border-dotted border-slate-400 py-0 px-1 text-xs text-muted hidden group-hover/body:block"
            >
                Body
            </div>
            <PageBody
                v-for="page in workbookData.body"
                :key="page.page"
                :page="page"
            />
        </TabPanels>
    </Tabs>
</template>

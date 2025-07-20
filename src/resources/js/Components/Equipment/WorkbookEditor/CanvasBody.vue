<script setup lang="ts">
import draggableComponent from "vuedraggable";
import {
    activePage,
    addBlankPage,
    deleteComponent,
    deletePage,
    editComponent,
    workbookData,
} from "@/Composables/Equipment/WorkbookEditor.module";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";
</script>

<template>
    <!-- <div
        class="relative border border-dashed border-slate-400 rounded-lg hover:border-dotted group/body p-1"
    >
        <div
            class="hidden text-xs absolute -top-4 right-4 border-t border-s border-e border-dashed border-slate-300 rounded-md px-1 text-muted group-hover/body:block"
        >
            Body
        </div>
        <draggableComponent
            :list="workbookData.body"
            :group="{ name: 'workbook', put: true }"
            class="h-full"
            item-key="index"
        >
            <template #item="{ element }">
                {{ element }}
            </template>
        </draggableComponent>
    </div> -->
    <Tabs v-bind:value="activePage">
        <TabList>
            <draggableComponent
                :list="workbookData.body"
                item-key="page"
                :group="{ name: 'page-list', pull: false }"
            >
                <template #item="{ element }">
                    <Tab
                        :value="element.page"
                        pt:root:class="border! border-slate-300! rounded-t-lg mx-1! p-1!"
                    >
                        <div class="text-xs">
                            <span
                                class="text-warning pointer me-1"
                                v-tooltip="'Edit Page Data'"
                                @click="editComponent(element)"
                            >
                                <fa-icon icon="pencil" />
                            </span>
                            <span
                                class="text-danger pointer"
                                v-tooltip="'Delete Page'"
                                @click="deletePage(element)"
                            >
                                <fa-icon icon="trash-alt" />
                            </span>
                        </div>
                        <div>
                            {{ element.title }}
                        </div>
                    </Tab>
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
    </Tabs>
</template>

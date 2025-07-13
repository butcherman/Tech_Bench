<script setup lang="ts">
import draggableComponent from "vuedraggable";
import ElementWrapper from "./ElementWrapper.vue";
import GridWrapper from "./GridWrapper.vue";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";
import {
    addBlankPage,
    workbookData,
    activePage,
    editPageData,
    deletePage,
    updatePreview,
} from "@/Composables/Equipment/WorkbookEditor";
</script>

<template>
    <Tabs v-bind:value="activePage">
        <TabList>
            <draggableComponent
                :list="workbookData.body"
                item-key="page"
                :group="{ name: 'page-list', pull: false }"
                @change="updatePreview()"
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
                                @click="editPageData(element)"
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
                @click.stop="addBlankPage"
            >
                <fa-icon icon="plus" v-tooltip="'New Page'" />
            </Tab>
        </TabList>
        <TabPanels class="h-full border border-slate-300 rounded-sm">
            <template v-for="page in workbookData.body">
                <TabPanel :value="page.page" class="h-full relative">
                    <div
                        v-if="!page.container.length"
                        class="absolute top-5 w-full"
                    >
                        <h4 class="text-center text-muted opacity-50">
                            Drag Element Here to Start Building Workbook
                        </h4>
                    </div>
                    <draggableComponent
                        :list="page.container"
                        :group="{ name: 'workbook', put: true }"
                        item-key="index"
                        class="py-5 h-full"
                        @change="updatePreview()"
                    >
                        <template #item="{ element }">
                            <div>
                                <GridWrapper
                                    v-if="element.type === 'grid-wrapper'"
                                    :grid-row="element"
                                    :container="page.container"
                                />
                                <ElementWrapper
                                    v-else
                                    :component="element"
                                    :container="page.container"
                                />
                            </div>
                        </template>
                    </draggableComponent>
                </TabPanel>
            </template>
        </TabPanels>
    </Tabs>
</template>

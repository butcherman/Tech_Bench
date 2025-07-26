<script setup lang="ts">
import ComponentListContainer from "./ComponentListContainer.vue";
import draggableComponent from "vuedraggable";
import EmptyContainer from "./EmptyContainer.vue";
import {
    activePage,
    addBlankPage,
    deletePage,
    editComponent,
    workbookData,
} from "@/Composables/Equipment/WorkbookEditor.module";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";
</script>

<template>
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
                        pt:root:class="border! border-slate-300! rounded-t-lg mx-1! p-0!"
                    >
                        <div
                            class="p-1"
                            :class="{ 'bg-red-100': !element.canPublish }"
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
        <TabPanels class="h-full border border-slate-300 rounded-sm p-1!">
            <TabPanel
                v-for="page in workbookData.body"
                :key="page.page"
                :value="page.page"
                class="h-full relative"
            >
                <EmptyContainer :isEmpty="page.container.length === 0" />
                <ComponentListContainer :component-list="page.container" />
            </TabPanel>
        </TabPanels>
    </Tabs>
</template>

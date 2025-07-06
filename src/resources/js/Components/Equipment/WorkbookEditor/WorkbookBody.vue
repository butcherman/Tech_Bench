<script setup lang="ts">
import ElementWrapper from "./ElementWrapper.vue";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";
import {
    addBlankPage,
    workbookData,
    activePage,
} from "@/Composables/Equipment/WorkbookEditor";
import WorkbookBodyNested from "./WorkbookBodyNested.vue";
import draggableComponent from "vuedraggable";
</script>

<template>
    <Tabs v-bind:value="activePage">
        <TabList>
            <template v-for="page in workbookData.body">
                <Tab
                    :value="page.page"
                    pt:root:class="border! border-slate-300! rounded-t-lg mx-1! p-1!"
                >
                    {{ page.title }}
                </Tab>
            </template>
            <Tab
                value="9999"
                pt:root:class="border! border-slate-300! rounded-t-lg p-1!"
                @click.stop="addBlankPage"
            >
                <fa-icon icon="plus" v-tooltip="'New Page'" />
            </Tab>
        </TabList>
        <TabPanels>
            <template v-for="page in workbookData.body">
                <TabPanel :value="page.page">
                    <draggableComponent
                        :list="page.container"
                        :group="{ name: 'workbook', put: true }"
                        item-key="index"
                    >
                        <template #item="{ element }">
                            <div>
                                <ElementWrapper :component="element" />
                            </div>
                        </template>
                    </draggableComponent>
                </TabPanel>
            </template>
        </TabPanels>
    </Tabs>
</template>

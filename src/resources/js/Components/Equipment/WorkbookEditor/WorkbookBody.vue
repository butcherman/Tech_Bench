<script setup lang="ts">
import draggableComponent from "vuedraggable";
import ElementWrapper from "./ElementWrapper.vue";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";
import {
    addBlankPage,
    workbookData,
    activePage,
} from "@/Composables/Equipment/WorkbookEditor";
</script>

<template>
    <Tabs v-bind:value="activePage" class="grow">
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
        <TabPanels class="h-full border border-slate-300 rounded-sm">
            <template v-for="page in workbookData.body">
                <TabPanel :value="page.page" class="h-full">
                    <draggableComponent
                        :list="page.container"
                        :group="{ name: 'workbook', put: true }"
                        item-key="index"
                        class="py-5 h-full"
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

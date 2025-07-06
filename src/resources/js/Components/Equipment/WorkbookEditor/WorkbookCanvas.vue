<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import ElementWrapper from "./ElementWrapper.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";
import {
    addBlankPage,
    workbookData,
    activePage,
} from "@/Composables/Equipment/WorkbookEditor";
import { computed, ref } from "vue";
</script>

<template>
    <Card class="h-full" title="Workbook Canvas">
        <WorkbookHeader />
        <div>
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
                            <div>
                                {{ page }}
                                <!-- <form>
                                    <ElementWrapper
                                    :component-list="page.container"
                                    />
                                </form> -->
                            </div>
                        </TabPanel>
                    </template>
                </TabPanels>
            </Tabs>
        </div>
    </Card>
</template>

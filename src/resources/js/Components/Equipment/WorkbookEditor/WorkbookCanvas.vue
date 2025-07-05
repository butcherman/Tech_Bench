<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import ElementWrapper from "./ElementWrapper.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";
import { workbookData } from "@/Composables/Equipment/WorkbookEditor";
</script>

<template>
    <Card class="h-full" title="Workbook Canvas">
        <WorkbookHeader />
        <div>
            <Tabs value="0">
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
                    >
                        <fa-icon icon="plus" v-tooltip="'New Page'" />
                    </Tab>
                </TabList>
                <TabPanels>
                    <template v-for="page in workbookData.body">
                        <TabPanel :value="page.page">
                            <form>
                                <ElementWrapper
                                    :component-list="page.container"
                                />
                            </form>
                        </TabPanel>
                    </template>
                </TabPanels>
            </Tabs>
        </div>
    </Card>
</template>

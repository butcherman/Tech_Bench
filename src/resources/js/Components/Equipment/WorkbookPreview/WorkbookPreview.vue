<script setup lang="ts">
import { ref } from "vue";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";
import GridWrapper from "./GridWrapper.vue";
import ElementWrapper from "./ElementWrapper.vue";

const props = defineProps<{
    workbookData: workbookWrapper;
}>();

const activePage = ref(props.workbookData.body[0].page);
</script>

<template>
    <div class="grow flex flex-col">
        <div>
            <div v-for="component in workbookData.header">
                <component
                    :is="component.tag"
                    :class="component.class"
                    v-html="component.text"
                />
            </div>
        </div>
        <Tabs v-bind:value="activePage" class="grow">
            <TabList>
                <template v-for="page in workbookData.body" :key="page.page">
                    <Tab
                        :value="page.page"
                        pt:root:class="border! border-slate-300! rounded-t-lg mx-1! p-1!"
                        :class="{ 'bg-red-200!': !page.canPublish }"
                    >
                        {{ page.title }}
                    </Tab>
                </template>
            </TabList>
            <TabPanels class="border border-slate-300 rounded-b-md grow">
                <template v-for="page in workbookData.body" :key="page.page">
                    <TabPanel :value="page.page" class="h-full">
                        <div v-if="!page.container.length">
                            <h5 class="text-center text-muted opacity-50">
                                No Data on this page
                            </h5>
                        </div>
                        <div v-for="element in page.container">
                            <GridWrapper
                                v-if="element.type === 'grid-wrapper'"
                                :grid-row="element"
                            />
                            <ElementWrapper v-else :component="element" />
                        </div>
                    </TabPanel>
                </template>
            </TabPanels>
        </Tabs>
        <div>
            <div>
                <div v-for="component in workbookData.footer">
                    <component
                        :is="component.tag"
                        :class="component.class"
                        v-html="component.text"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

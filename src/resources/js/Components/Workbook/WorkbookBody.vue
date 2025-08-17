<script setup lang="ts">
import ElementContainer from "./Elements/ElementContainer.vue";
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";

defineProps<{
    workbookData: workbookWrapper;
    activePage: string;
}>();
</script>

<template>
    <Tabs v-bind:value="activePage" class="h-full">
        <TabList>
            <Tab
                v-for="page in workbookData.body"
                :key="page.page"
                :value="page.page"
                pt:root:class="border! border-slate-300! rounded-t-lg mx-1! p-0!"
            >
                <div class="p-1" :class="{ 'bg-red-100': !page.canPublish }">
                    {{ page.title }}
                </div>
            </Tab>
        </TabList>
        <TabPanels class="border border-slate-300 rounded-sm p-1! h-full">
            <TabPanel
                v-for="page in workbookData.body"
                :key="page.page"
                :value="page.page"
                class="h-full relative"
            >
                <ElementContainer
                    :element-list="page.container"
                    class="h-full"
                    v-focustrap
                />
            </TabPanel>
        </TabPanels>
    </Tabs>
</template>

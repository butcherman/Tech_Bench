<script setup lang="ts">
import { Tab, Tabs, TabList, TabPanel, TabPanels } from "primevue";
import { onMounted, ref } from "vue";

const props = defineProps<{
    workbookData: workbookWrapper;
    activePage: string;
    isPreview?: boolean;
}>();
</script>

<template>
    <Tabs v-bind:value="activePage">
        <TabList>
            <template v-for="page in workbookData.body" :key="page.page">
                <Tab
                    :value="page.page"
                    pt:root:class="border! border-slate-300! rounded-t-lg mx-1! p-0!"
                >
                    <div
                        class="p-1"
                        :class="{ 'bg-red-100': !page.canPublish }"
                    >
                        {{ page.title }}
                    </div>
                </Tab>
            </template>
        </TabList>
    </Tabs>
</template>

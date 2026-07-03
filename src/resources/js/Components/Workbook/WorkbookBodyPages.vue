<script setup lang="ts">
import NodeWrapper from "./WorkbookNodes/NodeWrapper.vue";
import { TabPanels, TabPanel } from "primevue";

const props = defineProps<{
    bodySkeleton: workbookPage[];
}>();
</script>

<template>
    <TabPanels class="border border-slate-300 rounded-sm p-1! h-full">
        <TabPanel
            v-for="page in bodySkeleton"
            :key="page.page"
            :value="page.page"
            class="h-full"
        >
            <template v-for="element in page.contents">
                <NodeWrapper :node="element" />
            </template>
        </TabPanel>
    </TabPanels>
</template>

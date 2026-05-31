<script setup lang="ts">
import draggableComponent from "vuedraggable";
import WorkbookPageTabs from "./WorkbookPageTabs.vue";
import { Tab, Tabs, TabList, TabPanels } from "primevue";
import {
    activePage,
    workbookData,
    addBlankPage,
} from "@/Composables/Workbook/Canvas/WorkbookEditor.module";
</script>

<template>
    <Tabs v-model:value="activePage">
        <WorkbookPageTabs />
    </Tabs>
</template>

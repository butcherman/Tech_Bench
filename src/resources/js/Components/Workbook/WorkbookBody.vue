<script setup lang="ts">
import WorkbookBodyPages from "./WorkbookBodyPages.vue";
import WorkbookBodyTabs from "./WorkbookBodyTabs.vue";
import { activePage } from "@/Composables/Workbook/CustomerWorkbook.module.js";
import { Tabs } from "primevue";

const props = defineProps<{
    bodySkeleton: workbookPage[];
}>();
</script>

<template>
    <Tabs v-model:value="activePage" class="h-full">
        <WorkbookBodyTabs :body-skeleton="bodySkeleton" />
        <WorkbookBodyPages :body-skeleton="bodySkeleton" />
    </Tabs>
</template>

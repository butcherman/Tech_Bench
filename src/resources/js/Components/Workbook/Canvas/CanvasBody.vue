<script setup lang="ts">
import WorkbookPageTabs from "./WorkbookPageTabs.vue";
import { activePage } from "@/Composables/Workbook/Canvas/WorkbookEditor.module";
import { Tabs } from "primevue";
</script>

<template>
    <Tabs v-model:value="activePage">
        <WorkbookPageTabs />
    </Tabs>
</template>

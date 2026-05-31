<script setup lang="ts">
import CanvasBodyPages from "./CanvasBodyPages.vue";
import CanvasBodyPageTabs from "./CanvasBodyPageTabs.vue";
import { activePage } from "@/Composables/Workbook/Canvas/WorkbookEditor.module";
import { Tabs } from "primevue";
</script>

<template>
    <Tabs v-model:value="activePage">
        <CanvasBodyPageTabs />
        <CanvasBodyPages />
    </Tabs>
</template>

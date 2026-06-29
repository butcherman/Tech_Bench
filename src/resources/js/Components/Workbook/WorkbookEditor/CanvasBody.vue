<script setup lang="ts">
import CanvasBodyPages from "./CanvasBodyPages.vue";
import CanvasBodyTabs from "./CanvasBodyTabs.vue";
import { activePage } from "@/Composables/Workbook/CustomerWorkbook.module.js";
import { Tabs } from "primevue";
</script>

<template>
    <Tabs v-model:value="activePage">
        <CanvasBodyTabs />
        <CanvasBodyPages />
    </Tabs>
</template>

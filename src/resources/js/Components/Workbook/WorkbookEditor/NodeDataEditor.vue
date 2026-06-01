<script setup lang="ts">
import { activeNode } from "@/Composables/Workbook/Canvas/WorkbookEditor.module";
import { Drawer } from "primevue";
import { computed } from "vue";

const showEditor = computed(() => (activeNode.value ? true : false));
</script>

<template>
    <Drawer v-model:visible="showEditor" class="!w-3/4" position="right">
        <h1>Hello World</h1>
    </Drawer>
</template>

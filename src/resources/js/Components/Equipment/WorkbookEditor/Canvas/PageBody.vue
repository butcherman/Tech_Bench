<script setup lang="ts">
import { TabPanel } from "primevue";
import EmptyContainer from "../Elements/EmptyContainer.vue";
import ListContainer from "../Elements/ListContainer.vue";

defineProps<{
    page: workbookPage;
}>();
</script>

<template>
    <TabPanel :key="page.page" :value="page.page" class="h-full relative">
        <EmptyContainer :is-empty="page.container.length === 0" />
        <ListContainer :container-list="page.container" />
    </TabPanel>
</template>

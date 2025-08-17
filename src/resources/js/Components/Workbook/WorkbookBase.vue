<script setup lang="ts">
import Card from "../_Base/Card.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import {
    saveClass,
    saveIcon,
} from "@/Composables/Customer/CustomerWorkbook.module";

defineProps<{
    workbookData: workbookWrapper;
    activePage: string;
}>();
</script>

<template>
    <Card>
        <form novalidate @submit.prevent class="h-full flex flex-col">
            <WorkbookHeader :data="workbookData.header" />
            <WorkbookBody
                class="grow"
                :workbook-data="workbookData"
                :active-page="activePage"
            />
            <WorkbookHeader :data="workbookData.footer" />
        </form>
        <template #footer>
            <div class="flex flex-row-reverse">
                <span><fa-icon :icon="saveIcon" :class="saveClass" /></span>
            </div>
        </template>
    </Card>
</template>

<script setup lang="ts">
import Card from "../_Base/Card.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";

defineProps<{
    workbookData: workbookWrapper;
    activePage: string;
    isPreview?: boolean;
    values: { [index: string]: string };
}>();
</script>

<template>
    <Card>
        <div class="h-full flex flex-col">
            <WorkbookHeader :data="workbookData.header" />
            <WorkbookBody
                class="grow"
                :workbook-data="workbookData"
                :active-page="activePage"
                :values="values"
                is-preview
            />
            <WorkbookHeader :data="workbookData.footer" />
        </div>
    </Card>
</template>

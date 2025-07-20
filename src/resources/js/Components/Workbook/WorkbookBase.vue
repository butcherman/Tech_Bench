<script setup lang="ts">
import Card from "../_Base/Card.vue";
import WorkbookHeader from "./WorkbookHeader.vue";

defineProps<{
    workbookData: workbookWrapper;
    isPreview?: boolean;
}>();
</script>

<template>
    <Card>
        <div class="h-full flex flex-col">
            <WorkbookHeader :data="workbookData.header" />
            <div class="grow" style="border: 1px solid red">workbook body</div>
            <WorkbookHeader :data="workbookData.footer" />
        </div>
    </Card>
</template>

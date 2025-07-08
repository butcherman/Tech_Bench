<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
import WorkbookFooter from "./WorkbookFooter.vue";
</script>

<template>
    <Card class="h-full" title="Workbook Canvas">
        <div class="flex flex-col h-full gap-2">
            <WorkbookHeader />
            <WorkbookBody class="grow" />
            <WorkbookFooter />
        </div>
    </Card>
</template>

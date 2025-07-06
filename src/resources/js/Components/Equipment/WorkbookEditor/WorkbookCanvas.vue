<script setup lang="ts">
import Card from "@/Components/_Base/Card.vue";
import WorkbookBody from "./WorkbookBody.vue";
import WorkbookHeader from "./WorkbookHeader.vue";
</script>

<template>
    <Card class="h-full" title="Workbook Canvas">
        <div class="flex flex-col h-full">
            <WorkbookHeader />
            <WorkbookBody />
        </div>
    </Card>
</template>

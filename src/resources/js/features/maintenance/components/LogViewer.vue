<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import LogViewerDetails from "./LogViewerDetails.vue";

const props = defineProps<{
    logData?: LogEntry[];
}>();
</script>

<template>
    <Card>
        <div class="flex flex-col gap-2">
            <div>search filters</div>
            <div>stats</div>
            <div v-if="logData"><LogViewerDetails :log-data /></div>
        </div>
    </Card>
</template>

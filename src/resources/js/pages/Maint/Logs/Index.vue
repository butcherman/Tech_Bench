<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import LogViewer from "@/features/maintenance/components/LogViewer.vue";

const props = defineProps<{
    logFile: string;
    loggingLevel: LogLevel;
    logData?: LogData;
    logStats?: LogStats;
}>();
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>
<template>
    <div class="flex justify-center">
        <LogViewer v-bind="props" />
    </div>
</template>

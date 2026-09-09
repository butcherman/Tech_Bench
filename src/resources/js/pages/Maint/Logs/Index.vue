<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import LogViewer from "@/features/maintenance/components/LogViewer.vue";

const props = defineProps<{
    logData?: LogData;
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

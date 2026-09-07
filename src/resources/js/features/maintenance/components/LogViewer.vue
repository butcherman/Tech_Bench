<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import LogViewerDetails from "./LogViewerDetails.vue";
import { computed } from "vue";

const props = defineProps<{
    logData?: LogEntry[];
}>();

const thisLog = computed(() => props.logData ?? []);
</script>

<template>
    <Card>
        <div class="flex flex-col gap-2">
            <div>search filters</div>
            <div>stats</div>
            <div>
                <LogViewerDetails
                    :log-data="thisLog"
                    :loaded="logData !== undefined"
                />
            </div>
        </div>
    </Card>
</template>

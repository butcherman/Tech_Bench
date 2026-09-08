<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import LogViewerEntries from "./LogViewerEntries.vue";
import { computed } from "vue";

const props = defineProps<{
    logData?: LogData;
}>();

const thisLog = computed(() => props.logData?.data ?? []);
</script>

<template>
    <Card>
        <div class="flex flex-col gap-2">
            <!-- <div>search filters</div>
            <div>stats</div> -->
            <div>
                <LogViewerEntries
                    :log-data="thisLog"
                    :loaded="logData !== undefined"
                />
            </div>
        </div>
    </Card>
</template>

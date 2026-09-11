<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import LogViewerEntries from "./LogViewerEntries.vue";
import LogViewerFilters from "./LogViewerFilters.vue";
import LogViewerStatistics from "./LogViewerStatistics.vue";
import { dataGet } from "@/core/utilities/axiosWrapper.js";
import { load } from "@/wayfinder/routes/maint/logs/index.js";
import { ref, watch } from "vue";

const props = defineProps<{
    logFile: string;
    loggingLevel: LogLevel;
    logList: string[];
    logData?: LogData;
    logStats?: LogStats;
}>();

const thisLog = ref<LogEntry[]>([]);
const hasMore = ref<boolean>(false);
const curPage = ref<number>(1);
const isLoading = ref<boolean>(false);

/**
 * Get the next set of log entries.
 */
const loadMore = async (): Promise<void> => {
    isLoading.value = true;

    const newEntries = await dataGet<LogData>(
        `${load.url(props.logFile)}?page=${curPage.value + 1}&snapshot=${props.logData?.meta.snapshot}`,
    );

    if (newEntries) {
        thisLog.value = [...thisLog.value, ...newEntries.data];
        hasMore.value = newEntries.meta.has_more;
        curPage.value = newEntries.meta.current_page;
    }

    isLoading.value = false;
};

watch(
    () => props.logData,
    (newData) => {
        if (newData) {
            thisLog.value = [...newData.data];
            hasMore.value = newData.meta.has_more;
            curPage.value = newData.meta.current_page;
        }
    },
);
</script>

<template>
    <Card>
        <div class="flex flex-col gap-2">
            <LogViewerFilters
                v-if="logStats"
                :log-file
                :log-stats
                :log-list
                :loaded="logStats !== undefined"
            />
            <LogViewerStatistics
                :log-stats
                :loaded="logStats !== undefined"
                :logging-level
            />
            <LogViewerEntries
                :log-data="thisLog"
                :has-more
                :is-loading
                @load-more="loadMore"
            />
        </div>
    </Card>
</template>

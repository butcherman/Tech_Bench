<script setup lang="ts">
import Card from "@/core/components/Card.vue";
import LogViewerEntries from "./LogViewerEntries.vue";
import { ref, watch } from "vue";
import { dataGet } from "@/core/utilities/axiosWrapper.js";
import { load } from "@/wayfinder/routes/maint/logs/index.js";

const props = defineProps<{
    logFile: string;
    logData?: LogData;
}>();

const thisLog = ref<LogEntry[]>([]);
const hasMore = ref<boolean>(false);
const curPage = ref<number>(1);
const isLoading = ref(false);

/**
 * Get the next set of log entries.
 */
const loadMore = async () => {
    isLoading.value = true;

    const newEntries = await dataGet<LogData>(
        `${load.url(props.logFile)}?page=${curPage.value + 1}`,
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
            <!-- <div>search filters</div>
            <div>stats</div> -->
            <div>
                <LogViewerEntries
                    :log-data="thisLog"
                    :loaded="logData !== undefined"
                    :has-more
                    :is-loading
                    @load-more="loadMore"
                />
            </div>
        </div>
    </Card>
</template>

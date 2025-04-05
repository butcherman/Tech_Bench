<script setup lang="ts">
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import { Deferred } from "@inertiajs/vue3";
import AtomLoader from "@/Components/_Base/Loaders/AtomLoader.vue";
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import { ref, useTemplateRef } from "vue";
import Modal from "@/Components/_Base/Modal.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";

interface logEntry {
    time: string;
    env: string;
    level: string;
    message: string;
    data: addData;
    context: addData;
    stack_trace: string;
}

interface addData {
    [key: string]: string;
}

defineProps<{
    channel: string;
    logFile: string;
    logData?: logEntry[];
}>();

/*
|-------------------------------------------------------------------------------
| Additional Data for Modals
|-------------------------------------------------------------------------------
*/
const additionalDataModal = useTemplateRef("additional-data-modal");
const additionalData = ref<addData | null>(null);
const showAdditionalData = (dataEntry: addData): void => {
    additionalData.value = dataEntry;
    additionalDataModal.value?.show();
};

const stackTraceModal = useTemplateRef("stack-trace-modal");
const stackTrace = ref<string | null>(null);
const showStackTrace = (traceEntry: string): void => {
    stackTrace.value = traceEntry;
    stackTraceModal.value?.show();
};

/*
|-------------------------------------------------------------------------------
| Table Structure
|-------------------------------------------------------------------------------
*/
const columns = [
    {
        label: "Time",
        field: "time",
    },
    {
        label: "Level",
        field: "level",
        filterable: true,
        filterSelect: true,
    },
    {
        label: "Message",
        field: "message",
    },
    {
        field: "actions",
    },
];
</script>

<script lang="ts">
export default { layout: AppLayout };
</script>

<template>
    <div>
        <Card :title="logFile">
            <template #append-title>
                <a
                    :href="$route('maint.logs.download', [channel, logFile])"
                    v-tooltip.left="'Download Log File'"
                    @click.stop
                >
                    <BaseBadge class="mx-1" icon="download" />
                </a>
            </template>
            <Deferred data="log-data">
                <template #fallback>
                    <div class="flex justify-center">
                        <AtomLoader />
                    </div>
                    <h4 class="text-center">Loading Log File...</h4>
                </template>
                <DataTable v-if="logData" :rows="logData" :columns="columns">
                    <template #row.actions="{ rowData }">
                        <div class="flex gap-1">
                            <BaseBadge
                                v-if="rowData.stack_trace"
                                icon="bug"
                                variant="error"
                                v-tooltip.left="'Stack Trace'"
                                @click="showStackTrace(rowData.stack_trace)"
                            />
                            <BaseBadge
                                v-if="rowData.data"
                                icon="eye"
                                v-tooltip.left="'Additional Data'"
                                @click="showAdditionalData(rowData.data)"
                            />
                        </div>
                    </template>
                </DataTable>
            </Deferred>
        </Card>
        <Modal
            ref="additional-data-modal"
            title="Additional Data"
            @hidden="additionalData = null"
        >
            <TableStacked :items="additionalData" />
        </Modal>
        <Modal ref="stack-trace-modal" @hidden="stackTrace = null">
            <template #header>
                Stack Trace
                <span class="float-end me-10">
                    <ClipboardCopy :value="stackTrace" />
                </span>
            </template>
            <pre>{{ stackTrace }}</pre>
        </Modal>
    </div>
</template>

<style lang="postcss">
th {
    @apply text-start;
}
</style>

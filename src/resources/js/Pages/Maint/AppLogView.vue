<template>
    <div>
        <Card :title="logFile">
            <DataTable :rows="logData" :columns="columns">
                <template #row.actions="{ rowData }">
                    <BaseBadge
                        v-if="rowData.stack_trace"
                        icon="bug"
                        variant="error"
                        v-tooltip.left="'Stack Trace'"
                        @click="openStackTrace(rowData.stack_trace)"
                    />
                    <BaseBadge
                        v-if="rowData.data"
                        icon="eye"
                        v-tooltip.left="'Additional Data'"
                        @click="openAdditionalData(rowData.data)"
                    />
                </template>
            </DataTable>
        </Card>
        <Modal ref="stack-trace-modal" @hidden="stackTrace = null">
            <template #header>
                Stack Trace
                <span class="float-end me-10">
                    <ClipboardCopy :value="stackTrace" />
                </span>
            </template>
            <pre>{{ stackTrace }}</pre>
        </Modal>
        <Modal
            ref="additional-data-modal"
            title="Additional Data"
            @hidden="additionalData = null"
        >
            <TableStacked class="w-full" :items="additionalData" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import DataTable from "@/Components/_Base/DataTable/DataTable.vue";
import AppLayout from "@/Layouts/App/AppLayout.vue";
import Card from "@/Components/_Base/Card.vue";
import BaseBadge from "@/Components/_Base/Badges/BaseBadge.vue";
import Modal from "@/Components/_Base/Modal.vue";
import ClipboardCopy from "@/Components/_Base/ClipboardCopy.vue";
import TableStacked from "@/Components/_Base/TableStacked.vue";
import { ref, useTemplateRef } from "vue";

defineProps<{
    logFile: string;
    logData: any[];
}>();

/*
|-------------------------------------------------------------------------------
| Additional Data
|-------------------------------------------------------------------------------
*/
const additionalDataModal = useTemplateRef("additional-data-modal");
const additionalData = ref<[] | null>(null);
const openAdditionalData = (data: []): void => {
    additionalData.value = data;
    additionalDataModal.value?.show();
};

/*
|-------------------------------------------------------------------------------
| Stack Trace
|-------------------------------------------------------------------------------
*/
const stackTraceModal = useTemplateRef("stack-trace-modal");
const stackTrace = ref<string | null>(null);
const openStackTrace = (trace: string): void => {
    stackTrace.value = trace;
    stackTraceModal.value?.show();
};

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

<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import DataTable from "@/core/features/dataResources/DataTable.vue";
import Drawer from "@/core/components/Drawer.vue";
import LogViewerEntryDetails from "./LogViewerEntryDetails.vue";
import { useColumnBuilder } from "@/core/features/dataResources/composables/columnBuilder";
import { useLogEntryHelper } from "../composables/logEntryHelper";
import { ref } from "vue";

const props = defineProps<{
    logData: LogEntry[];
    loaded: boolean;
}>();

const colHelper = useColumnBuilder<LogEntry>();
const { getBadgeClass, getBadgeIcon } = useLogEntryHelper();

const activeEntry = ref();
const showEntry = ref(false);

const dataColumns = [
    colHelper.text("timestamp", "Date / Time", {
        filterable: false,
        sort: false,
        width: 145,
        formatter: (value: string) => {
            let date = new Date(value);
            return date.toLocaleTimeString();
        },
    }),
    colHelper.text("level", "Level", {
        filterable: false,
        sort: false,
        width: 80,
    }),
    colHelper.text("user", "User", {
        filterable: false,
        sort: false,
        width: 200,
    }),
    colHelper.text("data.body", "Message", {
        filterable: false,
        sort: false,
    }),
];

const onRowClick = (event: MouseEvent, rowData: LogEntry) => {
    console.log(rowData);

    activeEntry.value = rowData;
    showEntry.value = true;
};
</script>

<template>
    <div>
        <DataTable
            :columns="dataColumns"
            :data="logData"
            :row-click-fn="onRowClick"
            :loading="!loaded"
            compact
        >
            <template #row.level="{ rowData }">
                <BaseBadge
                    class="uppercase w-full"
                    variant="none"
                    :class="getBadgeClass(rowData.level)"
                    :icon="getBadgeIcon(rowData.level)"
                    :text="rowData.level"
                />
            </template>
        </DataTable>
        <Drawer v-model="showEntry" position="right" title="Log Details">
            <LogViewerEntryDetails v-if="activeEntry" :activeEntry />
        </Drawer>
    </div>
</template>

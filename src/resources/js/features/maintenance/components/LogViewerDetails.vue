<script setup lang="ts">
import BaseBadge from "@/core/components/badges/BaseBadge.vue";
import DataTable from "@/core/features/dataResources/DataTable.vue";
import { useColumnBuilder } from "@/core/features/dataResources/composables/columnBuilder";
import { useLogEntryHelper } from "../composables/logEntryHelper";

const props = defineProps<{
    logData: LogEntry[];
}>();

const colHelper = useColumnBuilder<LogEntry>();
const { getBadgeClass, getBadgeIcon } = useLogEntryHelper();

const dataColumns = [
    colHelper.text("time", "Date / Time", {
        filterable: false,
        sort: false,
    }),
    colHelper.text("level", "Level", {
        filterable: false,
        sort: false,
    }),
    // colHelper.text("context.user", "User", {
    //     filterable: false,
    //     sort: false,
    // }),
    colHelper.text("message", "Message", {
        filterable: false,
        sort: false,
    }),
];

const onRowClick = (event: MouseEvent, rowData: LogEntry) => {
    console.log(rowData);
};
</script>

<template>
    <DataTable
        :columns="dataColumns"
        :data="logData"
        :row-click-fn="onRowClick"
        compact
    >
        <template #row.level="{ rowData }">
            <BaseBadge
                class="uppercase"
                variant="none"
                :class="getBadgeClass(rowData.level)"
                :icon="getBadgeIcon(rowData.level)"
                :text="rowData.level"
            />
        </template>
    </DataTable>
</template>

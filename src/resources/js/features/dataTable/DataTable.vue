<script setup lang="ts" generic="TRow extends RowData">
import DataTableHeader from "./components/DataTableHeader.vue";
import { useDataTable } from "./composables/dataTable.js";
import type { DataTableColumn } from "./types.js";
import type { RowData } from "@tanstack/vue-table";

defineSlots<{
    [key: string]: any;
}>();

const emit = defineEmits<{
    "row-click": TRow;
}>();

const props = defineProps<{
    columns: DataTableColumn<TRow>[];
    data: TRow[];
    compact?: boolean;
    striped?: boolean;
    gridLines?: boolean;
    allowRowClick?: boolean;
    noResultsText?: string;
    paginate?: boolean;
    syncLoadingState?: boolean;
    rowBgFn?: (row: TRow) => string | false;
    rowClickLink?: (row: TRow) => string;
}>();

const table = useDataTable(props);
</script>

<template>
    <div class="overflow-x-auto w-full">
        <table class="table-auto w-full">
            <DataTableHeader :table="table" />
        </table>
    </div>
</template>

<script setup lang="ts" generic="TRow extends RowData">
import DataTableBody from "./components/DataTableBody.vue";
import DataTableFooter from "./components/DataTableFooter.vue";
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
    columns: DataTableColumn<TRow, any>[];
    data: TRow[];
    // Optional
    actionsSlot?: boolean;
    compact?: boolean;
    striped?: boolean;
    gridLines?: boolean;
    noResultsText?: string;
    paginate?: boolean;

    rowClassFn?: (row: TRow) => string | false;

    //
    // allowRowClick?: boolean;
    // syncLoadingState?: boolean;
    rowClickLink?: (row: TRow) => string;
}>();

const table = useDataTable(props);
</script>

<template>
    <div class="overflow-x-auto w-full">
        <table class="table-auto w-full">
            <DataTableHeader :table="table">
                <template
                    v-for="name of Object.keys($slots)"
                    v-slot:[name]="data"
                >
                    <slot :name="name" v-bind="data" />
                </template>
            </DataTableHeader>
            <DataTableBody :table="table" :no-results-text="noResultsText">
                <template
                    v-for="name of Object.keys($slots)"
                    v-slot:[name]="data"
                >
                    <slot :name="name" v-bind="data" />
                </template>
            </DataTableBody>
            <DataTableFooter :table="table">
                <template
                    v-for="name of Object.keys($slots)"
                    v-slot:[name]="data"
                >
                    <slot :name="name" v-bind="data" />
                </template>
            </DataTableFooter>
        </table>
    </div>
</template>

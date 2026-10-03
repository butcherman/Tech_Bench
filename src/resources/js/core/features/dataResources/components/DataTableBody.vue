<script setup lang="ts" generic="TRow extends RowData">
import DataTableBodyData from "./DataTableBodyData.vue";
import DataTableBodyEmpty from "./DataTableBodyEmpty.vue";
import DataTableBodyLoading from "./DataTableBodyLoading.vue";
import { computed } from "vue";
import type { RowData, Table } from "@tanstack/vue-table";

defineSlots<{
    [key: string]: any;
}>();

const emit = defineEmits<{
    "row-click": [TRow];
}>();

const props = defineProps<{
    table: Table<TRow>;
    noResultsText?: string;
    isLoading?: boolean;
}>();

const rows = computed(() => props.table.getRowModel().rows);
</script>

<template>
    <DataTableBodyLoading v-if="isLoading" :table="table" />
    <DataTableBodyEmpty
        v-else-if="!rows.length"
        :table="table"
        :no-results-text="noResultsText"
    >
        <template v-for="(_, slot) of $slots" #[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </DataTableBodyEmpty>
    <DataTableBodyData v-else :table="table">
        <template v-for="(_, slot) of $slots" #[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </DataTableBodyData>
</template>

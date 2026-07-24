<script setup lang="ts" generic="TData extends RowData">
import DataTableBodyData from "./DataTableBodyData.vue";
import DataTableBodyEmpty from "./DataTableBodyEmpty.vue";
import DataTableBodyLoading from "./DataTableBodyLoading.vue";
import { computed, ref } from "vue";
import type { RowData, Table } from "@tanstack/vue-table";

defineSlots<{
    [key: string]: any;
}>();

const props = defineProps<{
    table: Table<TData>;
    noResultsText?: string;
}>();

const isLoading = ref(false);

const showComponent = computed(() => {
    if (isLoading.value) {
        return "loader";
    }

    if (!props.table.getRowModel().rows.length) {
        return "empty";
    }

    return "body";
});
</script>

<template>
    <DataTableBodyLoading v-if="showComponent === 'loader'" :table="table" />
    <DataTableBodyEmpty
        v-if="showComponent === 'empty'"
        :table="table"
        :no-results-text="noResultsText"
    />
    <DataTableBodyData v-if="showComponent === 'body'" :table="table">
        <template v-for="name of Object.keys($slots)" v-slot:[name]="data">
            <slot :name="name" v-bind="data" />
        </template>
    </DataTableBodyData>
</template>

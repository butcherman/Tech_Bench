<script setup lang="ts" generic="TRow extends RowData">
import DataTableBodyData from "./DataTableBodyData.vue";
import DataTableBodyEmpty from "./DataTableBodyEmpty.vue";
import DataTableBodyLoading from "./DataTableBodyLoading.vue";
import { computed, ref } from "vue";
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
    >
        <template v-for="(_, slot) of $slots" #[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </DataTableBodyEmpty>
    <DataTableBodyData v-if="showComponent === 'body'" :table="table">
        <template v-for="(_, slot) of $slots" #[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </DataTableBodyData>
</template>

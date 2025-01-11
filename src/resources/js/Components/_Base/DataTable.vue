<template>
    <div class="w-full h-full">
        <table class="w-full h-full">
            <thead>
                <tr
                    v-for="headerGroup in table.getHeaderGroups()"
                    :key="headerGroup.id"
                >
                    <th v-for="headerCell in headerGroup.headers">
                        {{ headerCell.column.columnDef.header }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in table.getRowModel().rows" :key="row.id">
                    <td v-for="cell in row.getAllCells()">
                        {{ cell.getValue() }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import {
    useVueTable,
    createColumnHelper,
    getCoreRowModel,
} from "@tanstack/vue-table";
import { computed, ref } from "vue";

const props = defineProps<{
    columns: any[];
    rows: any[];
}>();

const colHelper = createColumnHelper();

const tableColumns = computed(() => {
    let cols = [];

    props.columns.forEach((col) => {
        cols.push(
            colHelper.accessor(col.field, {
                cell: (info) => info.getValue(),
                header: col.label,
            })
        );
    });

    return cols;
});

const table = useVueTable({
    columns: tableColumns.value,
    data: props.rows,
    getCoreRowModel: getCoreRowModel(),
});
</script>

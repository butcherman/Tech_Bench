<template>
    <div class="overflow-x-auto w-full">
        <table class="table-auto w-full">
            <thead>
                <tr
                    v-for="headerGroup in table.getHeaderGroups()"
                    :key="headerGroup.id"
                    class="bg-slate-300"
                >
                    <th
                        v-for="headerCell in headerGroup.headers"
                        :key="headerCell.id"
                        class="p-3 border border-slate-400"
                    >
                        <FlexRender
                            :render="headerCell.column.columnDef.header"
                            :props="headerCell.getContext()"
                        />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(row, index) in table.getRowModel().rows"
                    :key="row.id"
                    :class="{ 'bg-slate-100': index % 2 === 1 }"
                >
                    <td
                        v-for="cell in row.getAllCells()"
                        :key="cell.id"
                        class="p-3 border border-slate-400"
                    >
                        <FlexRender
                            :render="cell.column.columnDef.cell"
                            :props="cell.getContext()"
                        />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts" generic="T">
import TableHeaderCell from "./TableHeaderCell.vue";
import { computed, h } from "vue";
import {
    useVueTable,
    createColumnHelper,
    getCoreRowModel,
    FlexRender,
} from "@tanstack/vue-table";
import type { AccessorFn, ColumnDef } from "@tanstack/vue-table";

interface tableColumnProp {
    label: string;
    field: string;
    icon?: string;
}

const props = defineProps<{
    columns: tableColumnProp[];
    rows: T[];
}>();

/*
|-------------------------------------------------------------------------------
| Table Header/Column Definitions
|-------------------------------------------------------------------------------
*/
const colHelper = createColumnHelper<T>();
const tableColumns = computed<ColumnDef<T, unknown>[]>(() => {
    let cols: ColumnDef<T, unknown>[] = [];

    props.columns.forEach((col: tableColumnProp) => {
        cols.push(
            colHelper.accessor(col.field as unknown as AccessorFn<T>, {
                id: col.field,
                cell: (info) => info.getValue(),
                header: (data) => h(TableHeaderCell, { data, column: col }),
            })
        );
    });

    return cols;
});

/*
|-------------------------------------------------------------------------------
| Table State
|-------------------------------------------------------------------------------
*/
const table = useVueTable({
    columns: tableColumns.value,
    data: props.rows,
    getCoreRowModel: getCoreRowModel(),
});
</script>

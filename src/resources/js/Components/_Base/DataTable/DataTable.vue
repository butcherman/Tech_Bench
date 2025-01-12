<template>
    <div class="w-full h-full">
        <table
            class="table-auto w-full border border-collapse border-slate-400"
        >
            <thead>
                <tr
                    v-for="headerGroup in table.getHeaderGroups()"
                    :key="headerGroup.id"
                    class="bg-slate-100"
                >
                    <th
                        v-for="headerCell in headerGroup.headers"
                        :key="headerCell.id"
                        class="border border-slate-300 p-2 text-start"
                    >
                        <FlexRender
                            :render="headerCell.column.columnDef.header"
                            :props="headerCell.getContext()"
                        />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in table.getRowModel().rows" :key="row.id">
                    <td
                        v-for="cell in row.getAllCells()"
                        :key="cell.id"
                        class="border border-slate-300 p-2"
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
import { AccessorFn, ColumnDef, FlexRender } from "@tanstack/vue-table";
import {
    useVueTable,
    createColumnHelper,
    getCoreRowModel,
} from "@tanstack/vue-table";
import { computed } from "vue";

interface tableColumnProp {
    label: string;
    field: string;
}

const props = defineProps<{
    columns: tableColumnProp[];
    rows: T[];
}>();

const colHelper = createColumnHelper<T>();

const tableColumns = computed<ColumnDef<T, unknown>[]>(() => {
    let cols: ColumnDef<T, unknown>[] = [];

    props.columns.forEach((col: tableColumnProp) => {
        cols.push(
            colHelper.accessor(col.field as unknown as AccessorFn<T>, {
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

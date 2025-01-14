<template>
    <div class="overflow-x-auto w-full">
        <table class="table-auto w-full">
            <thead class="border-b border-slate-300 border-collapse">
                <tr
                    v-for="headerGroup in table.getHeaderGroups()"
                    :key="headerGroup.id"
                    class="border-b border-slate-300"
                >
                    <th
                        v-for="headerCell in headerGroup.headers"
                        :key="headerCell.id"
                        :class="paddingClass"
                    >
                        <fa-icon
                            v-if="headerCell.column.getCanSort()"
                            :icon="
                                getSortingIcon(headerCell.column.getIsSorted())
                            "
                            class="float-end mt-1 pointer"
                            @click="headerCell.column.toggleSorting()"
                        />
                        <slot
                            :name="`header.${headerCell.id}`"
                            :cellData="headerCell.column.columnDef.meta"
                        >
                            <FlexRender
                                :render="headerCell.column.columnDef.header"
                                :props="headerCell.getContext()"
                            />
                        </slot>
                    </th>
                </tr>
                <tr v-if="showFilterRow">
                    <th
                        v-for="headerCell in table.getHeaderGroups()[0].headers"
                        class="p-1 font-normal"
                    >
                        <TableHeaderFilter
                            v-if="headerCell.column.getCanFilter()"
                            :column="headerCell.getContext().column"
                        />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="!table.getRowModel().rows.length">
                    <td :colspan="table.getAllColumns().length">
                        <slot name="no-results">
                            <h3 class="text-center text-muted">
                                {{ noResultsText ?? "No Results" }}
                            </h3>
                        </slot>
                    </td>
                </tr>
                <tr
                    v-for="(row, index) in table.getRowModel().rows"
                    :key="row.id"
                    class="border-b border-slate-200"
                    :class="[pointerClass, bgClass(row.original, index)]"
                    @click="onRowClick($event, row.original)"
                >
                    <td
                        v-for="cell in row.getAllCells()"
                        :key="cell.id"
                        :class="[paddingClass, borderClass]"
                    >
                        <slot
                            :name="`column.${cell.column.id}`"
                            :cellData="cell.getValue()"
                        >
                            <FlexRender
                                :render="cell.column.columnDef.cell"
                                :props="cell.getContext()"
                            />
                        </slot>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts" generic="T">
import TableHeaderCell from "./TableHeaderCell.vue";
import TableHeaderFilter from "./TableHeaderFilter.vue";
import { computed, h } from "vue";
import { handleLinkClick } from "@/Composables/links.module";
import {
    useVueTable,
    createColumnHelper,
    getCoreRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    FlexRender,
} from "@tanstack/vue-table";
import type { AccessorFn, ColumnDef } from "@tanstack/vue-table";

interface tableColumnProp {
    label: string;
    field: string;
    icon?: string;
    filterable?: boolean;
    filterPlaceholder?: string;
    filterSelect?: boolean;
    sort?: boolean;
}

const emit = defineEmits<{
    "row-click": [row: T];
}>();

const props = defineProps<{
    columns: tableColumnProp[];
    rows: T[];
    compact?: boolean;
    striped?: boolean;
    gridLines?: boolean;
    allowRowClick?: boolean;
    noResultsText?: string;
    rowBgFn?: (row: T) => string | false;
    rowClickLink?: (row: T) => string;
}>();

/*
|---------------------------------------------------------------------------
| Table Events
|---------------------------------------------------------------------------
*/
const onRowClick = (event: MouseEvent, row: T) => {
    if (pointerClass.value === "pointer") {
        emit("row-click", row);

        if (props.rowClickLink) {
            let url = props.rowClickLink(row);
            if (url) {
                handleLinkClick(event, url);
            }
        }
    }
};

/*
|---------------------------------------------------------------------------
| Styling Computed Properties
|---------------------------------------------------------------------------
*/
const pointerClass = computed(() =>
    props.allowRowClick || props.rowClickLink ? "pointer" : ""
);
const borderClass = computed(() => (props.gridLines ? "border" : ""));
const paddingClass = computed(() => (props.compact ? "p-1" : "p-3"));
const bgClass = (row: T, index: number): string | false => {
    let bgClass = props.rowBgFn ? props.rowBgFn(row) : false;

    if (props.striped && !bgClass) {
        return index % 2 === 1 ? "bg-slate-100" : false;
    }

    return bgClass;
};

/*
|-------------------------------------------------------------------------------
| For Column Sorting
|-------------------------------------------------------------------------------
*/
const getSortingIcon = (col: false | "asc" | "desc") => {
    switch (col) {
        case "asc":
            return "sort-down";
        case "desc":
            return "sort-up";
        default:
            return "sort";
    }
};

/*
|-------------------------------------------------------------------------------
| For Column Filtering
|-------------------------------------------------------------------------------
*/
const showFilterRow = computed(() => {
    let show = false;

    props.columns.map((col) => {
        if (col.filterable) {
            show = true;
        }
    });

    return show;
});

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
                header: (data) =>
                    h(TableHeaderCell, {
                        label: col.label,
                        meta: data.column.columnDef.meta,
                    }),
                enableColumnFilter: col.filterable ?? false,
                enableSorting: col.sort ?? false,
                meta: {
                    label: col.label,
                    icon: col.icon,
                    filterSelect: col.filterSelect ?? false,
                    filterPlaceholder: col.filterPlaceholder,
                },
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
    getFilteredRowModel: getFilteredRowModel(),
    getSortedRowModel: getSortedRowModel(),
});
</script>

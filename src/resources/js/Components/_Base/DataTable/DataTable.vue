<script setup lang="ts" generic="T">
import TableBodyEmpty from "./Components/TableBodyEmpty.vue";
import TableBodyLoading from "./Components/TableBodyLoading.vue";
import TableFooter from "./Components/TableFooter.vue";
import TableHeader from "./Components/TableHeader.vue";
import TableHeaderCell from "./Components/TableHeaderCell.vue";
import { computed, h, provide, ref } from "vue";
import { handleLinkClick } from "@/Composables/links.module";
import { fadeOut, fadeIn } from "./JS/animation";
import { router } from "@inertiajs/vue3";
import {
    useVueTable,
    createColumnHelper,
    getCoreRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    getPaginationRowModel,
    getFacetedRowModel,
    getFacetedUniqueValues,
    FlexRender,
} from "@tanstack/vue-table";
import type { AccessorFn, ColumnDef, Table } from "@tanstack/vue-table";

/*
|-------------------------------------------------------------------------------
| Internal Types
|-------------------------------------------------------------------------------
*/
interface tableColumnProp {
    label?: string;
    field: string;
    icon?: string;
    filterable?: boolean;
    filterPlaceholder?: string;
    filterSelect?: boolean;
    sort?: boolean;
}

/*
|-------------------------------------------------------------------------------
| Define Component
|-------------------------------------------------------------------------------
*/
defineSlots<{
    [key: string]: any;
}>();

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
    paginate?: boolean;
    syncLoadingState?: boolean;
    rowBgFn?: (row: T) => string | false;
    rowClickLink?: (row: T) => string;
}>();

/*
|-------------------------------------------------------------------------------
| Variables
|-------------------------------------------------------------------------------
*/
const paginationArray = ref<number[]>([10, 25, 50, 100]);
const perPage = ref<number>(paginationArray.value[0]);

const tableRows = computed<T[]>(() => props.rows);

/*
|-------------------------------------------------------------------------------
| Sync Loading State with Inertia JS Loading or manually control
|-------------------------------------------------------------------------------
*/
const isLoading = ref<boolean>(false);

router.on("start", () => {
    if (props.syncLoadingState) {
        isLoading.value = true;
    }
});

router.on("finish", () => {
    if (props.syncLoadingState) {
        isLoading.value = false;
    }
});

const startLoading = (): void => {
    isLoading.value = true;
};
const endLoading = (): void => {
    isLoading.value = false;
};

/*
|-------------------------------------------------------------------------------
| Table Events
|-------------------------------------------------------------------------------
*/
const onRowClick = (event: MouseEvent, row: T): void => {
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
|-------------------------------------------------------------------------------
| Styling Computed Properties
|-------------------------------------------------------------------------------
*/
const pointerClass = computed<string>(() =>
    props.allowRowClick || props.rowClickLink ? "pointer" : ""
);
const borderClass = computed<string>(() => (props.gridLines ? "border" : ""));
const paddingClass = computed<string>(() => (props.compact ? "p-1" : "p-3"));

const bgClass = (row: T, index: number): string | false => {
    let bgClass = props.rowBgFn ? props.rowBgFn(row) : false;

    if (props.striped && !bgClass) {
        return index % 2 === 1 ? "bg-slate-100" : false;
    }

    return bgClass;
};

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
    data: tableRows,
    initialState: {
        pagination: {
            pageIndex: 0,
            pageSize: perPage.value,
        },
    },
    meta: {
        borderClass: borderClass.value,
        paddingClass: paddingClass.value,
        paginate: props.paginate ?? false,
        paginationArray: paginationArray.value,
        perPage: perPage.value,
        pointerClass: pointerClass.value,
        bgClass,
    },
    getCoreRowModel: getCoreRowModel(),
    getFacetedRowModel: getFacetedRowModel(),
    getFacetedUniqueValues: getFacetedUniqueValues(),
    getFilteredRowModel: getFilteredRowModel(),
    getPaginationRowModel: props.paginate ? getPaginationRowModel() : undefined,
    getSortedRowModel: getSortedRowModel(),
});

provide<Table<T>, string>("table", table);

defineExpose({ startLoading, endLoading });
</script>

<template>
    <div class="overflow-x-auto w-full">
        <table class="table-auto w-full">
            <TableHeader>
                <template
                    v-for="name of Object.keys($slots)"
                    v-slot:[name]="data"
                >
                    <slot :name="name" v-bind="data" />
                </template>
            </TableHeader>
            <TableBodyLoading v-if="isLoading" />
            <TableBodyEmpty
                v-else-if="!table.getRowModel().rows.length"
                :noResultsText="noResultsText"
            >
                <template #no-results>
                    <slot name="no-results" />
                </template>
            </TableBodyEmpty>
            <TransitionGroup
                v-else
                name="data-table"
                :css="false"
                @beforeLeave="fadeOut"
                @enter="fadeIn"
            >
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
                            :name="`row.${cell.column.id}`"
                            :rowData="row.original"
                        >
                            <div
                                v-if="typeof cell.getValue() === 'boolean'"
                                class="text-center"
                            >
                                <fa-icon
                                    :icon="cell.getValue() ? 'check' : 'xmark'"
                                    :class="
                                        cell.getValue()
                                            ? 'text-success'
                                            : 'text-danger'
                                    "
                                />
                            </div>
                            <FlexRender
                                v-else
                                :render="cell.column.columnDef.cell"
                                :props="cell.getContext()"
                            />
                        </slot>
                    </td>
                </tr>
            </TransitionGroup>
            <TableFooter>
                <template #footer>
                    <slot name="footer" />
                </template>
            </TableFooter>
        </table>
    </div>
</template>

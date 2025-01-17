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
            <TransitionGroup
                name="data-table"
                :css="false"
                @beforeLeave="fadeOut"
                @enter="fadeIn"
            >
                <tr v-if="!table.getRowModel().rows.length" :key="0">
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
                            :name="`row.${cell.column.id}`"
                            :rowData="row.original"
                        >
                            <FlexRender
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

<script setup lang="ts" generic="T">
import TableHeader from "./TableHeader.vue";
import TableHeaderCell from "./TableHeaderCell.vue";
import TableFooter from "./TableFooter.vue";
import { computed, h, provide, ref } from "vue";
import { handleLinkClick } from "@/Composables/links.module";
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
import gsap from "gsap";

interface tableColumnProp {
    label?: string;
    field: string;
    icon?: string;
    filterable?: boolean;
    filterPlaceholder?: string;
    filterSelect?: boolean;
    sort?: boolean;
}

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
    rowBgFn?: (row: T) => string | false;
    rowClickLink?: (row: T) => string;
}>();

const paginationArray = ref([10, 25, 50, 100]);
const perPage = ref(paginationArray.value[0]);

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

/*
|-------------------------------------------------------------------------------
| Table Transition Animations
|-------------------------------------------------------------------------------
*/
const fadeOut = (el: Element) => {
    gsap.to(el, {
        opacity: 0,
        duration: 0.8,
    });
};

const fadeIn = (el: Element, done: () => void) => {
    gsap.from(el, {
        opacity: 0,
        duration: 0.8,
        onComplete: done,
    });
};
</script>

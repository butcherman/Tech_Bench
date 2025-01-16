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
            <tfoot v-if="paginate">
                <tr>
                    <td
                        :colspan="table.getAllColumns().length"
                        :class="paddingClass"
                    >
                        <slot name="footer">
                            <div class="flex flex-row justify-between w-full">
                                <div>
                                    <select
                                        v-model="perPage"
                                        class="border border-slate-300 rounded-lg p-1"
                                        @change="table.setPageSize(perPage)"
                                    >
                                        <option v-for="opt in paginationArray">
                                            {{ opt }}
                                        </option>
                                    </select>
                                    Results Per Page
                                </div>
                                <div>
                                    <ul class="flex flex-row">
                                        <li
                                            class="border rounded-s-lg p-1"
                                            :class="{
                                                pointer: currentPage !== 0,
                                                'text-muted': currentPage === 0,
                                            }"
                                            @click="goToFirstPage()"
                                            v-tooltip="'First Page'"
                                        >
                                            <fa-icon icon="angles-left" />
                                        </li>
                                        <li
                                            class="border p-1"
                                            :class="{
                                                pointer:
                                                    table.getCanPreviousPage(),
                                                'text-muted':
                                                    !table.getCanPreviousPage(),
                                            }"
                                            @click="goToPreviousPage()"
                                            v-tooltip="'Previous Page'"
                                        >
                                            <fa-icon icon="angle-left" />
                                        </li>
                                        <li
                                            v-for="page in table.getPageOptions()"
                                            :key="page"
                                            class="border p-1 pointer"
                                            :class="{
                                                'bg-slate-300 font-bold':
                                                    page === currentPage,
                                            }"
                                            @click="goToPage(page)"
                                        >
                                            {{ page + 1 }}
                                        </li>
                                        <li
                                            class="border p-1"
                                            :class="{
                                                pointer: table.getCanNextPage(),
                                                'text-muted':
                                                    !table.getCanNextPage(),
                                            }"
                                            @click="goToNextPage()"
                                            v-tooltip="'Next Page'"
                                        >
                                            <fa-icon icon="angle-right" />
                                        </li>
                                        <li
                                            class="border rounded-e-lg p-1"
                                            :class="{
                                                pointer:
                                                    currentPage !==
                                                    table.getPageCount(),
                                                'text-muted':
                                                    !table.getCanNextPage(),
                                            }"
                                            @click="goToLastPage()"
                                            v-tooltip="'Last Page'"
                                        >
                                            <fa-icon icon="angles-right" />
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    Showing Results {{ showingStart }} -
                                    {{ showingEnd }} of
                                    {{ table.getRowCount() }} results
                                </div>
                            </div>
                        </slot>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script setup lang="ts" generic="T">
import TableHeaderCell from "./TableHeaderCell.vue";
import TableHeaderFilter from "./TableHeaderFilter.vue";
import { computed, h, ref } from "vue";
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
import type { AccessorFn, ColumnDef } from "@tanstack/vue-table";
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
|---------------------------------------------------------------------------
| For Pagination
|---------------------------------------------------------------------------
*/
const paginationArray = ref([10, 25, 50, 100]);
const perPage = ref(paginationArray.value[0]);

const currentPage = computed(() => table.getState().pagination.pageIndex);
const showingStart = computed(() => currentPage.value * perPage.value + 1);
const showingEnd = computed(() =>
    Math.min(table.getRowCount(), showingStart.value + perPage.value - 1)
);

const goToPage = (pageIndex: number) => {
    table.setPageIndex(pageIndex);
};

const goToFirstPage = () => {
    if (currentPage.value !== 0) {
        table.firstPage();
    }
};

const goToPreviousPage = () => {
    if (table.getCanPreviousPage()) {
        table.previousPage();
    }
};

const goToNextPage = () => {
    if (table.getCanNextPage()) {
        table.nextPage();
    }
};

const goToLastPage = () => {
    if (currentPage.value !== table.getPageCount()) {
        table.lastPage();
    }
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
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFacetedRowModel: getFacetedRowModel(),
    getFacetedUniqueValues: getFacetedUniqueValues(),
    getPaginationRowModel: props.paginate ? getPaginationRowModel() : undefined,
});

/*
|---------------------------------------------------------------------------
| Table Transition Animations
|---------------------------------------------------------------------------
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

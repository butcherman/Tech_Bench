<script setup lang="ts" generic="T">
import { inject, ref, computed } from "vue";
import type { Table } from "@tanstack/vue-table";

const table = inject<Table<T>>("table");

/*
|-------------------------------------------------------------------------------
| Pagination Options
|-------------------------------------------------------------------------------
*/
const perPage = ref<number>(table?.options.meta?.perPage ?? 10);
const currentPage = computed<number>(
    () => table?.getState().pagination.pageIndex ?? 0
);
const showingStart = computed<number>(
    () => currentPage.value * perPage.value + 1
);
const showingEnd = computed<number>(() =>
    Math.min(table?.getRowCount() ?? 0, showingStart.value + perPage.value - 1)
);

/*
|-------------------------------------------------------------------------------
| Pagination Navigation
|-------------------------------------------------------------------------------
*/
const goToPage = (pageIndex: number): void => {
    table?.setPageIndex(pageIndex);
};

const goToFirstPage = (): void => {
    if (currentPage.value !== 0) {
        table?.firstPage();
    }
};

const goToPreviousPage = (): void => {
    if (table?.getCanPreviousPage()) {
        table.previousPage();
    }
};

const goToNextPage = (): void => {
    if (table?.getCanNextPage()) {
        table?.nextPage();
    }
};

const goToLastPage = (): void => {
    if (currentPage.value !== table?.getPageCount()) {
        table?.lastPage();
    }
};
</script>

<template>
    <tfoot>
        <tr>
            <td
                :colspan="table?.getAllColumns().length"
                :class="table?.options.meta?.paddingClass"
            >
                <slot name="footer">
                    <div
                        v-if="table?.options.meta?.paginate"
                        class="flex flex-row justify-between w-full"
                    >
                        <div>
                            <select
                                v-model="perPage"
                                class="border border-slate-300 rounded-lg p-1"
                                @change="table.setPageSize(perPage)"
                            >
                                <option
                                    v-for="opt in table?.options.meta
                                        ?.paginationArray"
                                >
                                    {{ opt }}
                                </option>
                            </select>
                            <span class="hidden lg:inline">
                                Results Per Page
                            </span>
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
                                        pointer: table?.getCanPreviousPage(),
                                        'text-muted':
                                            !table?.getCanPreviousPage(),
                                    }"
                                    @click="goToPreviousPage()"
                                    v-tooltip="'Previous Page'"
                                >
                                    <fa-icon icon="angle-left" />
                                </li>
                                <li
                                    v-for="page in table?.getPageOptions()"
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
                                        pointer: table?.getCanNextPage(),
                                        'text-muted': !table?.getCanNextPage(),
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
                                            table?.getPageCount(),
                                        'text-muted': !table?.getCanNextPage(),
                                    }"
                                    @click="goToLastPage()"
                                    v-tooltip="'Last Page'"
                                >
                                    <fa-icon icon="angles-right" />
                                </li>
                            </ul>
                        </div>
                        <div>
                            <span class="hidden lg:inline"> Showing </span>
                            {{ showingStart }} - {{ showingEnd }} of
                            {{ table?.getRowCount() }}
                            <span class="hidden lg:inline"> Results </span>
                        </div>
                    </div>
                </slot>
            </td>
        </tr>
    </tfoot>
</template>

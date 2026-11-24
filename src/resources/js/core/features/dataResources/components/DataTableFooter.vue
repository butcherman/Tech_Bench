<script setup lang="ts" generic="TData extends RowData">
import Paginate from "../../pagination/Paginate.vue";
import { computed, ref, watch } from "vue";
import type { RowData, Table } from "@tanstack/vue-table";

const props = defineProps<{
    table: Table<TData>;
    hasActionSlot?: boolean;
}>();

/*
|-------------------------------------------------------------------------------
| How many records per page
|-------------------------------------------------------------------------------
*/
const perPage = ref(25);
watch(perPage, (newPerPage) => props.table.setPageSize(newPerPage));

/*
|-------------------------------------------------------------------------------
| Pagination Data
|-------------------------------------------------------------------------------
*/
const currentPage = computed<number>(
    () => props.table.getState().pagination.pageIndex + 1,
);

const totalRecords = computed<number>(() => props.table.getRowCount());

/*
|-------------------------------------------------------------------------------
| Pagination Events
|-------------------------------------------------------------------------------
*/
const onGoToPage = (page: number): void => {
    props.table.setPageIndex(page - 1);
};
</script>

<template>
    <div
        class="w-full min-w-0 border-t border-slate-300"
        :class="table.options.meta?.paddingClass"
    >
        <slot name="footer">
            <Paginate
                v-if="table.options.meta?.paginate"
                v-model:per-page="perPage"
                :per-page-array="table.options.meta?.paginationArray"
                :current-page="currentPage"
                :total-records="totalRecords"
                @go-to-page="onGoToPage"
            />
        </slot>
    </div>
</template>

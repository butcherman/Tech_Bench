<script setup lang="ts" generic="TData extends RowData">
import Paginate from "@/core/components/Paginate.vue";
import { computed, ref, watch } from "vue";
import type { RowData, Table } from "@tanstack/vue-table";

const props = defineProps<{
    table: Table<TData>;
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
const currentPage = computed(
    () => props.table.getState().pagination.pageIndex + 1,
);

const totalRecords = computed(() => props.table.getRowCount());
const totalPages = computed(() =>
    Math.ceil(totalRecords.value / perPage.value),
);

/*
|-------------------------------------------------------------------------------
| Pagination Events
|-------------------------------------------------------------------------------
*/
const onGoToPage = (page: number): void => {
    console.log("go to page", page);
    props.table.setPageIndex(page - 1);
};
</script>

<template>
    <tfoot>
        <tr class="border-t border-slate-300 border-collapse">
            <td
                :colspan="table.getAllColumns().length"
                :class="table.options.meta?.paddingClass"
            >
                <slot name="footer">
                    <Paginate
                        v-if="table.options.meta?.paginate"
                        v-model:per-page="perPage"
                        :pagination-array="table.options.meta?.paginationArray"
                        :current-page="currentPage"
                        :total-records="totalRecords"
                        :total-pages="totalPages"
                        @go-to-page="onGoToPage"
                    />
                </slot>
            </td>
        </tr>
    </tfoot>
</template>

<script setup lang="ts" generic="TData extends RowData">
import SkeletonLoader from "@/core/components/loaders/SkeletonLoader.vue";
import { computed } from "vue";
import type { RowData, Table } from "@tanstack/vue-table";

const props = defineProps<{
    table: Table<TData>;
}>();

/**
 * How many loading rows should be displayed
 */
const skeletonRowCount = computed<number>(() => {
    if (props.table.options.meta?.paginate) {
        return props.table.options.meta.perPage;
    }

    let rowCount = props.table.getRowCount();

    return rowCount > 0 ? rowCount : 25;
});

/**
 * Number of columns the table has
 */
const skeletonColumnCount = computed<number>(
    () => props.table.getAllColumns().length,
);
</script>

<template>
    <tbody>
        <tr
            v-for="row in skeletonRowCount"
            :key="row"
            class="border-b border-slate-200 hover:bg-slate-300"
            :class="[table.options.meta?.stripedClass]"
        >
            <td
                v-for="col in skeletonColumnCount"
                :key="col"
                :class="[
                    table.options.meta?.paddingClass,
                    table.options.meta?.borderClass,
                ]"
            >
                <SkeletonLoader />
            </td>
        </tr>
    </tbody>
</template>

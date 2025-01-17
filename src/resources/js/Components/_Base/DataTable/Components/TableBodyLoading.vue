<template>
    <tbody>
        <tr v-for="(row, index) in skeletonRows" :key="index">
            <td
                v-for="col in skeletonColumns"
                :class="table?.options.meta?.paddingClass"
            >
                <Skeleton :width="`${Math.floor(Math.random() * 50) + 50}%`" />
            </td>
        </tr>
    </tbody>
</template>

<script setup lang="ts" generic="T">
import { computed, inject } from "vue";
import { Skeleton } from "primevue";
import type { Table } from "@tanstack/vue-table";

const table = inject<Table<T>>("table");

const skeletonRows = computed<number>(() => {
    if (table?.options.meta?.paginate) {
        return table.options.meta.perPage;
    }

    if (table?.getRowCount() && table.getRowCount() > 0) {
        return table?.getRowCount();
    }

    return 25;
});

const skeletonColumns = computed<number>(() => {
    return table?.getAllColumns().length ?? 1;
});
</script>

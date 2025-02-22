<script setup lang="ts" generic="T">
import TableHeaderFilter from "./TableHeaderFilter.vue";
import { computed, inject } from "vue";
import { FlexRender } from "@tanstack/vue-table";
import type { Table } from "@tanstack/vue-table";

defineSlots<{
    [key: string]: any;
}>();

const table = inject<Table<T>>("table");

/**
 * Get the Sort Icon for each column
 */
const getSortingIcon = (
    col: false | "asc" | "desc"
): "sort-down" | "sort-up" | "sort" => {
    switch (col) {
        case "asc":
            return "sort-down";
        case "desc":
            return "sort-up";
        default:
            return "sort";
    }
};

/**
 * Determine if we need to show the filtering row.
 */
const showFilterRow = computed<boolean>(() => {
    let show = false;

    table?.getAllColumns().forEach((col) => {
        if (col.getCanFilter()) {
            show = true;
        }
    });

    return show;
});
</script>

<template>
    <thead class="border-b border-slate-300 border-collapse">
        <tr
            v-for="headerGroup in table?.getHeaderGroups()"
            :key="headerGroup.id"
            class="border-b border-slate-300"
        >
            <th
                v-for="headerCell in headerGroup.headers"
                :key="headerCell.id"
                :class="table?.options.meta?.paddingClass"
            >
                <fa-icon
                    v-if="headerCell.column.getCanSort()"
                    :icon="getSortingIcon(headerCell.column.getIsSorted())"
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
                v-for="headerCell in table?.getHeaderGroups()[0].headers"
                class="p-1 font-normal"
            >
                <TableHeaderFilter
                    v-if="headerCell.column.getCanFilter()"
                    :column="headerCell.getContext().column"
                />
            </th>
        </tr>
    </thead>
</template>

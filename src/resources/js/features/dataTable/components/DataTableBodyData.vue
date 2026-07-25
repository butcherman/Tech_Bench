<script setup lang="ts" generic="TData extends RowData">
import { FlexRender } from "@tanstack/vue-table";
import type { RowData, Table } from "@tanstack/vue-table";

const props = defineProps<{
    table: Table<TData>;
}>();
//
</script>

<template>
    <tbody>
        <tr
            v-for="(row, index) in table.getRowModel().rows"
            :key="row.id"
            class="border-b border-slate-200 hover:bg-slate-300"
            :class="[
                table.options.meta?.stripedClass,
                table.options.meta?.pointerClass,
            ]"
        >
            <td
                v-for="cell in row.getAllCells()"
                :key="cell.id"
                :class="[
                    table.options.meta?.paddingClass,
                    table.options.meta?.borderClass,
                ]"
            >
                <div
                    class="flex"
                    :class="`justify-${cell.column.columnDef.meta?.align}`"
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
                </div>
            </td>
            <td
                v-if="table.options.meta?.actionsSlot"
                :class="[
                    table.options.meta?.paddingClass,
                    table.options.meta?.borderClass,
                ]"
            >
                <slot :name="`row.actions`" :rowData="row.original" />
            </td>
        </tr>
    </tbody>
</template>

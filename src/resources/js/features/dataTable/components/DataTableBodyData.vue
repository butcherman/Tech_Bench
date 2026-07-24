<script setup lang="ts" generic="TData extends RowData">
import { FlexRender } from "@tanstack/vue-table";
import type { RowData, Table } from "@tanstack/vue-table";

const props = defineProps<{
    table: Table<TData>;
}>();
//
</script>

<template>
    <TransitionGroup name="data-table" as="tbody">
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
                <slot :name="`row.${cell.column.id}`" :rowData="row.original">
                    <div
                        v-if="typeof cell.getValue() === 'boolean'"
                        class="text-center"
                    >
                        <fa-icon
                            :icon="cell.getValue() ? 'check' : 'xmark'"
                            :class="
                                cell.getValue() ? 'text-success' : 'text-danger'
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
    </TransitionGroup>
</template>

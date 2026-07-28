import BooleanIcon from "@/core/components/BooleanIcon.vue";
import { DataTableColumn } from "../types";
import { h } from "vue";
import type { DeepKeys, RowData } from "@tanstack/table-core";

export function booleanColumn<TRow extends RowData>(
    field: DeepKeys<TRow>,
    label: string,
    options: Partial<DataTableColumn<TRow>> = {},
): DataTableColumn<TRow, boolean> {
    return {
        field,

        label,

        icon: undefined,

        filterable: true,

        filterSelect: true,

        sort: true,

        align: "center",

        cell: (info) =>
            h(BooleanIcon, {
                value: info.getValue(),
            }),

        ...options,
    };
}

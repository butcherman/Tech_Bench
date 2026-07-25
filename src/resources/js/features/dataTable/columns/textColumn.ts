import type { DeepKeys, RowData } from "@tanstack/table-core";
import { DataTableColumn } from "../types";

export function textColumn<TRow extends RowData>(
    field: DeepKeys<TRow>,
    label: string,
    options: Partial<DataTableColumn<TRow>> = {},
): DataTableColumn<TRow, string> {
    return {
        field,

        label,

        icon: undefined,

        filterable: true,

        filterPlaceholder: `Filter ${label}`,

        filterSelect: false,

        sort: true,

        align: "start",

        ...options,
    };
}

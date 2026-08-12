import type { DataTableColumn } from "../types/types";
import type { DeepKeys, DeepValue, RowData } from "@tanstack/table-core";

export function textColumn<TRow extends RowData, TField extends DeepKeys<TRow>>(
    field: TField,
    label: string,
    options: Partial<DataTableColumn<TRow, DeepValue<TRow, TField>>> = {},
): DataTableColumn<TRow, DeepValue<TRow, TField>> {
    return {
        field,
        label,
        filterable: true,
        filterPlaceholder: `Filter ${label}`,
        filterSelect: false,
        sort: true,
        align: "start",

        ...options,
    };
}

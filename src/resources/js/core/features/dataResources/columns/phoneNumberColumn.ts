import type { DataTableColumn } from "../types/types";
import type { DeepKeys, DeepValue, RowData } from "@tanstack/table-core";

export function phoneNumberColumn<
    TRow extends RowData,
    TField extends DeepKeys<TRow>,
>(
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

        formatter: (value) => {
            const cleaned = ("" + value).replace(/\D/g, "");
            const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);

            if (match) {
                return "(" + match[1] + ") " + match[2] + "-" + match[3];
            }

            return null;
        },

        ...options,
    };
}

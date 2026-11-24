import { h } from "vue";
import type { DataTableColumn } from "../types/types";
import type { DeepKeys, DeepValue, RowData } from "@tanstack/table-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

export function iconColumn<TRow extends RowData, TField extends DeepKeys<TRow>>(
    field: TField,
    label: string,
    options: Partial<DataTableColumn<TRow, DeepValue<TRow, TField>>> = {},
): DataTableColumn<TRow, DeepValue<TRow, TField>> {
    return {
        field,
        label,
        filterable: false,
        filterSelect: false,
        sort: false,
        align: "center",

        cell: (info) =>
            h(FontAwesomeIcon, {
                icon: info.getValue(),
            }),

        ...options,
    };
}

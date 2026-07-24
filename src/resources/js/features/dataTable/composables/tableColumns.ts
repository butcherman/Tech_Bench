import DataTableHeaderCell from "../components/DataTableHeaderCell.vue";
import { h } from "vue";
import type { ColumnDef, RowData } from "@tanstack/vue-table";
import type { DataTableColumn } from "../types";

export function useTableColumns<TRow extends RowData>(
    columns: DataTableColumn<TRow>[],
) {
    return columns.map(
        (col): ColumnDef<TRow> => ({
            accessorFn: (row) => row[col.field as keyof TRow],
            id: col.field,
            cell: (info) => info.getValue(),
            header: (data) =>
                h(DataTableHeaderCell, {
                    label: col.label,
                    meta: data.column.columnDef.meta,
                }),
            enableColumnFilter: col.filterable ?? false,
            enableSorting: col.sort ?? false,
            meta: {
                label: col.label,
                icon: col.icon,
                filterSelect: col.filterSelect ?? false,
                filterPlaceholder: col.filterPlaceholder,
            },
        }),
    );
}

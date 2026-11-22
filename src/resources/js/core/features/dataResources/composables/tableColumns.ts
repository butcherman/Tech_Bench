import DataTableHeaderCell from "../components/DataTableHeaderCell.vue";
import { h } from "vue";
import type { ColumnDef, RowData } from "@tanstack/vue-table";
import type { DataTableColumn } from "../types/types.js";

export function useTableColumns<TRow extends RowData>(
    columns: DataTableColumn<TRow, unknown>[],
) {
    return columns.map(
        (col): ColumnDef<TRow> => ({
            accessorKey: col.field,
            cell: (info) => {
                if (col.cell) {
                    return col.cell(info);
                }

                if (col.formatter) {
                    return col.formatter(info.getValue(), info.row.original);
                }

                return info.getValue();
            },
            header: (data) =>
                h(DataTableHeaderCell, {
                    label: col.label,
                    meta: data.column.columnDef.meta,
                }),
            enableColumnFilter: col.filterable ?? false,
            enableSorting: col.sort ?? false,
            meta: col,
        }),
    );
}

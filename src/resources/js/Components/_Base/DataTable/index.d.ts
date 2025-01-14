import type { RowData } from "@tanstack/vue-table";

declare module "@tanstack/table-core" {
    interface ColumnMeta<TData extends RowData, TValue> {
        label: string;
        icon?: string;
        filterSelect: boolean;
        filterPlaceholder?: string;
    }
}

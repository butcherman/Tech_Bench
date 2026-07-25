import type { DeepKeys, RowData, CellContext } from "@tanstack/vue-table";
import type { VNodeChild } from "vue";

export interface DataTableColumn<TRow extends RowData, TValue = unknown> {
    field: DeepKeys<TRow>;
    filterable: boolean;
    filterPlaceholder?: string;
    filterSelect: boolean;
    label: string;
    sort: boolean;

    icon?: string;
    width?: number;
    align?: "start" | "center" | "end";

    formatter?: (value: unknown, row: TRow) => unknown;
    cell?: (info: CellContext<TRow, TValue>) => VNodeChild;
}

export interface DataTableProps<TRow extends RowData> {
    columns: DataTableColumn<TRow>[];
    data: TRow[];

    // Optional
    actionsSlot?: boolean;
    compact?: boolean;
    gridLines?: boolean;
    noResultsText?: string;
    striped?: boolean;

    rowClassFn?: (row: TRow) => string | false;

    //
    allowRowClick?: boolean;
    paginate?: boolean;
    syncLoadingState?: boolean;
    rowClickLink?: (row: TRow) => string;
}

declare module "@tanstack/table-core" {
    interface ColumnMeta<TData extends RowData, TValue> {
        align?: "start" | "center" | "end";
        filterPlaceholder?: string;
        filterSelect?: boolean;
        icon?: string;
        label?: string;
    }

    interface TableMeta<TData extends RowData> {
        borderClass: string;
        paddingClass: string;
        paginate: boolean;
        paginationArray: number[];
        perPage: number;
        pointerClass: string;
        stripedClass: string;
        actionsSlot?: boolean;

        rowClassFn?: (row: TData) => string | false;
    }
}

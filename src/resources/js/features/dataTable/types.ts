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
    striped?: boolean;
    gridLines?: boolean;
    noResultsText?: string;

    //
    allowRowClick?: boolean;
    paginate?: boolean;
    syncLoadingState?: boolean;
    rowBgFn?: (row: TRow) => string | false;
    rowClickLink?: (row: TRow) => string;
}

declare module "@tanstack/table-core" {
    interface ColumnMeta<TData extends RowData, TValue> {
        label?: string;
        icon?: string;
        align?: "start" | "center" | "end";
        filterSelect?: boolean;
        filterPlaceholder?: string;
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
        // bgClass: (row: TData, index: number) => string | false;
    }
}

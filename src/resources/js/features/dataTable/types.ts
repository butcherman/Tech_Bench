import type { DeepKeys, RowData } from "@tanstack/vue-table";

export interface DataTableColumn<TRow extends RowData> {
    label?: string;
    field: DeepKeys<TRow>;
    icon?: string;
    filterable?: boolean;
    filterPlaceholder?: string;
    filterSelect?: boolean;
    sort?: boolean;
}

export interface DataTableProps<TRow extends RowData> {
    columns: DataTableColumn<TRow>[];
    data: TRow[];
    compact?: boolean;
    striped?: boolean;
    gridLines?: boolean;
    allowRowClick?: boolean;
    noResultsText?: string;
    paginate?: boolean;
    syncLoadingState?: boolean;
    rowBgFn?: (row: TRow) => string | false;
    rowClickLink?: (row: TRow) => string;
}

declare module "@tanstack/table-core" {
    interface ColumnMeta<TData extends RowData, TValue> {
        label?: string;
        icon?: string;
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
        bgClass: (row: TData, index: number) => string | false;
    }
}

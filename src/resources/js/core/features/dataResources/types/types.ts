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

    formatter?: (value: TValue, row?: TRow) => unknown;
    cell?: (info: CellContext<TRow, TValue>) => VNodeChild;
}

export interface DataTableProps<TRow extends RowData> {
    columns: DataTableColumn<TRow>[];
    data: TRow[];

    // Optional
    actionsSlot?: boolean;
    allowRowClick?: boolean;
    compact?: boolean;
    gridLines?: boolean;
    noResultsText?: string;
    paginate?: boolean;
    striped?: boolean;

    rowClassFn?: (row: TRow) => string | false;
    rowClickFn?: (event: MouseEvent, row: TRow) => unknown;
}

export interface ResourceListProps<TRow> {
    list: TRow[];

    // Optional
    allowRowClick?: boolean;
    center?: boolean;
    compact?: boolean;
    gridLines?: boolean;
    hoverRow?: boolean;
    labelField?: keyof TRow;
    striped?: boolean;
    noBorder?: boolean;
    noResultsText?: string;
    paginate?: boolean;

    rowClassFn?: (row: TRow) => IndexedData<TRow> | false;
    rowClickFn?: (event: MouseEvent, row: TRow) => unknown;
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
        allowRowClick?: boolean;

        rowClassFn?: (row: TData) => string | false;
        rowClickFn?: (event: MouseEvent, row: TData) => unknown;
    }
}

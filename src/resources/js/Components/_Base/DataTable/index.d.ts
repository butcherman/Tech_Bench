import type { RowData } from "@tanstack/vue-table";
import type { ComputedRef } from "vue";

declare module "@tanstack/table-core" {
    interface ColumnMeta<TData extends RowData, TValue> {
        label?: string;
        icon?: string;
        filterSelect: boolean;
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

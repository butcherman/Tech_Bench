import { useTableColumns } from "./tableColumns";
import { useTableStyles } from "./tableStyles";
import {
    useVueTable,
    getCoreRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    getPaginationRowModel,
    getFacetedRowModel,
    getFacetedUniqueValues,
    RowData,
} from "@tanstack/vue-table";
import type { DataTableProps } from "../types";

export const useDataTable = <TRow extends RowData>(
    props: DataTableProps<TRow>,
) => {
    const { pointerClass, borderClass, paddingClass, stripedClass } =
        useTableStyles(props);

    return useVueTable({
        columns: useTableColumns(props.columns),
        data: props.data,
        initialState: {
            pagination: {
                pageIndex: 0,
                // pageSize: perPage.value,
            },
        },
        meta: {
            borderClass: borderClass.value,
            paddingClass: paddingClass.value,
            paginate: props.paginate ?? false,
            paginationArray: [], // paginationArray.value,
            perPage: 25, //perPage.value,
            pointerClass: pointerClass.value,
            stripedClass: stripedClass.value,
            // bgClass,
        },
        getCoreRowModel: getCoreRowModel(),
        getFacetedRowModel: getFacetedRowModel(),
        getFacetedUniqueValues: getFacetedUniqueValues(),
        getFilteredRowModel: getFilteredRowModel(),
        getPaginationRowModel: props.paginate
            ? getPaginationRowModel()
            : undefined,
        getSortedRowModel: getSortedRowModel(),
    });
};

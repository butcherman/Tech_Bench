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
import { useTableColumns } from "./tableColumns";
import type { DataTableProps } from "../types";

export const useDataTable = <TRow extends RowData>(
    props: DataTableProps<TRow>,
) => {
    return useVueTable({
        columns: useTableColumns(props.columns),
        data: [],
        initialState: {
            // pagination: {
            //     pageIndex: 0,
            //     pageSize: perPage.value,
            // },
        },
        // meta: {
        //     borderClass: borderClass.value,
        //     paddingClass: paddingClass.value,
        //     paginate: props.paginate ?? false,
        //     paginationArray: paginationArray.value,
        //     perPage: perPage.value,
        //     pointerClass: pointerClass.value,
        //     bgClass,
        // },
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

import { ref } from "vue";
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

    const perPage = ref(25);
    const paginationArray = ref([10, 25, 50, 100]);

    return useVueTable({
        columns: useTableColumns(props.columns),
        data: props.data,
        initialState: {
            pagination: {
                pageIndex: 0,
                pageSize: perPage.value,
            },
        },
        meta: {
            borderClass: borderClass.value,
            paddingClass: paddingClass.value,
            paginate: props.paginate ?? false,
            paginationArray: paginationArray.value,
            perPage: perPage.value,
            pointerClass: pointerClass.value,
            stripedClass: stripedClass.value,
            actionsSlot: props.actionsSlot,
            allowRowClick: props.allowRowClick,
            rowClassFn: props.rowClassFn,
            rowLinkFn: props.rowLinkFn,
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

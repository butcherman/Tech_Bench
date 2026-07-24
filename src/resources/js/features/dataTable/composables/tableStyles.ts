import { computed } from "vue";
import { DataTableProps } from "../types";
import { RowData } from "@tanstack/vue-table";

export const useTableStyles = <TRow extends RowData>(
    props: DataTableProps<TRow>,
) => {
    const pointerClass = computed<string>(() =>
        props.allowRowClick || props.rowClickLink ? "pointer" : "",
    );

    const borderClass = computed<string>(() =>
        props.gridLines ? "border" : "",
    );

    const paddingClass = computed<string>(() =>
        props.compact ? "p-1" : "p-3",
    );

    const stripedClass = computed(() =>
        props.striped ? "odd:bg-slate-100" : "",
    );

    // const bgClass = (row: TRow, index: number): string | false => {
    //     let bgClass = props.rowBgFn ? props.rowBgFn(row) : false;

    //     if (props.striped && !bgClass) {
    //         return index % 2 === 1 ? "bg-slate-100" : false;
    //     }

    //     return bgClass;
    // };

    return {
        pointerClass,
        borderClass,
        paddingClass,
        stripedClass,
        // bgClass,
    };
};

import { computed } from "vue";
import { DataTableProps } from "../types";
import { RowData } from "@tanstack/vue-table";

export const useTableStyles = <TRow extends RowData>(
    props: DataTableProps<TRow>,
) => {
    const pointerClass = computed<string>(() =>
        // props.allowRowClick || props.rowClickLink ? "pointer" : "",
        props.allowRowClick || props.rowLinkFn ? "pointer" : "",
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

    return {
        pointerClass,
        borderClass,
        paddingClass,
        stripedClass,
    };
};

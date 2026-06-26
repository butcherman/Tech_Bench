<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import ImportWorkbookData from "../ImportTableData/ImportWorkbookData.vue";
import okModal from "@/Modules/okModal";
import Overlay from "@/Components/_Base/Loaders/Overlay.vue";
import { v4 } from "uuid";
import { computed, inject, onMounted, ref, Ref } from "vue";
import { dataDelete, dataGet } from "@/Composables/axiosWrapper.module";
import { Field, FieldEntry, useFieldArray } from "vee-validate";
import {
    isPreviewMode,
    wbHash,
} from "@/Composables/Workbook/CustomerWorkbook.module";
import type { AxiosResponse } from "axios";

const props = defineProps<{
    allowAddRow: boolean;
    allowDeleteRow: boolean;
    allowExport: boolean;
    allowImport: boolean;
    columns: workbookTableColumn[];
    defaultRows: number;
    hideBorders: boolean;
    index: string;
    numberRows: boolean;
}>();

const {
    remove,
    push,
    fields,
    replace,
    update,
}: {
    remove: (idx: number) => void;
    push: (col: workbookTableRow) => void;
    fields: Ref<FieldEntry<workbookTableRow>[], FieldEntry<workbookTableRow>[]>;
    replace: (newArray: workbookTableRow[]) => void;
    update: (idx: number, value: any) => void;
} = useFieldArray(props.index);

const tableIsLocked = ref<boolean>(false);
const borderClass = computed<boolean>(() => !props.hideBorders);
const tableIsEmpty = computed<boolean>(() => {
    let empty = true;

    fields.value.forEach((row) => {
        let cols = Object.entries(row.value);
        cols.forEach(([index, val]) => {
            if (index !== "index" && val) {
                empty = false;
            }
        });
    });

    return empty;
});

/**
 * Inject save method
 */
const saveTableCell = inject<
    | ((
          arrayIndex: number,
          tableIndex: string,
          rowIndex: string,
          columnName: string,
      ) => void)
    | null
>("saveTableCell", null);

/**
 * Save the value of a table cell in the database
 */
const saveCell = (
    arrayIndex: number,
    columnName: string,
    rowIndex: string,
): void => {
    if (saveTableCell && !isPreviewMode.value) {
        saveTableCell(arrayIndex, props.index, rowIndex, columnName);
    }
};

/**
 * Delete a row from the database and UI
 */
const deleteRow = (row: FieldEntry<workbookTableRow>, index: number): void => {
    dataDelete(
        route("cust-workbook.del-row", [
            wbHash.value,
            props.index,
            row.value.index,
        ]),
    ).then((res) => {
        if (res) {
            remove(index);
        }
    });
};

/**
 * Determine what type of input should be displayed
 */
const getInputType = (column: workbookTableColumn): string => {
    switch (column.type) {
        case "integer":
            return "number";
        case "boolean":
            return "checkbox";
        default:
            return "text";
    }
};

/**
 * Export the data table information
 */
const exportTableData = (): void => {
    if (isPreviewMode.value) {
        okModal("Export Data Confirmation");
        return;
    }

    window.location.href = route("cust-workbook.export", [
        wbHash.value,
        props.index,
    ]);
};

/**
 * Refresh the table data
 */
const refreshTableData = () => {
    dataGet(
        route("cust-workbook.import.index", [wbHash.value, props.index]),
    ).then(
        (res: AxiosResponse<workbookTableRow[], workbookTableRow[]> | void) => {
            if (res) {
                tableIsLocked.value = false;
                replace(res.data);
            }
        },
    );
};

// A blank row to add to the table
const defaultRow = (): workbookTableRow => {
    let rowData: workbookTableRow = {
        index: v4(),
    };

    return rowData;
};

onMounted(() => {
    if (!fields.value.length) {
        for (let n = 0; n < props.defaultRows; n++) {
            push(defaultRow());
        }
    }

    Echo.channel(`equipment-workbook.${wbHash.value}.${props.index}`)
        .listen(
            ".WorkbookTableValueUpdated",
            (valData: workbookTableValueEvent) => {
                let updatedModel = valData.model;
                let rowIdx = fields.value.findIndex(
                    (row) => row.value.index === updatedModel.row_index,
                );

                if (rowIdx < 0) {
                    // If this is a new row, push it to the array
                    let newRow = defaultRow();
                    newRow[updatedModel.column_name] = updatedModel.value;

                    push(newRow);
                } else {
                    // If this is an existing row, update the data
                    let rowCopy = { ...fields.value[rowIdx].value };
                    rowCopy[updatedModel.column_name] = updatedModel.value;

                    update(rowIdx, rowCopy);
                }
            },
        )
        .listen(
            ".WorkbookTableRowDeleted",
            (event: { tableIndex: string; rowIndex: string }) => {
                let rowIdx = fields.value.findIndex(
                    (row) => row.value.index === event.rowIndex,
                );

                if (rowIdx >= 0) {
                    remove(rowIdx);
                }
            },
        )
        .listen(
            ".WorkbookTableLockEvent",
            (event: { tableIndex: string; lockTable: boolean }) => {
                tableIsLocked.value = event.lockTable;

                if (!event.lockTable) {
                    refreshTableData();
                }
            },
        );
});
</script>

<template>
    <div class="relative group/table">
        <Overlay :loading="tableIsLocked">
            <template #loader>
                <div class="flex flex-col justify-center">
                    <h2 class="text-center">Table Is Locked</h2>
                    <h5 class="text-center">Please Wait</h5>
                </div>
            </template>
            <table
                class="table-auto w-full border-slate-200"
                :class="{ border: borderClass }"
            >
                <thead>
                    <tr
                        class="border-slate-200"
                        :class="{ border: borderClass }"
                    >
                        <td
                            v-if="numberRows"
                            class="border-slate-200"
                            :class="{ border: borderClass }"
                        ></td>
                        <th
                            v-for="col in columns"
                            class="border-slate-200"
                            :class="{ border: borderClass }"
                        >
                            {{ col.name }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(row, idx) in fields"
                        :key="row.key"
                        class="border-slate-200"
                        :class="{ border: borderClass }"
                    >
                        <td
                            v-if="numberRows"
                            class="border-slate-200 text-center"
                            :class="{ border: borderClass }"
                        >
                            {{ idx + 1 }}
                        </td>
                        <td
                            v-for="col in columns"
                            class="border-slate-200 px-1"
                            :class="{ border: borderClass }"
                        >
                            <Field
                                v-if="col.type === 'boolean'"
                                class="w-full m-0 px-2"
                                :name="`${index}[${idx}].${col.name}`"
                                type="checkbox"
                                :value="true"
                                :unchecked-value="false"
                                @change="
                                    saveCell(idx, col.name, row.value.index)
                                "
                            />
                            <Field
                                v-else-if="col.type === 'enum'"
                                as="select"
                                class="w-full m-0 px-2"
                                :name="`${index}[${idx}].${col.name}`"
                                @change="
                                    saveCell(idx, col.name, row.value.index)
                                "
                            >
                                <option />
                                <option
                                    v-for="opt in col.list"
                                    :key="opt"
                                    :value="opt"
                                >
                                    {{ opt }}
                                </option>
                            </Field>
                            <Field
                                v-else
                                class="w-full m-0 px-2"
                                :name="`${index}[${idx}].${col.name}`"
                                :type="getInputType(col)"
                                @change="
                                    saveCell(idx, col.name, row.value.index)
                                "
                            />
                        </td>
                        <td v-if="allowDeleteRow" class="text-center">
                            <DeleteBadge
                                icon="circle-xmark"
                                v-tooltip.left="'Delete Row'"
                                confirm
                                @accepted="deleteRow(row, idx)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex flex-row-reverse my-2">
                <AddButton
                    class="mx-1"
                    size="small"
                    pill
                    @click="push(defaultRow())"
                />
                <BaseButton
                    class="mx-2"
                    text="Export Data"
                    size="small"
                    variant="info"
                    pill
                    @click="exportTableData"
                />
                <ImportWorkbookData
                    :table-index="index"
                    :table-empty="tableIsEmpty"
                    @import-success="refreshTableData"
                />
            </div>
        </Overlay>
    </div>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import okModal from "@/Modules/okModal/index.js";
import { computed, ref } from "vue";
import { v4 } from "uuid";
import { isPreviewMode } from "@/Composables/Workbook/CustomerWorkbook.module";

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

const borderClass = computed(() => !props.hideBorders);

/**
 * Determine what type of input should be displayed
 */
const getInputType = (column: workbookTableColumn): string => {
    switch (column.type) {
        case "Number":
            return "number";
        case "Checkbox":
            return "checkbox";
        default:
            return "text";
    }
};

/**
 * Add a new row to the table
 */
const addRow = () => {
    tableData.value.push({
        index: v4(),
    });
};

/**
 * Delete the selected row
 */
const deleteRow = (rowIndex: number): void => {
    tableData.value.splice(rowIndex, 1);
};

/**
 * Build some fake data rows for demo purposes
 * TODO - Build the real data
 */
const buildTableData = () => {
    let tableData: workbookTableValue[] = [];

    for (let i: number = 0; i < props.defaultRows; i++) {
        let dataRow: workbookTableValue = {
            index: v4(),
        };

        tableData.push(dataRow);
    }

    return tableData;
};

const tableData = ref(buildTableData());

/**
 * Show a pop-up that import or export was clicked
 */
const importPopUp = (type: "Import" | "Export") => {
    if (isPreviewMode.value) {
        okModal(`${type} Process Triggered`);
        return;
    }

    alert("put something here...");
};
</script>

<template>
    <div class="relative group/table">
        <table
            class="table-auto w-full border-slate-200"
            :class="{ border: borderClass }"
        >
            <thead>
                <tr class="border-slate-200" :class="{ border: borderClass }">
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
                    v-for="(row, index) in tableData"
                    class="border-slate-200"
                    :class="{ border: borderClass }"
                    :key="row.index"
                >
                    <td
                        v-if="numberRows"
                        class="border-slate-200 text-center"
                        :class="{ border: borderClass }"
                    >
                        {{ index + 1 }}
                    </td>
                    <td
                        v-for="col in columns"
                        class="border-slate-200 px-1"
                        :class="{ border: borderClass }"
                    >
                        <select
                            v-if="col.type === 'Drop List'"
                            class="w-full m-0 py-0 px-2"
                        >
                            <option />
                            <option v-for="opt in col.list.split(',')">
                                {{ opt }}
                            </option>
                        </select>
                        <input
                            v-else
                            :type="getInputType(col)"
                            class="w-full m-0 px-2"
                            v-model="tableData[index][col.name]"
                        />
                    </td>
                    <td v-if="allowDeleteRow" class="text-center">
                        <DeleteBadge
                            icon="circle-xmark"
                            v-tooltip.left="'Delete Row'"
                            confirm
                            @accepted="deleteRow(index)"
                        />
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="flex flex-row-reverse my-2">
            <AddButton class="mx-1" size="small" pill @click="addRow" />
            <BaseButton
                v-if="allowImport"
                class="mx-1"
                size="small"
                text="Import"
                icon="upload"
                variant="info"
                pill
                @click="importPopUp('Import')"
            />
            <BaseButton
                v-if="allowExport"
                class="mx-1"
                size="small"
                text="Export"
                icon="download"
                variant="info"
                pill
                @click="importPopUp('Export')"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import { computed, inject, onMounted, Ref } from "vue";
import { Field, FieldEntry, useFieldArray } from "vee-validate";
import { v4 } from "uuid";

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
}: {
    remove: (idx: number) => void;
    push: (col: workbookTableRow) => void;
    fields: Ref<FieldEntry<workbookTableRow>[], FieldEntry<workbookTableRow>[]>;
} = useFieldArray(props.index);
const borderClass = computed(() => !props.hideBorders);

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

const saveCell = (
    arrayIndex: number,
    columnName: string,
    rowIndex: string,
): void => {
    if (saveTableCell) {
        saveTableCell(arrayIndex, props.index, rowIndex, columnName);
    }
};

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

// A blank row to add to the table
const defaultRow = (): workbookTableRow => {
    console.log("getting default row");
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
});
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
                            v-if="col.type === 'Checkbox'"
                            class="w-full m-0 px-2"
                            :name="`${index}[${idx}].${col.name}`"
                            type="checkbox"
                            :value="true"
                            :unchecked-value="false"
                            @change="saveCell(idx, col.name, row.value.index)"
                        />
                        <Field
                            v-else-if="col.type === 'Drop List'"
                            as="select"
                            class="w-full m-0 px-2"
                            :name="`${index}[${idx}].${col.name}`"
                            @change="saveCell(idx, col.name, row.value.index)"
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
                            @change="saveCell(idx, col.name, row.value.index)"
                        />
                    </td>
                    <td v-if="allowDeleteRow" class="text-center">
                        <DeleteBadge
                            icon="circle-xmark"
                            v-tooltip.left="'Delete Row'"
                            confirm
                            @accepted="remove(idx)"
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
        </div>
    </div>
</template>

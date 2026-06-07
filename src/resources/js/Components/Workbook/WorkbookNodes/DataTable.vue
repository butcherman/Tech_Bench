<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import { computed, inject, onMounted } from "vue";
import { Field, useFieldArray } from "vee-validate";
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

const { remove, push, fields } = useFieldArray(props.index);
const borderClass = computed(() => !props.hideBorders);

/**
 * Inject save method
 */
const saveTableCell = inject<
    ((tableIndex: string, rowIndex: number, columnName: string) => void) | null
>("saveTableCell", null);

const saveCell = (rowIndex: number, columnName: string): void => {
    if (saveTableCell) {
        saveTableCell(props.index, rowIndex, columnName);
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
const defaultRow = computed(() => {
    console.log("getting default row");
    let rowData: { [key: string]: undefined | string } = {
        index: v4(),
    };

    return rowData;
});

onMounted(() => {
    if (!fields.value.length) {
        for (let n = 0; n < props.defaultRows; n++) {
            push(defaultRow.value);
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
                    v-for="(field, idx) in fields"
                    :key="field.key"
                    class="border-slate-200"
                    :class="{ border: borderClass }"
                >
                    <td
                        v-if="numberRows"
                        class="border-slate-200 text-center"
                        :class="{ border: borderClass }"
                    >
                        {{ idx + 1 }}
                        {{ field }}
                    </td>
                    <td
                        v-for="col in columns"
                        class="border-slate-200 px-1"
                        :class="{ border: borderClass }"
                    >
                        <Field
                            :name="`${index}[${idx}].${col.name}`"
                            v-slot="{ field }"
                            @change="saveCell(idx, col.name)"
                        >
                            <select
                                v-if="col.type === 'Drop List'"
                                class="w-full m-0 py-0 px-2"
                                v-bind="field"
                            >
                                <option />
                                <option
                                    v-for="opt in col.list"
                                    :key="opt"
                                    :value="opt"
                                >
                                    {{ opt }}
                                </option>
                            </select>
                            <input
                                v-else
                                :type="getInputType(col)"
                                class="w-full m-0 px-2"
                                v-bind="field"
                                :unchecked-value="false"
                            />
                        </Field>
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
                @click="push(defaultRow)"
            />
        </div>
    </div>
</template>

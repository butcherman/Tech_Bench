<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { ref } from "vue";
import { useFieldArray, useForm } from "vee-validate";
import { object, number, boolean, array, string } from "yup";
import { closeNodeEditor } from "@/Composables/Workbook/WorkbookEditor.module";

import type { DropdownChangeEvent } from "primevue";

const props = defineProps<{
    node: workbookNode;
}>();

// Copy of Element data so we don't modify the original saved data
const formData = JSON.parse(JSON.stringify(props.node.props));

/**
 * Table Column Data
 */
const tableColumnTypes = ["Text", "Number", "Checkbox", "Drop List"];
const tableColumns = ref<workbookTableColumn[]>(formData.columns);
const updateColumnData = (event: DropdownChangeEvent, index: number): void => {
    tableColumns.value[index].type = event.value;
};

/**
 * Add/Remove Columns
 */
const addColumn = () => {
    let defaultColumn: workbookTableColumn = {
        name: "New Column",
        type: "Text",
    };

    tableColumns.value.push(defaultColumn);
    push(defaultColumn);
};

const removeColumn = (index: number): void => {
    tableColumns.value.splice(index, 1);
    remove(index);
};

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const validationSchema = object({
    columns: array().of(
        object().shape({
            name: string().required("The Column Name is Required"),
            type: string().required(),
            list: string().when("type", {
                is: "Drop List",
                then: (s) => s.required("List of Options Required"),
                otherwise: (s) => s.nullable(),
            }),
        }),
    ),
    default_rows: number()
        .required("How many rows should show if no data is present?")
        .min(2)
        .max(200)
        .label("Default Number of Rows"),
    allow_add_row: boolean().required(),
    allow_delete_row: boolean().required(),
    allow_import: boolean().required(),
    allow_export: boolean().required(),
    hide_borders: boolean().required(),
    number_rows: boolean().required(),
});

const initialValues = {
    allow_add_row: formData.allowAddRow,
    allow_delete_row: formData.allowDeleteRow,
    allow_export: formData.allowExport,
    allow_import: formData.allowImport,
    columns: formData.columns,
    default_rows: formData.defaultRows,
    hide_borders: formData.hideBorders,
    number_rows: formData.numberRows,
};

const { handleSubmit } = useForm({
    validationSchema: validationSchema,
    initialValues: initialValues,
});

const { remove, push, fields } = useFieldArray("columns");

/**
 * Update the Data Table Settings
 */
const saveData = handleSubmit((form) => {
    // Any drop list column needs to have the list made to an array
    form.columns.forEach((col: workbookTableColumn) => {
        if (col.list && typeof col.list === "string") {
            col.list = col.list.split(",").map((item) => item.trim());
        }
    });

    // Save the full form
    props.node.props.allowAddRow = form.allow_add_row;
    props.node.props.allowDeleteRow = form.allow_delete_row;
    props.node.props.allowExport = form.allow_export;
    props.node.props.allowImport = form.allow_import;
    props.node.props.columns = form.columns;
    props.node.props.defaultRows = form.default_rows;
    props.node.props.hideBorders = form.hide_borders;
    props.node.props.numberRows = form.number_rows;

    closeNodeEditor();
});
</script>

<template>
    <form novalidate v-focustrap @submit.prevent="saveData">
        <fieldset class="border border-slate-300 p-3">
            <legend>Table Columns</legend>
            <template v-for="(field, index) in fields" :key="field.key">
                <div class="flex w-full gap-2">
                    <TextInput
                        class="basis-1/3"
                        label="Column Name"
                        :id="`columns-${field.key}`"
                        :name="`columns[${index}].name`"
                    />
                    <SelectInput
                        class="basis-1/5"
                        label="Column Type"
                        :id="`col-name-${field.key}`"
                        :name="`columns[${index}].type`"
                        :list="tableColumnTypes"
                        @change="updateColumnData($event, index)"
                    />
                    <div class="grow">
                        <TextInput
                            v-if="tableColumns[index].type === 'Drop List'"
                            :id="`col-name-${field.key}`"
                            :name="`columns[${index}].list`"
                            label="Dropdown Options (use comma to separate)"
                        />
                    </div>
                    <div
                        class="flex flex-col justify-center ms-2 text-danger pointer"
                        v-tooltip="'Remove Column'"
                        @click="removeColumn(index)"
                    >
                        <fa-icon icon="circle-xmark" />
                    </div>
                </div>
            </template>
            <div class="flex flex-row-reverse">
                <AddButton
                    text="Add Column"
                    size="small"
                    pill
                    @click="addColumn"
                />
            </div>
        </fieldset>
        <div class="flex justify-center">
            <div class="w-75">
                <TextInput
                    id="default-rows"
                    name="default_rows"
                    label="Default Number of Rows"
                    type="number"
                />
                <SwitchInput
                    id="allow-add"
                    name="allow_add_row"
                    label="Allow Add Rows"
                />

                <SwitchInput
                    id="allow-delete"
                    name="allow_delete_row"
                    label="Allow Delete Rows"
                />
                <SwitchInput
                    id="hide-borders"
                    name="hide_borders"
                    label="Hide Borders"
                />
                <SwitchInput
                    id="number-rows"
                    name="number_rows"
                    label="Number Rows"
                />
                <SwitchInput
                    id="allow-import"
                    name="allow_import"
                    label="Allow Data Import"
                />
                <SwitchInput
                    id="allow-export"
                    name="allow_export"
                    label="Allow Data Export"
                />
            </div>
        </div>
        <div class="flex-none text-center mt-4">
            <BaseButton class="w-3/4" type="submit" variant="primary">
                Save
            </BaseButton>
        </div>
    </form>
</template>

<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Modal from "@/Components/_Base/Modal.vue";
import SelectInput from "@/Forms/_Base/SelectInput.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { FieldEntry, useFieldArray, useForm } from "vee-validate";
import { object, number, boolean, array, string } from "yup";
import { closeNodeEditor } from "@/Composables/Workbook/WorkbookEditor.module";
import { ref, Ref, useTemplateRef } from "vue";

interface formDataInterface {
    allowAddRow: boolean;
    allowDeleteRow: boolean;
    allowExport: boolean;
    allowImport: boolean;
    columns: workbookTableColumn[];
    defaultRows: number;
    hideBorders: boolean;
    numberRows: boolean;
}

const props = defineProps<{
    node: workbookNode;
}>();

const optionsModal = useTemplateRef("advanced-col-options");
const activeColIndex = ref<number | null>(null);

// Copy of Element data so we don't modify the original saved data
const formData: formDataInterface = JSON.parse(
    JSON.stringify(props.node.props),
);
formData.columns?.map((col) => {
    if (col.type === "enum" && Array.isArray(col.list)) {
        col.list = col.list?.join(", ");
    }
});

/**
 * Table Column Data
 */
const tableColumnTypes: { text: string; value: workbookTableColumnType }[] = [
    {
        text: "Text",
        value: "string",
    },
    {
        text: "Number",
        value: "integer",
    },
    {
        text: "Checkbox",
        value: "boolean",
    },
    {
        text: "Drop List",
        value: "enum",
    },
];

/**
 * Add/Remove Columns
 */
const addColumn = () => {
    let defaultColumn: workbookTableColumn = {
        name: "New Column",
        type: "string",
        allowDefault: false,
        defaultValue: "",
        hiddenColumn: false,
    };

    push(defaultColumn);
};

const removeColumn = (index: number): void => {
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
                is: "enum",
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
    allow_default: false,
    hidden_column: false,
    default_value: "",
};

const { handleSubmit, values, setFieldValue } = useForm({
    validationSchema: validationSchema,
    initialValues: initialValues,
});

const {
    remove,
    push,
    fields,
}: {
    remove: (idx: number) => void;
    push: (col: workbookTableColumn) => void;
    fields: Ref<
        FieldEntry<workbookTableColumn>[],
        FieldEntry<workbookTableColumn>[]
    >;
} = useFieldArray("columns");

/**
 * Open the Advanced Settings for a column
 */
const openAdvancedSettings = (colIndex: number) => {
    let fieldToModify: workbookTableColumn = fields.value[colIndex].value;

    activeColIndex.value = colIndex;
    setFieldValue("allow_default", fieldToModify.allowDefault ?? false);
    setFieldValue("hidden_column", fieldToModify.hiddenColumn ?? false);
    setFieldValue("default_value", fieldToModify.defaultValue ?? "");

    optionsModal.value?.show();
};

/**
 * Save and close the Advanced Settings for a Column
 */
const closeAdvancedSettings = () => {
    if (activeColIndex.value !== null) {
        console.log(activeColIndex.value);
        fields.value[activeColIndex.value].value.allowDefault =
            values.allow_default;
        fields.value[activeColIndex.value].value.defaultValue =
            values.default_value;
        fields.value[activeColIndex.value].value.hiddenColumn =
            values.hidden_column;

        optionsModal.value?.hide();
    }
};

/**
 * Put the default Advanced Settings back in place
 */
const resetAdvancedSettings = () => {
    activeColIndex.value = null;
    setFieldValue("allow_default", false);
    setFieldValue("hidden_column", false);
    setFieldValue("default_value", "");
};

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
                        value-field="value"
                        text-field="text"
                    />
                    <div class="grow">
                        <TextInput
                            v-if="values.columns[index].type === 'enum'"
                            :id="`col-name-${field.key}`"
                            :name="`columns[${index}].list`"
                            label="Dropdown Options (use comma to separate)"
                        />
                    </div>
                    <div
                        class="flex flex-col justify-center ms-2 pointer"
                        v-tooltip.left="'Advanced Options'"
                        @click="openAdvancedSettings(index)"
                    >
                        <fa-icon icon="fa-sliders" />
                    </div>

                    <div
                        class="flex flex-col justify-center ms-2 text-danger pointer"
                        v-tooltip.left="'Remove Column'"
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
        <Modal
            ref="advanced-col-options"
            title="Advanced Options"
            @hidden="resetAdvancedSettings()"
        >
            <SwitchInput
                id="allow-default"
                name="allow_default"
                label="Allow Default Value"
            />
            <TextInput
                v-if="values.allow_default"
                id="default-value"
                name="default_value"
                label="Default Value"
                help="Optional"
            />
            <SwitchInput
                id="hidden-column"
                name="hidden_column"
                label="Hidden From Public View"
            />
            <div class="flex justify-center">
                <BaseButton text="OK" @click="closeAdvancedSettings()" />
            </div>
        </Modal>
    </form>
</template>

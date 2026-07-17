<script setup lang="ts">
import AddButton from "@/Components/_Base/Buttons/AddButton.vue";
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { closeNodeEditor } from "@/Composables/Workbook/WorkbookEditor.module";
import { string, object, boolean, array } from "yup";
import { FieldEntry, useFieldArray, useForm } from "vee-validate";
import { Ref } from "vue";

const props = defineProps<{
    node: workbookNode;
}>();

const validationSchema = object({
    allowAddRow: boolean().required(),
    allowDeleteRow: boolean().required(),
    centerList: boolean().required(),
    defaultList: array().min(1, "There must be at least one item").required(),
    title: string().required("Please enter a title for this Task List"),
});

const initialValues = {
    allowAddRow: props.node.props.allowAddRow,
    allowDeleteRow: props.node.props.allowDeleteRow,
    centerList: props.node.props.centerList,
    defaultList: props.node.props.defaultList,
    title: props.node.props.title,
};

const { handleSubmit, errors } = useForm({
    validationSchema: validationSchema,
    initialValues: initialValues,
});

const {
    remove,
    push,
    fields,
}: {
    remove: (idx: number) => void;
    push: (col: string) => void;
    fields: Ref<FieldEntry<string>[], FieldEntry<string>[]>;
} = useFieldArray("defaultList");

/**
 * Update the Text of the field being modified.
 */
const saveData = handleSubmit((form) => {
    props.node.props.allowAddRow = form.allowAddRow;
    props.node.props.allowDeleteRow = form.allowDeleteRow;
    props.node.props.centerList = form.centerList;
    props.node.props.title = form.title;
    props.node.props.defaultList = form.defaultList.filter(Boolean);

    closeNodeEditor();
});
</script>

<template>
    <form novalidate v-focustrap @submit.prevent="saveData">
        <fieldset class="border border-slate-300 p-3">
            <legend>List Items</legend>
            <div class="text-danger">{{ errors.defaultList }}</div>
            <template v-for="(field, index) in fields" :key="field.key">
                <div class="flex">
                    <TextInput
                        :id="`defaultList[${index}]`"
                        :name="`defaultList[${index}]`"
                        label="List Item"
                        class="grow"
                    />
                    <div
                        class="flex flex-col justify-center ms-2 text-danger pointer"
                    >
                        <fa-icon icon="circle-xmark" @click="remove(index)" />
                    </div>
                </div>
            </template>
            <div class="flex flex-row-reverse">
                <AddButton size="small" pill @click="push('')" />
            </div>
        </fieldset>
        <TextInput id="title" name="title" label="List Title" />
        <div class="flex justify-center">
            <div>
                <SwitchInput
                    id="switch-input"
                    name="centerList"
                    label="Center List"
                />
                <SwitchInput
                    id="allow-add-row"
                    name="allowAddrow"
                    label="Allow Add New Items"
                />
                <SwitchInput
                    id="allow-delete-row"
                    name="allowDeleteRow"
                    label="Allow Delete Item"
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

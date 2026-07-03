<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Editor from "@/Forms/_Base/Editor.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import { closeNodeEditor } from "@/Composables/Workbook/WorkbookEditor.module";
import { string, object, boolean } from "yup";
import { useForm } from "vee-validate";

const props = defineProps<{
    node: workbookNode;
}>();

const validationSchema = object({
    content: string().required("Please input the content for this field"),
    center: boolean().required(),
});

const initialValues = {
    content: props.node.props.text,
    center: props.node.props.class === "text-center" ? true : false,
};

const { handleSubmit } = useForm({
    validationSchema: validationSchema,
    initialValues: initialValues,
});

/**
 * Update the Text of the field being modified.
 */
const saveData = handleSubmit((form) => {
    props.node.props.text = form.content;
    props.node.props.class = form.center ? "text-center" : "";
    closeNodeEditor();
});
</script>

<template>
    <form novalidate v-focustrap @submit.prevent="saveData">
        <Editor :id="node.index" name="content" label="Content" />
        <SwitchInput id="switch-input" name="center" label="Center Text" />
        <div class="flex-none text-center mt-4">
            <BaseButton class="w-3/4" type="submit" variant="primary">
                Save
            </BaseButton>
        </div>
    </form>
</template>

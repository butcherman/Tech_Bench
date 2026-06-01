<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { string, object } from "yup";
import { useForm } from "vee-validate";
import { closeNodeEditor } from "@/Composables/Workbook/Canvas/WorkbookEditor.module";

const props = defineProps<{
    node: workbookNode;
}>();

const validationSchema = object({
    text: string().nullable(),
});

const initialValues = {
    text: props.node.props.text,
};

const { handleSubmit } = useForm({
    validationSchema: validationSchema,
    initialValues: initialValues,
});

/**
 * Update the Text of the field being modified.
 */
const saveData = handleSubmit((form) => {
    props.node.props.text = form.text;
    closeNodeEditor();
});
</script>

<template>
    <form novalidate v-focustrap @submit.prevent="saveData">
        <TextInput :id="node.index" name="text" label="Fieldset Title" />
        <div class="flex-none text-center mt-4">
            <BaseButton class="w-3/4" type="submit" variant="primary">
                Save
            </BaseButton>
        </div>
    </form>
</template>

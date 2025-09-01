<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import TextAreaInput from "@/Forms/_Base/TextAreaInput.vue";
import { string, object } from "yup";
import { closeWbEditor } from "@/Composables/Equipment/WorkbookEditor.module";
import { useForm } from "vee-validate";

const props = defineProps<{
    element: workbookElement;
}>();

const validationSchema = object({
    content: string().required("Please input the content for this field"),
});

const initialValues = {
    content: props.element.text,
};

const { handleSubmit } = useForm({
    validationSchema: validationSchema,
    initialValues: initialValues,
});

/**
 * Update the Text of the field being modified.
 */
const saveData = handleSubmit((form) => {
    props.element.text = form.content;
    closeWbEditor();
});
</script>

<template>
    <form novalidate v-focustrap @submit.prevent="saveData">
        <TextAreaInput :id="element.index" name="content" label="Content" />
        <div class="flex-none text-center mt-4">
            <BaseButton class="w-3/4" type="submit" variant="primary">
                Save
            </BaseButton>
        </div>
    </form>
</template>

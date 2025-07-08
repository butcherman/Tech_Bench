<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { closeEditor } from "@/Composables/Equipment/WorkbookEditor";
import { string, object } from "yup";
import { useForm } from "vee-validate";

const props = defineProps<{
    element: workbookElement;
}>();

const validationSchema = object({
    text: string().nullable(),
});

const initialValues = {
    text: props.element.text,
};

const { handleSubmit } = useForm({
    validationSchema: validationSchema,
    initialValues: initialValues,
});

/**
 * Update the Text of the field being modified.
 */
const saveData = handleSubmit((form) => {
    console.log(form);
    props.element.text = form.text;
    closeEditor();
});
</script>

<template>
    <form novalidate v-focustrap @submit.prevent="saveData">
        <TextInput :id="element.index" name="text" label="Fieldset Title" />
        <div class="flex-none text-center mt-4">
            <BaseButton class="w-3/4" type="submit" variant="primary">
                Save
            </BaseButton>
        </div>
    </form>
</template>

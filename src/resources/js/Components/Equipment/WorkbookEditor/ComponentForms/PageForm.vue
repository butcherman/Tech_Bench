<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { closeWbEditor } from "@/Composables/Equipment/WorkbookEditor.module";
import { boolean, object, string } from "yup";
import { useForm } from "vee-validate";

const props = defineProps<{
    component: workbookPage;
}>();

const validationSchema = object({
    title: string().required("Each Page Must have a Title"),
    internal_only: boolean().required(),
});

const initialValues = {
    title: props.component.title,
    internal_only: !props.component.canPublish,
};

const { handleSubmit } = useForm({
    validationSchema: validationSchema,
    initialValues: initialValues,
});

/**
 * Update the Text of the field being modified.
 */
const saveData = handleSubmit((form) => {
    props.component.title = form.title;
    props.component.canPublish = !form.internal_only;

    closeWbEditor();
});
</script>

<template>
    <form novalidate v-focustrap @submit.prevent="saveData">
        <TextInput :id="component.page" name="title" label="Page Title" />
        <SwitchInput
            id="internal-only"
            name="internal_only"
            label="Page is Internal Use Only"
            help="Determines if this page can be viewed publicly, or only by registered users."
        />
        <div class="flex-none text-center mt-4">
            <BaseButton class="w-3/4" type="submit" variant="primary">
                Save
            </BaseButton>
        </div>
    </form>
</template>

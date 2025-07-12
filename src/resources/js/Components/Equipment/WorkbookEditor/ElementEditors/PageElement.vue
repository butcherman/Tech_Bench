<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import SwitchInput from "@/Forms/_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import { closeEditor, imDirty } from "@/Composables/Equipment/WorkbookEditor";
import { string, object, boolean } from "yup";
import { useForm } from "vee-validate";

const props = defineProps<{
    page: workbookPage;
}>();

const validationSchema = object({
    title: string().required("Each Page Must have a Title"),
    internal_only: boolean().required(),
});

const initialValues = {
    title: props.page.title,
    internal_only: !props.page.canPublish,
};

const { handleSubmit } = useForm({
    validationSchema: validationSchema,
    initialValues: initialValues,
});

/**
 * Update the Text of the field being modified.
 */
const saveData = handleSubmit((form) => {
    props.page.title = form.title;
    props.page.canPublish = !form.internal_only;
    closeEditor();

    imDirty();
});
</script>

<template>
    <form novalidate v-focustrap @submit.prevent="saveData">
        <TextInput :id="page.page" name="title" label="Page Title" />
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

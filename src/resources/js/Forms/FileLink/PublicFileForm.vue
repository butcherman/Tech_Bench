<script setup lang="ts">
import TextAreaInput from "../_Base/TextAreaInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueFileForm from "../_Base/VueFileForm.vue";
import { object, string } from "yup";
import { useTemplateRef } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps<{
    link: fileLink;
}>();

const form = useTemplateRef("upload-file-form");

const onSuccessfulUpload = () => {
    let values = form.value?.values;

    if (values) {
        let formData = useForm(values);

        formData.put(route("guest-link.update", props.link.link_hash));
    }

    let name = form.value?.getFieldValue("name");
    form.value?.resetFileForm();
    form.value?.setFieldValue("name", name);
};

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    name: "",
    notes: "",
};
const schema = object({
    name: string().required(),
    notes: string().nullable(),
});
</script>

<template>
    <VueFileForm
        ref="upload-file-form"
        submit-text="Upload Files"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('guest-link.store', link.link_hash)"
        :max-files="5"
        file-required
        @success="onSuccessfulUpload"
    >
        <TextInput id="name" name="name" label="Your Name" focus />
        <template #after-file>
            <TextAreaInput
                id="notes"
                name="notes"
                label="Additional Information"
                :rows="10"
                placeholder="Tell us about this file..."
            />
        </template>
    </VueFileForm>
</template>

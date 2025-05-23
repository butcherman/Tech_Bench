<script setup lang="ts">
import Collapse from "@/Components/_Base/Collapse.vue";
import Editor from "../_Base/Editor.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueFileForm from "../_Base/VueFileForm.vue";
import { boolean, date, object, string } from "yup";
import { ref, useTemplateRef, watch } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps<{
    defaultExpire: string;
}>();

const form = useTemplateRef("file-link-form");
const hasInstructions = ref(false);

/**
 * After all files are uploaded, submit the empty form to create the file link.
 */
const handleSuccessfulUpload = () => {
    let values = form.value?.values;

    if (values) {
        let formData = useForm(values);

        formData.post(route("links.store"));
    }
};

/**
 * When Instructions box is hidden, delete any listed instructions.
 */
watch(hasInstructions, (newInstruction) => {
    if (!newInstruction) {
        form.value?.setFieldValue("instructions", "");
    }
});

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    link_name: "",
    expire: props.defaultExpire,
    allow_upload: false,
    instructions: "",
};
const schema = object({
    link_name: string().required().label("Link Name"),
    expire: date().required().typeError("Please enter a valid date"),
    allow_upload: boolean().required(),
    instructions: string().nullable(),
});
</script>

<template>
    <VueFileForm
        ref="file-link-form"
        submit-text="Create File Link"
        :initial-values="initValues"
        :max-files="5"
        :submit-route="$route('links.store')"
        :validation-schema="schema"
        @success="handleSuccessfulUpload"
    >
        <TextInput id="link-name" name="link_name" label="Link Name" focus />
        <TextInput id="expire" name="expire" label="Expire Date" type="date" />
        <div class="flex justify-center">
            <div>
                <SwitchInput
                    id="allow-upload"
                    name="allow_upload"
                    label="All Visitors to Upload Files"
                />
                <SwitchInput
                    id="add-instructions"
                    name="add_instructions"
                    label="Add Instructions"
                    @change="hasInstructions = !hasInstructions"
                />
            </div>
        </div>
        <Collapse :show="hasInstructions">
            <Editor
                id="instructions"
                name="instructions"
                label="Instructions"
            />
        </Collapse>
    </VueFileForm>
</template>

<script setup lang="ts">
import Collapse from "@/Components/_Base/Collapse.vue";
import Editor from "../_Base/Editor.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "@/Forms/_Base/VueForm.vue";
import { boolean, date, object, string } from "yup";
import { ref, useTemplateRef, watch } from "vue";

const props = defineProps<{
    link: fileLink;
    allowCustomUrl: boolean;
}>();

const form = useTemplateRef("file-link-form");
const hasInstructions = ref(props.link.instructions ? true : false);

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
    link_name: props.link.link_name,
    link_hash: props.link.link_hash,
    expire: props.link.expire,
    add_instructions: hasInstructions.value,
    allow_upload: props.link.allow_upload,
    instructions: props.link.instructions,
};
const schema = object({
    link_name: string().required().label("Link Name"),
    link_hash: string().required().label("Link URL"),
    expire: date().required().typeError("Please enter a valid date"),
    allow_upload: boolean().required(),
    instructions: string().nullable(),
});
</script>

<template>
    <VueForm
        ref="file-link-form"
        submit-method="put"
        submit-text="Update Link Details"
        :initial-values="initValues"
        :submit-route="$route('links.update', link.link_id)"
        :validation-schema="schema"
    >
        <TextInput id="link-name" name="link_name" label="Link Name" focus />
        <div v-if="allowCustomUrl" class="flex flex-row">
            <div
                class="border border-collapse rounded-e-none rounded-lg ps-2 py-2 my-2 text-muted"
            >
                {{ $route("guest-link.index") }}/
            </div>
            <TextInput
                id="link-hash"
                name="link_hash"
                label="Link URL"
                class="grow rounded-s-none"
            />
        </div>
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
    </VueForm>
</template>

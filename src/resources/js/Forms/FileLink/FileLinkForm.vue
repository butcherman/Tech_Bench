<template>
    <VueFileForm
        ref="fileLinkForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('links.upload')"
        :submit-text="submitText"
        :max-files="5"
        :hide-file-input="fileLink ? true : false"
        @success="handleSuccess"
    >
        <TextInput id="link-name" name="link_name" label="Link Name" focus />
        <TextInput id="expire" name="expire" label="Expire Date" type="date" />
        <div class="d-flex justify-content-center">
            <CheckboxSwitch
                id="allow-upload"
                name="allow_upload"
                label="Allow Visitors to Upload Files"
            />
        </div>
        <div class="mb-2">
            <div class="text-center">
                <button
                    type="button"
                    class="btn btn-primary btn-sm rounded-5"
                    @click="toggleInstructions"
                >
                    {{ instructionText }}
                </button>
            </div>
            <Transition @enter="growShow" @leave="shrinkHide" :css="false">
                <div
                    v-show="showInstructions"
                    ref="instructionBox"
                    id="instruction-box"
                >
                    <Editor
                        id="instructions"
                        name="instructions"
                        label="Instructions"
                    />
                </div>
            </Transition>
        </div>
    </VueFileForm>
</template>

<script setup lang="ts">
import VueFileForm from "../_Base/VueFileForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import Editor from "../_Base/Editor.vue";
import { computed, ref } from "vue";
import { boolean, date, object, string } from "yup";
import { useForm } from "@inertiajs/vue3";
import { growShow, shrinkHide } from "@/Modules/Animation.module";

const props = defineProps<{
    fileLink?: fileLink;
    defaultExpire?: string;
}>();

const fileLinkForm = ref<InstanceType<typeof VueFileForm> | null>(null);

const submitText = computed(() =>
    props.fileLink ? "Update File Link" : "Create File Link"
);

const initValues = {
    link_name: props.fileLink?.link_name || "",
    expire: props.fileLink?.expire || props.defaultExpire,
    allow_upload: props.fileLink?.allow_upload || false,
    instructions: props.fileLink?.instructions || "",
};
const schema = object({
    link_name: string().required().label("Link Name"),
    expire: date().required().typeError("Please enter a valid date"),
    allow_upload: boolean().required(),
    instructions: string().nullable(),
});

/**
 * Toggle box for inputting custom instructions
 */
const showInstructions = ref<boolean>(
    props.fileLink?.instructions ? true : false
);
const instructionText = computed<string>(() =>
    showInstructions.value ? "Remove Instructions" : "Add Instructions"
);
const toggleInstructions = () => {
    showInstructions.value = !showInstructions.value;

    if (!showInstructions.value) {
        fileLinkForm.value?.setFieldValue("instructions", "");
    }
};

const handleSuccess = () => {
    let values;
    if ((values = fileLinkForm.value?.values)) {
        let formData = useForm(values);

        if (props.fileLink) {
            formData.put(route("links.update", props.fileLink.link_id));
        } else {
            formData.post(route("links.store"));
        }
    }
};
</script>

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
        <div class="text-center mb-2">
            <button
                type="button"
                class="btn btn-primary btn-sm rounded-5"
                @click="toggleInstructions"
            >
                {{ instructionText }}
            </button>
            <div ref="instructionBox" id="instruction-box">
                <Editor
                    id="instructions"
                    name="instructions"
                    label="Instructions"
                />
            </div>
        </div>
    </VueFileForm>
</template>

<script setup lang="ts">
import VueFileForm from "../_Base/VueFileForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import Editor from "../_Base/Editor.vue";
import { computed, ref, onMounted } from "vue";
import { boolean, date, object, string } from "yup";
import { gsap } from "gsap";
import { useForm } from "@inertiajs/vue3";

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
const instructionBox = ref<InstanceType<typeof HTMLDivElement> | null>(null);
const showInstructions = ref<boolean>(false);
const instructionText = computed<string>(() =>
    showInstructions.value ? "Remove Instructions" : "Add Instructions"
);
const toggleInstructions = () => {
    gsap.to(instructionBox.value, {
        height: showInstructions.value ? 0 : "auto",
        duration: 0.5,
    });

    showInstructions.value = !showInstructions.value;

    if (!showInstructions.value) {
        fileLinkForm.value?.setFieldValue("instructions", "");
    }
};

const handleSuccess = (result: string) => {
    console.log("success", result);

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

onMounted(() => {
    if (props.fileLink?.instructions) {
        toggleInstructions();
    }
});
</script>

<style scoped lang="scss">
#instruction-box {
    overflow: hidden;
    height: 0;
}
</style>

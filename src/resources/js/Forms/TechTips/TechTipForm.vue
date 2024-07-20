<template>
    <VueFileForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="submitRoute"
        :submit-text="submitText"
        :hide-file-input="hideFile"
        @success="handleSuccess"
    >
        <TextInput id="subject" name="subject" label="Subject" focus />
        <SelectInput
            id="tip_type_id"
            name="tip_type_id"
            label="Tip Type"
            :list="tipTypes"
            text-field="description"
            value-field="tip_type_id"
        />
        <MultiSelectInput
            id="equipList"
            name="equipList"
            label="Equipment Types"
            :list="equipList"
            mode="tags"
            groups
            allow-select-all
        />
        <Editor id="details" name="details" label="Tip Details" />
        <div class="row justify-content-center">
            <div class="col-md-4 col-10 my-2">
                <button
                    type="button"
                    class="btn btn-info w-100"
                    @click="hideFile = !hideFile"
                >
                    Add File
                </button>
            </div>
            <div class="col-md-4 col-10 my-2">
                <button
                    type="button"
                    class="btn btn-info w-100"
                    @click="showAdvanced = !showAdvanced"
                >
                    Advanced Options
                </button>
            </div>
        </div>
        <Collapse :visible="showAdvanced">
            <div class="d-flex justify-content-center">
                <div>
                    <CheckboxSwitch
                        id="suppress-notifications"
                        name="suppress"
                        label="Suppress Notification"
                        help="When enabled, notification of a new Tech Tip being 
                              created will not be sent out"
                    />
                    <CheckboxSwitch
                        id="sticky"
                        name="sticky"
                        label="Make Sticky Tip"
                        help="Sticky Tips are prioritized to the top of the search list"
                    />
                    <CheckboxSwitch
                        id="public"
                        name="public"
                        label="Make Public Tip"
                        help="When enabled, this Tech Tip will be available for 
                              anyone to see.  Be sure that this Tech Tip does not
                              contain any sensitive information."
                    />
                </div>
            </div>
        </Collapse>
        <div class="mb-3" />
    </VueFileForm>
</template>

<script setup lang="ts">
import VueFileForm from "@/Forms/_Base/VueFileForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import MultiSelectInput from "../_Base/MultiSelectInput.vue";
import Editor from "../_Base/Editor.vue";
import CheckboxSwitch from "../_Base/CheckboxSwitch.vue";
import Collapse from "@/Components/_Base/Collapse.vue";
import { computed, ref } from "vue";
import { array, boolean, object, string } from "yup";

const props = defineProps<{
    techTip?: techTip;
    tipTypes: tipType[];
    equipList: {
        label: string;
        options: { text: string; value: string | number }[];
    }[];
}>();

const submitRoute = computed(() =>
    // props.techTip ? "#" : route("tech-tips.store")
    route("tech-tips.store")
);
const submitMethod = computed(() => (props.techTip ? "put" : "post"));
const submitText = computed(() =>
    props.techTip ? "Edit Tech Tip" : "Create Tech Tip"
);

const showAdvanced = ref(false);
const hideFile = ref(true);

const initValues = {
    subject: props.techTip?.subject || "",
    tip_type_id: props.techTip?.tip_type_id || null,
    equipList: [],
    suppress: false,
    sticky: props.techTip?.sticky || false,
    public: props.techTip?.public || false,
};
const schema = object({
    subject: string().required(),
    tip_type_id: string().required().label("Tip Type"),
    equipList: array()
        .required("Select at least one Equipment Type")
        .min(1)
        .label("Equipment List"),
    details: string().required().label("Details"),
    suppress: boolean().required(),
    sticky: boolean().required(),
    public: boolean().required(),
});

const handleSuccess = (result: { file: string[]; res: string }) => {
    console.log(result);
};
</script>

<template>
    <VueFileForm
        ref="techTipForm"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('tech-tips.upload')"
        :submit-text="submitText"
        :hide-file-input="hideFile"
        :max-files="5"
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
        <Editor
            id="details"
            name="details"
            label="Tip Details"
            image-folder="tech_tips"
        />
        <div
            v-if="techTip && techTip.file_upload?.length"
            class="border rounded"
        >
            <h5 class="text-center">Tip Files:</h5>
            <ul class="list-group m-4">
                <li
                    v-for="file in techTip.file_upload"
                    :key="file.file_id"
                    class="list-group-item"
                    :class="{
                        'bg-danger': removedFiles.includes(file.file_id),
                    }"
                >
                    {{ file.file_name }}
                    <span
                        v-show="!removedFiles.includes(file.file_id)"
                        class="float-end pointer"
                        title="Remove File"
                        v-tooltip
                        @click="toggleFile(file.file_id)"
                    >
                        <fa-icon icon="trash-alt" class="text-danger" />
                    </span>
                    <span
                        v-show="removedFiles.includes(file.file_id)"
                        class="float-end pointer"
                        title="Restore File"
                        v-tooltip
                        @click="toggleFile(file.file_id)"
                    >
                        <fa-icon icon="rotate" />
                    </span>
                </li>
            </ul>
        </div>
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
                        help="When enabled, notification of a new or updated
                              Tech Tip will not be sent out"
                    />
                    <CheckboxSwitch
                        id="sticky"
                        name="sticky"
                        label="Make Sticky Tip"
                        help="Sticky Tips are prioritized to the top of the search list"
                    />
                    <CheckboxSwitch
                        v-if="allowPublic"
                        id="public"
                        name="public"
                        label="Make Public Tip"
                        help="When enabled, this Tech Tip will be available for
                              anyone to see.  Be sure that this Tech Tip does not
                              contain any sensitive information."
                        @change="verifyPublic"
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
import { useForm } from "@inertiajs/vue3";
import verifyModal from "@/Modules/verifyModal";

const emit = defineEmits(["success"]);
const props = defineProps<{
    techTip?: techTip;
    tipTypes: tipType[];
    allowPublic: boolean;
    equipList: {
        label: string;
        options: { text: string; value: string | number }[];
    }[];
}>();

const techTipForm = ref<InstanceType<typeof VueFileForm> | null>(null);
const removedFiles = ref<number[]>([]);

const submitText = computed(() =>
    props.techTip ? "Edit Tech Tip" : "Create Tech Tip"
);

const showAdvanced = ref<boolean>(false);
const hideFile = ref<boolean>(true);

const initValues = {
    subject: props.techTip?.subject || "",
    tip_type_id: props.techTip?.tip_type_id || null,
    equipList: props.techTip?.equipList || [],
    details: props.techTip?.details || "",
    suppress: props.techTip ? true : false,
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

const handleSuccess = (result: string) => {
    let values;
    if ((values = techTipForm.value?.values)) {
        let formData = useForm(values);
        if (props.techTip) {
            formData.transform((data) => ({
                ...data,
                removedFiles: removedFiles.value,
            }));
            formData.put(route("tech-tips.update", props.techTip.tip_id));
        } else {
            formData.post(route("tech-tips.store"));
        }
    }
};

const toggleFile = (file_id: number) => {
    if (removedFiles.value.includes(file_id)) {
        removedFiles.value.splice(removedFiles.value.indexOf(file_id), 1);
    } else {
        removedFiles.value.push(file_id);
    }
};

const verifyPublic = (value: boolean) => {
    if (value) {
        verifyModal(
            `This Tech Tip will be available to non-registered visitors to this
             site.  Have you verified there is no confidential information in
             this Tech Tip?`,
            "Please Verify"
        ).then((res) => {
            if (!res) {
                techTipForm.value?.setFieldValue("public", false);
            }
        });
    }
};
</script>

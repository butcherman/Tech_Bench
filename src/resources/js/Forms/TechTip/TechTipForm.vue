<script setup lang="ts">
import BaseButton from "@/Components/_Base/Buttons/BaseButton.vue";
import Collapse from "@/Components/_Base/Collapse.vue";
import DeleteBadge from "@/Components/_Base/Badges/DeleteBadge.vue";
import Editor from "../_Base/Editor.vue";
import MultiSelectInput from "../_Base/MultiSelectInput.vue";
import ResourceList from "@/Components/_Base/ResourceList.vue";
import SelectInput from "../_Base/SelectInput.vue";
import SwitchInput from "../_Base/SwitchInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueFileForm from "../_Base/VueFileForm.vue";
import { computed, ref, useTemplateRef } from "vue";
import { object, string, array, boolean } from "yup";
import { useForm } from "@inertiajs/vue3";

const props = defineProps<{
    allowPublic: boolean;
    tipTypes: tipType[];
    equipTypes: { [key: string]: customerEquipment[] }[];
    equipList?: number[];
    fileList?: fileUpload[];
    techTip?: techTip;
}>();

const tipForm = useTemplateRef("tech-tip-form");
const showAdvanced = ref<boolean>(false);
const showFile = ref<boolean>(false);

const removedFiles = ref<number[]>([]);
const toggleRemovedFile = (fileId: number): void => {
    if (removedFiles.value.includes(fileId)) {
        removedFiles.value.splice(removedFiles.value.indexOf(fileId), 1);
    } else {
        removedFiles.value.push(fileId);
    }
};

/*
|-------------------------------------------------------------------------------
| Handle Form
|-------------------------------------------------------------------------------
*/
const submitText = computed(() =>
    props.techTip ? "Update Tech Tip" : "Create Tech Tip"
);

/**
 * After all files are uploaded, we need to process the rest of the form
 */
const onSuccessfulUpload = () => {
    if (!tipForm.value) {
        return;
    }
    const formData = useForm(tipForm.value?.values);

    if (props.techTip) {
        formData.transform((data) => ({
            ...data,
            removedFiles: removedFiles.value,
        }));

        formData.put(route("tech-tips.update", props.techTip.slug));

        return;
    }

    formData.post(route("tech-tips.store"));
};

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    subject: props.techTip?.subject || "",
    tip_type_id: props.techTip?.tip_type_id || null,
    equipList: props.equipList || [],
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
</script>

<template>
    <VueFileForm
        ref="tech-tip-form"
        :hide-file-input="!showFile"
        :initial-values="initValues"
        :max-files="5"
        :submit-route="$route('tech-tips.upload-file')"
        :submit-text="submitText"
        :validation-schema="schema"
        @success="onSuccessfulUpload"
    >
        <TextInput id="subject" name="subject" label="Subject" focus />
        <SelectInput
            id="tip_type_id"
            label="Tip Type"
            name="tip_type_id"
            text-field="description"
            value-field="tip_type_id"
            :list="tipTypes"
        />
        <MultiSelectInput
            id="equipList"
            class="my-3"
            name="equipList"
            label="Equipment Types"
            :list="equipTypes"
            group-text-field="label"
            group-children-field="items"
            text-field="label"
            value-field="value"
            filter
        />
        <Editor
            id="details"
            name="details"
            label="Tip Details"
            image-folder="tech_tips"
        />
        <div v-if="fileList" class="my-2 flex justify-center">
            <div class="tb-card">
                <h5>Attached Files:</h5>
                <ResourceList :list="fileList">
                    <template #list-item="{ item }">
                        <div
                            class="w-full p-1"
                            :class="{
                                'bg-red-300': removedFiles.includes(
                                    item.file_id
                                ),
                            }"
                        >
                            {{ item.file_name }}
                        </div>
                    </template>
                    <template #actions="{ item }">
                        <a
                            :href="
                                $route('download', [
                                    item.file_id,
                                    item.file_name,
                                ])
                            "
                            class="text-blue-400 me-1"
                            v-tooltip.left="'Download File'"
                        >
                            <fa-icon icon="download" />
                        </a>
                        <DeleteBadge
                            v-if="!removedFiles.includes(item.file_id)"
                            v-tooltip.left="'Remove File'"
                            @click="toggleRemovedFile(item.file_id)"
                        />
                        <span
                            v-else
                            class="pointer text-blue-500"
                            v-tooltip.left="'Restore File'"
                            @click="toggleRemovedFile(item.file_id)"
                        >
                            <fa-icon icon="rotate" />
                        </span>
                    </template>
                </ResourceList>
            </div>
        </div>
        <div class="flex justify-center">
            <BaseButton
                text="Add File"
                class="w-1/3 mx-2"
                variant="info"
                @click="showFile = !showFile"
            />
            <BaseButton
                text="Advanced Options"
                class="w-1/3 mx-2"
                variant="info"
                @click="showAdvanced = !showAdvanced"
            />
        </div>
        <Collapse :show="showAdvanced" class="mb-4">
            <div class="flex justify-center">
                <div>
                    <SwitchInput
                        id="suppress"
                        name="suppress"
                        label="Suppress Notifications"
                    />
                    <SwitchInput
                        id="sticky"
                        name="sticky"
                        label="Make Sticky Tip"
                    />
                    <SwitchInput
                        v-if="allowPublic"
                        id="public"
                        name="public"
                        label="Make Tip Public"
                    />
                </div>
            </div>
        </Collapse>
    </VueFileForm>
</template>

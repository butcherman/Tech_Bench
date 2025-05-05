<script setup lang="ts">
import PickListInput from "../_Base/PickListInput.vue";
import RadioGroupInput from "../_Base/RadioGroupInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueFileForm from "../_Base/VueFileForm.vue";
import { growShow, shrinkHide } from "@/Composables/animations.module";
import { object, string, array } from "yup";
import { ref, useTemplateRef } from "vue";
import type { DropzoneFile } from "dropzone";

type fileCategory = "general" | "site" | "equipment";

defineEmits<{
    success: [];
    submitting: [];
}>();

const props = defineProps<{
    customer: customer;
    siteList: customerSite[];
    equipList: customerEquipment[];
    fileTypes: customerFileType[];
    currentSite?: customerSite;
    equipment?: customerEquipment;
}>();

const form = useTemplateRef("add-file-form");

/**
 * If the name field is empty when a file is dropped, add the filename field
 */
const checkNameField = (file: DropzoneFile) => {
    console.log("check name field");

    if (
        form.value?.getFieldValue("name") === undefined ||
        !form.value?.getFieldValue("name").length
    ) {
        let fileName = file.name;
        form.value?.setFieldValue("name", fileName);
    }
};

/*
|-------------------------------------------------------------------------------
| Determine the type of file being created/edited.
|-------------------------------------------------------------------------------
*/
const getInitFileCategory = (): fileCategory => {
    // if ((props.note && props.note.cust_equip_id) || props.equipment) {
    //     return "equipment";
    // }

    // if (
    //     (props.note && props.note.sites.length > 0) ||
    //     (props.currentSite && props.siteList.length > 1)
    // ) {
    //     return "site";
    // }

    return "general";
};

const updateFileCategory = (type: fileCategory): void => {
    fileCategory.value = type;
    console.log(type);
};

const fileCategory = ref<fileCategory>(getInitFileCategory());

const fileCategories: { label: string; value: fileCategory }[] = [
    {
        label: "General File",
        value: "general",
    },
    {
        label: "Equipment File",
        value: "equipment",
    },
];

if (props.siteList.length > 1) {
    fileCategories.push({
        label: "Site File",
        value: "site",
    });
}

/*
|-------------------------------------------------------------------------------
| Vee Validate
|-------------------------------------------------------------------------------
*/
const initValues = {
    name: "",
    file_type: fileCategory.value,
    site_list: props.currentSite ? [props.currentSite.cust_site_id] : [],
    cust_equip_id: props.equipment?.cust_equip_id || null,
    file_type_id: null,
};
const schema = object({
    name: string().required(),
    file_type: string().required(),
    site_list: array().when("file_type", {
        is: "site",
        then: (schema) =>
            schema
                .min(1, "Select at least one site")
                .required("Select at least one site"),
        otherwise: (schema) => schema.nullable(),
    }),
    cust_equip_id: string().when("file_type", {
        is: "equipment",
        then: (schema) =>
            schema.required("Select the Equipment to attach this file to"),
        otherwise: (schema) => schema.nullable(),
    }),
    file_type_id: string().required("What type of file is this?"),
});
</script>

<template>
    <VueFileForm
        ref="add-file-form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="$route('customers.files.store', customer.slug)"
        submit-text="Upload File"
        @file-added="checkNameField"
        @success="$emit('success')"
        @submitting="$emit('submitting')"
    >
        <TextInput
            id="name"
            name="name"
            label="Name"
            placeholder="Enter A Descriptive Name"
        />
        <SelectInput
            id="type"
            name="file_type_id"
            label="File Type"
            :list="fileTypes"
            text-field="description"
            value-field="file_type_id"
        />
        <div class="flex justify-center mt-2">
            <RadioGroupInput
                id="file-category"
                name="file_type"
                :list="fileCategories"
                inline
                @change="updateFileCategory"
            />
        </div>
        <TransitionGroup @enter="growShow" @leave="shrinkHide">
            <PickListInput
                v-if="fileCategory === 'site'"
                id="site-list"
                name="site_list"
                label="Select which Sites this file is relevant to"
                label-field="site_name"
                value-field="cust_site_id"
                :list="siteList"
            />
            <SelectInput
                v-if="fileCategory === 'equipment'"
                id="cust_equip-id"
                name="cust_equip_id"
                label="Select which Equipment this file is relevant to"
                :list="equipList"
                text-field="equip_name"
                value-field="cust_equip_id"
            >
                <template #option="{ option }">
                    {{ option.equip_name }}
                    <span v-if="!currentSite">
                        &nbsp; ({{ option.sites[0].site_name }})
                    </span>
                </template>
            </SelectInput>
        </TransitionGroup>
    </VueFileForm>
</template>

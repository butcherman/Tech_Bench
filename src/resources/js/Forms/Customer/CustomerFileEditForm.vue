<template>
    <VueForm
        ref="form"
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="
            $route('customers.files.update', [
                customer.slug,
                customerFile.cust_file_id,
            ])
        "
        submit-method="put"
        submit-text="Update File Data"
        @success="$emit('success')"
    >
        <TextInput
            id="name"
            name="name"
            label="Name"
            placeholder="Enter A Descriptive Name"
            focus
        />
        <RadioGroupInput
            id="file-type"
            name="file_type"
            :list="fileFormTypes"
            class="text-center"
            inline
            @change="updateFileFormType"
        />
        <SelectBoxInput
            v-if="fileFormType === 'site'"
            id="equipment-site-list"
            name="site_list"
            label="Select which Sites this note is relevant to"
            text-field="site_name"
            value-field="cust_site_id"
            :list="siteList"
        />
        <SelectInput
            v-if="fileFormType === 'equipment'"
            id="cust_equip-id"
            name="cust_equip_id"
            label="Select which Equipment this note is relevant to"
            :list="equipList"
            text-field="equip_name"
            value-field="cust_equip_id"
        />
        <SelectInput
            id="type"
            name="file_type_id"
            label="File Type"
            :list="fileTypes"
            text-field="description"
            value-field="file_type_id"
        />
    </VueForm>
</template>

<script setup lang="ts">
import VueForm from "@/Forms/_Base/VueForm.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import RadioGroupInput from "../_Base/RadioGroupInput.vue";
import SelectBoxInput from "../_Base/SelectBoxInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import { ref } from "vue";
import { object, string, array } from "yup";

defineEmits(["success"]);
const props = defineProps<{
    customer: customer;
    siteList: customerSite[];
    customerFile: customerFile;
    equipList: customerEquipment[];
    fileTypes: customerFileType[];
}>();

/**
 * Determine the note type based on prop parameters
 */
const getFileFormType = (): "general" | "site" | "equipment" => {
    if (props.customerFile.cust_equip_id) {
        return "equipment";
    }

    if (props.customerFile.customer_site.length > 0) {
        return "site";
    }

    return "general";
};
const updateFileFormType = (type: "general" | "site" | "equipment") => {
    fileFormType.value = type;
};
const fileFormType = ref(getFileFormType());

/**
 * Files can be assigned to a specific equipment or site.  Unselected will be
 * a General note
 */
const fileFormTypes = [
    {
        text: "General File",
        value: "general",
    },
    {
        text: "Equipment File",
        value: "equipment",
    },
];
if (props.siteList.length > 1) {
    fileFormTypes.push({
        text: "Site File",
        value: "site",
    });
}

const initValues = {
    name: props.customerFile.name,
    file_type: fileFormType.value,
    site_list: props.customerFile.customer_site.map(
        (site) => site.cust_site_id
    ),
    cust_equip_id: props.customerFile.cust_equip_id,
    file_type_id: props.customerFile.file_type_id,
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

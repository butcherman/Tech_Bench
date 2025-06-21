<script setup lang="ts">
import PickListInput from "../_Base/PickListInput.vue";
import RadioGroupInput from "../_Base/RadioGroupInput.vue";
import SelectInput from "../_Base/SelectInput.vue";
import TextInput from "@/Forms/_Base/TextInput.vue";
import VueForm from "../_Base/VueForm.vue";
import { growShow, shrinkHide } from "@/Composables/animations.module";
import { object, string, array } from "yup";
import { ref } from "vue";
import { toLower } from "lodash";

type fileCategory = "general" | "site" | "equipment";

defineEmits<{
    success: [];
}>();

const props = defineProps<{
    customer: customer;
    siteList: customerSite[];
    equipList: customerEquipment[];
    fileTypes: customerFileType[];
    currentSite?: customerSite;
    equipment?: customerEquipment;
    customerFile: customerFile;
}>();

/*
|-------------------------------------------------------------------------------
| Determine the type of file being created/edited.
|-------------------------------------------------------------------------------
*/
const getInitFileCategory = (): fileCategory => {
    return toLower(props.customerFile.file_category);
};

const updateFileCategory = (type: fileCategory): void => {
    fileCategory.value = type;
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
    name: props.customerFile.name,
    file_type: fileCategory.value,
    site_list: props.customerFile.sites.map((site) => site.cust_site_id),
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

<template>
    <VueForm
        :initial-values="initValues"
        :validation-schema="schema"
        :submit-route="
            $route('customers.files.update', [
                customer.slug,
                customerFile.cust_file_id,
            ])
        "
        :only="['fileList']"
        submit-method="put"
        submit-text="Update File Data"
        @success="$emit('success')"
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
    </VueForm>
</template>
